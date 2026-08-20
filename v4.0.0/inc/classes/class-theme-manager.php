<?php
/**
 * This file contain the Theme_Manager class and it's functionalities for menu.
 *
 * @version 4.0.0
 * @author  Expresstech System
 *
 * @package responsive-menu
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;
use RMP\Features\Inc\Option_Manager;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Manager
 * This class is handling the menu themes and its functionalities.
 *
 * @since 4.0.0
 */
class Theme_Manager {

	use Singleton;

	/**
	 * This is option key where saved themes are stored.
	 *
	 * @var string $theme_option
	 */
	protected static $theme_option = 'rmp_themes';

	/**
	 * This is default theme preview image url
	 *
	 * @var string $theme_preview_img
	 */
	public $theme_preview_img = RMP_PLUGIN_URL_V4 . '/assets/images/default-theme-preview.png';

	/**
	 * Capability required to upload a menu theme.
	 *
	 * A menu theme ships a PHP index file which the plugin loads with
	 * require_once on every request, so uploading one is equivalent to
	 * installing a plugin and must require the same capability. On multisite
	 * `install_plugins` belongs to Super Admins only, which closes the
	 * privilege boundary a site Administrator could otherwise cross.
	 *
	 * @since 4.7.3
	 *
	 * @var string
	 */
	const THEME_UPLOAD_CAPABILITY = 'install_plugins';

	/**
	 * File extensions permitted inside an uploaded theme archive.
	 *
	 * PHP is on the list because the theme format requires it — see
	 * self::THEME_UPLOAD_CAPABILITY for the control that actually gates the
	 * upload. The list exists to keep files that could re-configure the web
	 * server (.htaccess, web.config, php.ini, .user.ini) out of the uploads
	 * directory, since those would undo the guards written by
	 * self::protect_upload_dir().
	 *
	 * @since 4.7.3
	 *
	 * @var array
	 */
	const ALLOWED_THEME_FILE_EXTENSIONS = array(
		'php',
		'json',
		'css',
		'scss',
		'sass',
		'less',
		'js',
		'map',
		'png',
		'jpg',
		'jpeg',
		'gif',
		'svg',
		'webp',
		'ico',
		'bmp',
		'woff',
		'woff2',
		'ttf',
		'otf',
		'eot',
		'txt',
		'md',
		'html',
		'htm',
		'xml',
		'po',
		'mo',
		'pot',
	);

	/**
	 * Archive entries that are ignored rather than rejected.
	 *
	 * Zips built on macOS and Windows carry this housekeeping alongside the
	 * theme files. Rejecting an otherwise valid theme because of them would
	 * be a needless failure.
	 *
	 * @since 4.7.3
	 *
	 * @var array
	 */
	const IGNORED_THEME_FILES = array(
		'.ds_store',
		'thumbs.db',
		'.gitkeep',
		'.gitignore',
	);

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_action( 'wp_ajax_rmp_save_theme', array( $this, 'rmp_save_theme' ) );
		add_action( 'admin_post_rmp_upload_theme_file', array( $this, 'rmp_upload_theme' ) );
		add_action( 'wp_ajax_rmp_menu_theme_upload', array( $this, 'rmp_theme_upload_from_wizard' ) );
		add_action( 'wp_ajax_rmp_theme_delete', array( $this, 'rmp_theme_delete' ) );
		add_action( 'wp_ajax_rmp_theme_apply', array( $this, 'rmp_theme_apply' ) );
		add_action( 'wp_ajax_rmp_call_theme_api', array( $this, 'update_theme_api_cache' ) );
		add_action( 'admin_init', array( $this, 'maybe_protect_upload_dir' ) );
	}

	/**
	 * Writes the uploads-directory guards once per plugin version.
	 *
	 * The activation hook does not run when a plugin is updated, so installs
	 * that already hold uploaded themes would never receive the guards
	 * otherwise.
	 *
	 * @since 4.7.3
	 *
	 * @return void
	 */
	public function maybe_protect_upload_dir() {

		if ( RMP_PLUGIN_VERSION === get_option( 'rmp_upload_dir_protected' ) ) {
			return;
		}

		if ( $this->protect_upload_dir() ) {
			update_option( 'rmp_upload_dir_protected', RMP_PLUGIN_VERSION );
		}
	}


	/**
	 * Function to get the list of pro theme from store.
	 *
	 * @since 4.0.0
	 *
	 * @return array $pro_themes
	 */
	public function get_themes_by_api() {

		// If theme list is cached then access it.
		$pro_themes = get_transient( 'rmp_theme_api_response' );
		if ( ! empty( $pro_themes ) ) {
			return $pro_themes;
		}

		$pro_themes = array();

		// These are older version theme which are not compatible with new version.
		$exclude_theme_ids = array( '47704', '47698', '45318' );

		$endpoint_url      = 'https://responsive.menu/edd-api/v2/products/?category=themes';
		$rmp_response      = wp_remote_get( $endpoint_url, array( 'sslverify' => false ) );
		$rmp_response_body = wp_remote_retrieve_body( $rmp_response );
		$rmp_response_body = json_decode( $rmp_response_body, true );
		if ( ! empty( $rmp_response_body ) && is_array( $rmp_response_body ) ) {
			foreach ( $rmp_response_body['products'] as $key => $product ) {
				if ( ! in_array( $product['info']['id'], $exclude_theme_ids, true ) ) {
					$pro_themes[] = array(
						'name'        => $product['info']['title'],
						'slug'        => $product['info']['slug'],
						'preview_url' => $product['info']['thumbnail'],
						'demo_link'   => ! empty( $product['info']['demo_link'] ) ? $product['info']['demo_link'] : '',
						'buy_link'    => $product['info']['link'],
						'price'       => $product['pricing']['amount'],
					);
				}
			}
		}

		// Cache the theme response.
		set_transient( 'rmp_theme_api_response', $pro_themes, DAY_IN_SECONDS );

		return $pro_themes;
	}

	/**
	 * Function to apply the theme in the menu.
	 *
	 * @since 4.0.0
	 *
	 * @return json
	 */
	public function rmp_theme_apply() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( array( 'message' => __( 'You can not apply themes !', 'responsive-menu' ) ) );
		}

		$theme_name = isset( $_POST['theme_name'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_name'] ) ) : '';
		if ( empty( $theme_name ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Theme Name Missing', 'responsive-menu' ) ) );
		}

		$theme_type  = isset( $_POST['theme_type'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_type'] ) ) : '';
		$menu_id     = isset( $_POST['menu_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_id'] ) ) : '';
		$menu_to_use = isset( $_POST['menu_to_use'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_to_use'] ) ) : '';

		if ( 'template' === $theme_type ) {
			$theme_option = $this->get_saved_theme_options( $theme_name );
		} else {
			$theme_option = $this->get_available_theme_settings( $theme_name );
		}

		$theme_option['menu_id']     = $menu_id;
		$theme_option['menu_theme']  = $theme_name;
		$theme_option['theme_type']  = $theme_type;
		$theme_option['menu_to_use'] = $menu_to_use;

		update_post_meta( $menu_id, 'rmp_menu_meta', $theme_option );

		/**
		 * Fires when menu theme applied and options are saved.
		 *
		 * @since 4.0.0
		 * @param int $menu_id
		 */
		do_action( 'rmp_theme_apply', $menu_id );

		wp_send_json_success( array( 'message' => esc_html__( 'Theme applied', 'responsive-menu' ) ) );
	}

	/**
	 * Function to get the theme options from availbale theme.
	 *
	 * @since 4.0.0
	 * @since 4.1.0 Add plugin bundle themes, Rename the function and Check minimum version support.
	 *
	 * @return array
	 */
	public function get_available_theme_settings( $theme_name ) {

		// Themes from uploads directory.
		$theme_dir_path   = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
		$upload_theme_url = wp_upload_dir()['baseurl'] . '/rmp-menu/themes';
		$theme_dirs       = glob( $theme_dir_path . '/*', GLOB_ONLYDIR );

		// Themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*', GLOB_ONLYDIR ), $theme_dirs );

		$options     = array();
		$min_version = '4.0.0';

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		foreach ( $theme_dirs as $theme_dir ) {
			$config_file = $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( $wp_filesystem->get_contents( $config_file ), true );
				if ( $config['name'] == $theme_name ) {
					$min_version = ! empty( $config['min_rm_version'] ) ? $config['min_rm_version'] : '4.0.0';
					$options     = json_decode( $wp_filesystem->get_contents( $theme_dir . '/options.json' ), true );
					break;
				}
			}
		}
		// Check menu theme minimum version compatibility.
		if ( version_compare( RMP_PLUGIN_VERSION, $min_version, '<' ) ) {
			wp_send_json_error(
				array(
					'message' => sprintf(
						'%s required Responsive Menu %s version or higher. Please update the plugin with the latest version.',
						$theme_name,
						$min_version
					),
				)
			);
		}

		/**
		 * Filters the theme setting options.
		 *
		 * @since 4.0.1
		 *
		 * @param array  $option
		 * @param string $theme_name
		 */
		$options = apply_filters( 'get_available_theme_settings', $options, $theme_name );

		return $options;
	}

	/**
	 * Function to delete the theme.
	 *
	 * @since 4.0.0
	 * @since 4.1.0 Added condition for active theme.
	 *
	 * @return json
	 */
	public function rmp_theme_delete() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( array( 'message' => __( 'You can not delete themes !', 'responsive-menu' ) ) );
		}

		$theme_name = isset( $_POST['theme_name'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_name'] ) ) : '';
		if ( empty( $theme_name ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Theme Name Missing', 'responsive-menu' ) ) );
		}

		$theme_type = isset( $_POST['theme_type'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_type'] ) ) : '';

		if ( $this->is_active_theme( $theme_name, $theme_type ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'This theme is currently active. Please choose another theme and then try deleting.', 'responsive-menu' ) ) );
		}

		if ( 'template' === $theme_type ) {
			$this->delete_template( $theme_name );
		} else {
			$this->delete_theme_folder( $theme_name );
		}

		wp_send_json_success( array( 'message' => esc_html__( 'Theme deleted', 'responsive-menu' ) ) );
	}

	/**
	 * Function to return the theme dir path.
	 *
	 * @since 4.0.0
	 * @since 4.1.0 Added the plugin bundle theme.
	 *
	 * @return string
	 */
	public function get_theme_dir( $theme_name ) {

		// Themes from uploads directory.
		$upload_theme_url = wp_upload_dir()['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path   = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
		$theme_dirs       = glob( $theme_dir_path . '/*', GLOB_ONLYDIR );

		// Themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*', GLOB_ONLYDIR ), $theme_dirs );
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		foreach ( $theme_dirs as $theme_dir ) {
			$config_file = $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( $wp_filesystem->get_contents( $config_file ), true );
				if ( $config['name'] == $theme_name ) {
					return $theme_dir;
				}
			}
		}

		return false;
	}

	/**
	 * Function to delete the theme dir.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function delete_theme_folder( $theme_name ) {
		if ( empty( $theme_name ) ) {
			return;
		}

		$theme_dir = $this->get_theme_dir( $theme_name );
		if ( empty( $theme_dir ) ) {
			return;
		}

		$this->delete_files( $theme_dir );
	}

	/**
	 * Function to delete the theme files.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function delete_files( $dir ) {
		foreach ( glob( $dir . '/*' ) as $file ) {
			if ( is_dir( $file ) ) {
				delete_files( $file );
			} else {
				unlink( $file );
			}
		}
		rmdir( $dir );
	}

	/**
	 * Function to delete the saved template.
	 *
	 * @since 4.0.0
	 *
	 * @return boolean
	 */
	public function delete_template( $theme_name ) {
		$rmp_themes = get_option( self::$theme_option );

		if ( empty( $rmp_themes ) ) {
			return false;
		}

		foreach ( $rmp_themes as $theme_key => $options ) {
			if ( $theme_name === $theme_key ) {
				unset( $rmp_themes[ $theme_key ] );
				update_option( self::$theme_option, $rmp_themes );
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the directory uploaded menu themes are extracted into.
	 *
	 * @since 4.7.3
	 *
	 * @return string Trailing-slashed absolute path.
	 */
	public function get_theme_upload_dir() {
		return trailingslashit( wp_upload_dir()['basedir'] ) . 'rmp-menu/themes/';
	}

	/**
	 * Writes the hardening guards into the plugin's uploads directory.
	 *
	 * Menu themes legitimately contain a PHP file, and that file is meant to
	 * be loaded by the plugin — never requested over HTTP. This drops an
	 * .htaccess that refuses to serve or execute scripts under
	 * uploads/rmp-menu/, plus a silent index.php so the directory cannot be
	 * browsed. Stylesheets and theme images keep working because only script
	 * types are denied, not the whole directory.
	 *
	 * Both files are written only when missing, so a host that has customised
	 * them is left alone.
	 *
	 * Note: .htaccess is honoured by Apache and LiteSpeed only. nginx has no
	 * per-directory config, so those installs need the equivalent `location`
	 * rule in the server configuration — see readme.txt.
	 *
	 * @since 4.7.3
	 *
	 * @return bool True when both guards are in place.
	 */
	public function protect_upload_dir() {

		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		if ( empty( $wp_filesystem ) ) {
			return false;
		}

		$base_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'rmp-menu/';

		if ( ! $wp_filesystem->is_dir( $base_dir ) && ! wp_mkdir_p( $base_dir ) ) {
			return false;
		}

		$htaccess = $base_dir . '.htaccess';

		if ( ! $wp_filesystem->exists( $htaccess ) ) {
			$rules = "# Responsive Menu: never serve or execute scripts from this directory.\n"
				. "<IfModule mod_php.c>\n\tphp_flag engine off\n</IfModule>\n"
				. "<IfModule mod_php7.c>\n\tphp_flag engine off\n</IfModule>\n"
				. "<IfModule mod_php8.c>\n\tphp_flag engine off\n</IfModule>\n"
				. "<FilesMatch \"\\.(?i:php|php[0-9]|phps|phtml|pht|phar|inc|hphp|cgi|pl|py|sh)$\">\n"
				. "\t<IfModule mod_authz_core.c>\n\t\tRequire all denied\n\t</IfModule>\n"
				. "\t<IfModule !mod_authz_core.c>\n\t\tOrder allow,deny\n\t\tDeny from all\n\t</IfModule>\n"
				. "</FilesMatch>\n";

			$wp_filesystem->put_contents( $htaccess, $rules, FS_CHMOD_FILE );
		}

		$index = $base_dir . 'index.php';

		if ( ! $wp_filesystem->exists( $index ) ) {
			$wp_filesystem->put_contents( $index, "<?php\n// Silence is golden.\n", FS_CHMOD_FILE );
		}

		return $wp_filesystem->exists( $htaccess ) && $wp_filesystem->exists( $index );
	}

	/**
	 * Returns the list of entry names held in a zip archive.
	 *
	 * Opening the archive is also how the upload is confirmed to be a real
	 * zip: anything that is not one fails to open here, which is a stronger
	 * check than trusting the uploaded file name.
	 *
	 * Mirrors the readers unzip_file() itself uses, so an archive that lists
	 * here is one that will extract.
	 *
	 * @since 4.7.3
	 *
	 * @param string $file Absolute path to the archive.
	 *
	 * @return array|\WP_Error Entry names, or WP_Error when the file is not a readable zip.
	 */
	protected function get_archive_file_list( $file ) {

		$error = new \WP_Error(
			'rmp_invalid_archive',
			esc_html__( 'This file could not be read as a zip archive.', 'responsive-menu' )
		);

		$entries = array();

		if ( class_exists( 'ZipArchive' ) ) {
			$zip = new \ZipArchive();

			if ( true !== $zip->open( $file ) ) {
				return $error;
			}

			for ( $index = 0; $index < $zip->numFiles; $index++ ) {
				$entry = $zip->statIndex( $index );

				if ( false !== $entry ) {
					$entries[] = $entry['name'];
				}
			}

			$zip->close();

			return $entries;
		}

		require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';

		$archive  = new \PclZip( $file );
		$contents = $archive->listContent();

		if ( ! is_array( $contents ) ) {
			return $error;
		}

		foreach ( $contents as $entry ) {
			$entries[] = $entry['filename'];
		}

		return $entries;
	}

	/**
	 * Returns the archive entries that a theme is not allowed to contain.
	 *
	 * @since 4.7.3
	 *
	 * @param array $entries Entry names from the archive.
	 *
	 * @return array Offending entry names.
	 */
	protected function get_disallowed_theme_files( $entries ) {

		$disallowed = array();

		foreach ( $entries as $entry ) {

			// Directories carry no payload of their own.
			if ( '/' === substr( $entry, -1 ) ) {
				continue;
			}

			// Editor and OS metadata that is harmless but common in theme zips.
			if ( 0 === strpos( $entry, '__MACOSX/' ) ) {
				continue;
			}

			$basename = basename( $entry );

			if ( in_array( strtolower( $basename ), self::IGNORED_THEME_FILES, true ) ) {
				continue;
			}

			/*
			 * unzip_file() drops traversing entries itself, but a theme has no
			 * reason to carry one, so treat it as a rejected upload rather than
			 * a silently skipped file.
			 */
			if ( 0 !== validate_file( $entry ) || '/' === substr( $entry, 0, 1 ) ) {
				$disallowed[] = $entry;
				continue;
			}

			$extension = strtolower( pathinfo( $basename, PATHINFO_EXTENSION ) );

			// Files with no extension cannot be handed to an interpreter.
			if ( '' === $extension ) {
				continue;
			}

			if ( ! in_array( $extension, self::ALLOWED_THEME_FILE_EXTENSIONS, true ) ) {
				$disallowed[] = $entry;
			}
		}

		return $disallowed;
	}

	/**
	 * Validates and extracts an uploaded menu theme archive.
	 *
	 * Shared by the settings-page upload and the setup-wizard upload so both
	 * entry points enforce the same rules.
	 *
	 * @since 4.7.3
	 *
	 * @return true|\WP_Error True on success, WP_Error describing the refusal otherwise.
	 */
	protected function process_theme_upload() {

		if ( ! current_user_can( self::THEME_UPLOAD_CAPABILITY ) ) {
			return new \WP_Error(
				'rmp_theme_upload_forbidden',
				esc_html__( 'You can not upload themes !', 'responsive-menu' )
			);
		}

		$tmp_name = isset( $_FILES['file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['tmp_name'] ) ) : '';

		if ( empty( $tmp_name ) || ! is_uploaded_file( $tmp_name ) ) {
			return new \WP_Error(
				'rmp_theme_upload_missing',
				esc_html__( 'Please add zip file !', 'responsive-menu' )
			);
		}

		// Reject on the file name first — a cheap check before reading the file.
		$file_name = isset( $_FILES['file']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['file']['name'] ) ) : '';
		$file_type = wp_check_filetype( $file_name, array( 'zip' => 'application/zip' ) );

		if ( 'zip' !== $file_type['ext'] ) {
			return new \WP_Error(
				'rmp_theme_upload_not_zip',
				esc_html__( 'Please add zip file !', 'responsive-menu' )
			);
		}

		// Reading the archive proves the contents are a zip, not just the name.
		$entries = $this->get_archive_file_list( $tmp_name );

		if ( is_wp_error( $entries ) ) {
			return $entries;
		}

		$disallowed = $this->get_disallowed_theme_files( $entries );

		if ( ! empty( $disallowed ) ) {
			return new \WP_Error(
				'rmp_theme_upload_disallowed_file',
				sprintf(
					/* translators: %s: comma separated list of file names. */
					esc_html__( 'This theme was not installed because it contains files that are not allowed: %s', 'responsive-menu' ),
					esc_html( implode( ', ', array_slice( $disallowed, 0, 5 ) ) )
				)
			);
		}

		if ( ! $this->archive_has_theme_config( $entries ) ) {
			return new \WP_Error(
				'rmp_theme_upload_not_a_theme',
				esc_html__( 'This zip does not look like a menu theme — no config.json was found inside it.', 'responsive-menu' )
			);
		}

		// Make sure the guards exist before anything is written next to them.
		$this->protect_upload_dir();

		$unzipped = unzip_file( $tmp_name, $this->get_theme_upload_dir() );

		if ( is_wp_error( $unzipped ) ) {
			return $unzipped;
		}

		return true;
	}

	/**
	 * Checks that an archive carries a theme manifest.
	 *
	 * @since 4.7.3
	 *
	 * @param array $entries Entry names from the archive.
	 *
	 * @return bool
	 */
	protected function archive_has_theme_config( $entries ) {

		foreach ( $entries as $entry ) {
			if ( 'config.json' === basename( $entry ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Funtion to upload the menu theme zip file.
	 *
	 * @since 4.0.0
	 * @since 4.0.4 Added nonce and user capabilities check.
	 * @since 4.7.3 Validates the archive contents and requires `install_plugins`.
	 *
	 * @since array $status
	 */
	public function rmp_upload_theme() {

		// Check nonce to verify the authenticate upload file.
		check_ajax_referer( 'rmp_nonce', 'rmp_theme_upload_nonce' );

		status_header( 200 );

		$uploaded = $this->process_theme_upload();

		if ( is_wp_error( $uploaded ) ) {
			wp_send_json_error(
				array( 'message' => $uploaded->get_error_message() )
			);
		}

		wp_send_json_success(
			array( 'message' => esc_html__( 'Theme Imported Successfully.', 'responsive-menu' ) )
		);
	}

	/**
	 * Returns the theme list with meta info.
	 *
	 * @since 4.0.0
	 * @since 4.1.0 Added bundle themes.
	 *
	 * @return array $theme
	 */
	public function get_themes_from_uploads() {

		// Get theme from uploads directory.
		$upload_dir     = wp_upload_dir();
		$theme_url      = $upload_dir['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = $upload_dir['basedir'] . '/rmp-menu/themes';
		$theme_dirs     = glob( $theme_dir_path . '/*', GLOB_ONLYDIR );

		// Get themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*', GLOB_ONLYDIR ), $theme_dirs );
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		$themes = array();
		foreach ( $theme_dirs as $theme_dir ) {
			$config_file       = $theme_dir . '/config.json';
			$theme_preview_url = $theme_url . '/' . basename( $theme_dir ) . '/preview.png';

			// Theme preview image from plugin bundle.
			if ( ! strpos( $theme_dir, 'uploads' ) ) {
				$theme_preview_url = plugin_dir_url( $config_file ) . '/preview.png';
			}

			if ( file_exists( $config_file ) ) {
				$config = json_decode( $wp_filesystem->get_contents( $config_file ), true );
				$themes[ basename( $theme_dir ) ]['theme_name']        = $config['name'];
				$themes[ basename( $theme_dir ) ]['theme_version']     = $config['version'];
				$themes[ basename( $theme_dir ) ]['demo_link']         = ! empty( $config['demo_link'] ) ? $config['demo_link'] : '';
				$themes[ basename( $theme_dir ) ]['theme_preview_url'] = $theme_preview_url;
			}
		}

		return $themes;
	}

	/**
	 * Returns the theme dir list to supress the theme which are in downloaded list.
	 *
	 * @since 4.0.2
	 *
	 * @return array $theme_dirs
	 */
	public function get_uploaded_theme_dir() {
		$themes = $this->get_themes_from_uploads();

		if ( empty( $themes ) ) {
			return;
		}

		$theme_dirs = array();

		foreach ( $themes as $theme => $theme_meta ) {

			// Replace the these older themes dir name as slug.
			if ( 'electric blue theme' === $theme ) {
				$theme_dirs[] = 'electric-blue-free';
			} elseif ( 'full-width-theme' === $theme ) {
				$theme_dirs[] = 'full-width-free';
			} elseif ( 'simple-red-theme' === $theme ) {
				$theme_dirs[] = 'simple-red-free';
			} else {
				$theme_dirs[] = strtolower( $theme );
			}
		}

		return $theme_dirs;
	}

	public function rmp_save_theme() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$theme_name = isset( $_POST['theme_name'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_name'] ) ) : '';
		if ( empty( $theme_name ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Theme Name Missing', 'responsive-menu' ) ) );
		}

		$menu_id = isset( $_POST['menu_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_id'] ) ) : '';
		if ( empty( $menu_id ) ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Menu ID missing !', 'responsive-menu' ) )
			);
		}

		if ( ! current_user_can( 'edit_post', $menu_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You can not edit menu !', 'responsive-menu' ) ) );
		}

		$options   = array();
		$form_data = isset( $_POST['form'] ) ? rm_sanitize_rec_array( wp_unslash( $_POST['form'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		wp_parse_str( $form_data, $options );
		$options = $options['menu'];

		$rmp_themes = get_option( self::$theme_option );
		if ( empty( $rmp_themes ) || ! is_array( $rmp_themes ) ) {
			$rmp_theme = array();
		}

		$rmp_themes[ $theme_name ] = $options;

		update_option( self::$theme_option, $rmp_themes );

		$data = $this->saved_theme_list();

		wp_send_json_success(
			array(
				'themes'  => $data,
				'message' => $theme_name . ' is saved',
			)
		);

		exit();
	}


	public function saved_theme_list() {
		$rmp_themes = get_option( self::$theme_option );

		if ( empty( $rmp_themes ) ) {
			return;
		}

		$theme_list = array();
		foreach ( $rmp_themes as $theme_name => $options ) {
			$theme_list[] = $theme_name;
		}

		return $theme_list;
	}

	public function get_saved_theme_options( $theme_name ) {
		$rmp_themes = get_option( self::$theme_option );

		if ( empty( $rmp_themes ) ) {
			return;
		}

		foreach ( $rmp_themes as $theme_key => $options ) {
			if ( $theme_name === $theme_key ) {
				return $options;
			}
		}

		return array();
	}

	/**
	 * Function to return the list of saved template themes.
	 *
	 * @since 4.0.0
	 * @since Updated the funtion to add the condition
	 *
	 * @return HTML|string
	 */
	public function rmp_saves_theme_template_list( $in_customizer = false ) {
		$rmp_themes = $this->saved_theme_list();

		// Check the list is empty or not.
		if ( empty( $rmp_themes ) ) {
			?><div class="rmp-theme-page-empty">
					<span class="rmp-menu-library-blank-icon  dashicons dashicons-welcome-widgets-menus"></span>
					<h3 class="rmp-menu-library-title"> <?php esc_html_e( 'You have no template !', 'responsive-menu' ); ?> </h3>
				</div>
				<?php
				return;
		}

		// Prepare the saved theme list and wrapped into html.
		foreach ( $rmp_themes as $theme_name ) {
			?>
			<div class="rmp-theme-title ">
				<span class="item-title"> <?php echo esc_attr( $theme_name ); ?> </span>
				<span class="item-controls">
					<?php
					if ( $in_customizer ) {
						?>
						<a theme-name="<?php echo esc_attr( $theme_name ); ?>" class="rmp-theme-apply" theme-type="template"><?php esc_html_e( 'Apply', 'responsive-menu' ); ?></a>
						<?php
					} else {
						?>
						<input type="radio" class="rmp-theme-option" name="menu_theme" id="<?php echo esc_attr( $theme_name ); ?>" value="<?php echo esc_attr( $theme_name ); ?>" theme-type="template"/>
							<label theme-name="<?php echo esc_attr( $theme_name ); ?>" class="rmp-theme-use" for="<?php echo esc_attr( $theme_name ); ?>"><?php esc_html_e( 'Use', 'responsive-menu' ); ?></label>
						<?php } ?>
				</span>
			</div>
			<?php
		}
	}

	/**
	 * Design the theme list which are from stored.
	 *
	 * @since 4.0.0
	 * @return HTML|string $html
	 */
	public function get_themes_from_theme_store( $in_customizer = false ) {
		$themes          = $this->get_themes_by_api();
		$uploaded_themes = $this->get_uploaded_theme_dir();

		if ( empty( $uploaded_themes ) || ! is_array( $uploaded_themes ) ) {
			$uploaded_themes = array();
		}

		foreach ( $themes as $theme ) {

			// Avoid the themes which are already uploaded.
			if ( in_array( strtolower( $theme['slug'] ), $uploaded_themes, true ) ) {
				continue;
			}

			if ( $in_customizer ) {
				$buy_link = add_query_arg(
					array(
						'utm_source' => 'plugin',
						'utm_medium' => 'change_theme_wizard',
					),
					$theme['buy_link']
				);
			} else {
				$buy_link = add_query_arg(
					array(
						'utm_source' => 'plugin',
						'utm_medium' => 'new_menu_wizard',
					),
					$theme['buy_link']
				);
			}
			?>
			<li class="rmp_theme_grid_item">
				<div class="rmp-item-card">
					<figure class="rmp-item-card_image">
						<img src="<?php echo esc_url( $theme['preview_url'] ); ?>" alt="<?php echo esc_attr( $theme['name'] ); ?>" loading="lazy"/>
					</figure>
					<div class="rmp-item-card-backside">
						<div class="rmp-item-card_contents">
							<h4> <?php echo esc_html( $theme['name'] ); ?> </h4>
						</div>
						<div class="rmp-item-card_action">
							<?php
							if ( ! empty( $theme['demo_link'] ) ) {
								if ( $in_customizer ) {
									$link = add_query_arg(
										array(
											'utm_source' => 'plugin',
											'utm_medium' => 'change_theme_wizard',
										),
										$theme['demo_link']
									);
								} else {
									$link = add_query_arg(
										array(
											'utm_source' => 'plugin',
											'utm_medium' => 'new_menu_wizard',
										),
										$theme['demo_link']
									);
								}
								?>
									<a href="<?php echo esc_url( $link ); ?>" alt="<?php echo esc_attr( $theme['name'] ); ?>" target="_blank" rel="noopener" class="button"><?php esc_html_e( 'View Demo', 'responsive-menu' ); ?></a>
									<?php
							}
							?>
							<a href="<?php echo esc_url( $buy_link ); ?>" target="_blank" rel="noopener" class="button btn-blue">
								<?php
								if ( 0 === intval( $theme['price'] ) ) {
									esc_html_e( 'Download', 'responsive-menu' );
								} else {
									esc_html_e( 'Purchase', 'responsive-menu' );
								}
								?>
							</a>
						</div>
					</div>
				</div>
			</li>
			<?php
		}

		if ( empty( $themes ) ) {
			?>
			<div class="rmp-theme-page-empty">
					<span class="rmp-menu-library-blank-icon fas fa-file-download"></span>
					<h3 class="rmp-menu-library-title"> <?php esc_html_e( 'No theme available !', 'responsive-menu' ); ?> </h3>
				</div>
				<?php
		}
	}

	public function all_theme_combine_list() {
		$all_themes = array();

		// Local saved themes.
		$themes = $this->saved_theme_list();

		if ( ! empty( $themes ) && is_array( $themes ) ) {
			foreach ( $themes as $theme ) {
				$all_themes[] = array(
					'name' => $theme,
					'type' => 'Template',
				);
			}
		}

		// Uploaded themes.
		$themes = $this->get_themes_from_uploads();
		if ( ! empty( $themes ) && is_array( $themes ) ) {
			foreach ( $themes as $theme ) {
				$all_themes[] = array(
					'name'        => $theme['theme_name'],
					'type'        => 'Downloaded',
					'preview_url' => $theme['theme_preview_url'],
				);
			}
		}

		return $all_themes;
	}

	/**
	 * Returns the thumbnail of theme.
	 *
	 * @since 4.0.0
	 *
	 * @return string|url|null
	 */
	public function get_theme_preview_url( $theme_name ) {

		// Get theme from uploads directory.
		$upload_dir     = wp_upload_dir();
		$theme_url      = $upload_dir['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = $upload_dir['basedir'] . '/rmp-menu/themes';
		$theme_dirs     = glob( $theme_dir_path . '/*', GLOB_ONLYDIR );

		// Get themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*', GLOB_ONLYDIR ), $theme_dirs );
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		foreach ( $theme_dirs as $theme_dir ) {
			$config_file       = $theme_dir . '/config.json';
			$theme_preview_url = $theme_url . '/' . basename( $theme_dir ) . '/preview.png';

			// Theme preview image from plugin bundle.
			if ( ! strpos( $theme_dir, 'uploads' ) ) {
				$theme_preview_url = plugin_dir_url( $config_file ) . '/preview.png';
			}

			if ( file_exists( $config_file ) ) {
				$config = json_decode( $wp_filesystem->get_contents( $config_file ), true );
				if ( $config['name'] == $theme_name ) {
					return $theme_preview_url;
				}
			}
		}

		return;
	}

	/**
	 * Function to return the theme thumbnail element.
	 *
	 * @since 4.0.0
	 *
	 * @return strinh|HTML|null
	 */
	public function get_theme_thumbnail( $theme_name, $theme_type ) {

		// If theme is template
		if ( 'template' === $theme_type ) {
			?>
			<img src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/no-preview.jpeg' ); ?>" class="theme-thumbnail" alt="<?php echo esc_attr( $theme_type ); ?>" >
			<?php
		}

		// If theme is default.
		if ( 'default' === $theme_type ) {
			?>
			<img src="<?php echo esc_url( esc_url( $this->theme_preview_img ) ); ?>" class="theme-thumbnail" alt="<?php echo esc_attr( $theme_type ); ?>" >
			<?php
		}

		$theme_preview_url = $this->get_theme_preview_url( $theme_name );
		if ( empty( $theme_preview_url ) ) {
			return;
		}
		?>
		<img src="<?php echo esc_url( $theme_preview_url ); ?>" class="theme-thumbnail"  alt="<?php echo esc_attr( $theme_type ); ?>" >
		<?php
	}

	/**
	 * Returns the theme index file path.
	 *
	 * The index is read from a theme's own config.json, which for an uploaded
	 * theme is attacker-supplied, and the caller require_once's whatever comes
	 * back. It is therefore reduced to a file name inside the theme directory.
	 *
	 * @since 4.1.0
	 * @since 4.7.3 Constrained the configured index to a PHP file in the theme directory.
	 *
	 * @return string;
	 */
	public function get_theme_index_file( $theme_name ) {

		// Get theme from uploads directory.
		$theme_url      = wp_upload_dir()['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
		$theme_dirs     = glob( $theme_dir_path . '/*', GLOB_ONLYDIR );

		// Get themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*', GLOB_ONLYDIR ), $theme_dirs );

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		foreach ( $theme_dirs as $theme_dir ) {

			$config_file = $theme_dir . '/config.json';

			if ( file_exists( $config_file ) ) {
				$config = json_decode( $wp_filesystem->get_contents( $config_file ), true );

				if ( empty( $config['name'] ) || empty( $config['index'] ) || $config['name'] !== $theme_name ) {
					continue;
				}

				// Never let the manifest point outside its own directory.
				$index_file = basename( $config['index'] );

				if ( 'php' !== strtolower( pathinfo( $index_file, PATHINFO_EXTENSION ) ) ) {
					continue;
				}

				return $theme_dir . '/' . $index_file;
			}
		}

		return;
	}

	/**
	 * Returns all uploaded theme list.
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */
	public function get_menu_active_themes() {
		$active_themes = array();
		$themes        = $this->get_themes_from_uploads();
		foreach ( $themes as $key => $theme ) {
			if ( empty( $theme['theme_name'] ) ) {
				continue;
			}

			$active_themes[ $key ] = $theme['theme_name'];
		}

		return $active_themes;
	}

	/**
	 * Check theme is active or not.
	 *
	 * @since 4.1.0
	 *
	 * @return bool
	 */
	public function is_active_theme( $theme_name, $theme_type ) {
		if ( empty( $theme_name ) || empty( $theme_type ) ) {
			return false;
		}

		$option_manager = Option_Manager::get_instance();
		$menu_ids       = get_all_rmp_menu_ids();

		foreach ( $menu_ids as $menu_id ) {
			$options = $option_manager->get_options( $menu_id );

			if ( empty( $options['menu_theme'] ) || empty( $options['theme_type'] ) ) {
				continue;
			}

			if ( $options['menu_theme'] === $theme_name && $options['theme_type'] === $theme_type ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Function to returns the available theme list.
	 *
	 * @since 4.1.0
	 * @return HTML|string $html
	 */
	public function get_available_themes( $in_customizer = false ) {
		?>
		<ul class="rmp_theme_grids">
		<?php

		if ( ! $in_customizer ) {
			?>
			<li class="rmp_theme_grid_item">
					<input type="radio" checked id="default" class="rmp-theme-option" name="menu_theme" value="" theme-type="default"/>
					<label class="rmp-item-card default-item" for="default">
						<figure class="rmp-item-card_image">
							<img src="<?php echo esc_url( esc_url( $this->theme_preview_img ) ); ?>" alt="<?php esc_html_e( 'Default Theme', 'responsive-menu' ); ?>" loading="lazy"/>
						</figure>
						<div class="rmp-item-card-backside">
							<div class="rmp-item-card_contents">
								<h4> <?php esc_html_e( 'Default Theme', 'responsive-menu' ); ?> </h4>
							</div>
							<div class="rmp-item-card_action">
								<a href="https://demo.responsive.menu/themes/default-theme/?utm_source=plugin&utm_medium=new_menu_wizard" alt="<?php esc_html_e( 'Default Theme', 'responsive-menu' ); ?>" target="_blank" rel="noopener" class="button"><?php esc_html_e( 'View Demo', 'responsive-menu' ); ?></a>
							</div>
						</div>
					</label>
				</li>
				<?php
		}

		$downloaded_themes = $this->get_themes_from_uploads();
		foreach ( $downloaded_themes as $theme ) {
			$id = 'rmp-theme-' . preg_replace( '/\s+/', '', $theme['theme_name'] );
			?>
				<li class="rmp_theme_grid_item">
					<?php
					if ( ! $in_customizer ) {
						?>
						<input type="radio" id="<?php echo esc_attr( $id ); ?>" theme-type="downloaded" class="rmp-theme-option" name="menu_theme" value="<?php echo esc_html( $theme['theme_name'] ); ?>"/>
						<?php
					}
					?>
					<label class="rmp-item-card" for="<?php echo esc_attr( $id ); ?>">
						<figure class="rmp-item-card_image">
							<img src="<?php echo esc_url( $theme['theme_preview_url'] ); ?>" alt="<?php echo esc_html( $theme['theme_name'] ); ?>" loading="lazy"/>
						</figure>
						<div class="rmp-item-card-backside">
							<div class="rmp-item-card_contents">
								<h4> <?php echo esc_html( $theme['theme_name'] ); ?> </h4>
							</div>
							<div class="rmp-item-card_action">
						<?php
						if ( ! empty( $theme['demo_link'] ) ) {
							if ( $in_customizer ) {
								$link = add_query_arg(
									array(
										'utm_source' => 'plugin',
										'utm_medium' => 'change_theme_wizard',
									),
									$theme['demo_link']
								);
							} else {
								$link = add_query_arg(
									array(
										'utm_source' => 'plugin',
										'utm_medium' => 'new_menu_wizard',
									),
									$theme['demo_link']
								);
							}
							?>
									<a href="<?php echo esc_url( $link ); ?>" alt="<?php echo esc_attr( $theme['theme_name'] ); ?>" target="_blank" rel="noopener" class="button"><?php esc_html_e( 'View Demo', 'responsive-menu' ); ?></a>
									<?php
						}
						if ( $in_customizer ) {
							?>
				<button class="button btn-blue rmp-theme-apply" theme-name="<?php echo esc_html( $theme['theme_name'] ); ?>" theme-type="downloaded" ><?php esc_html_e( 'Apply', 'responsive-menu' ); ?></button>
							<?php
						}
						?>
							</div>
						</div>
					</label>
				</li>
				<?php
		}
		?>
		</ul>
		<?php
	}

	/**
	 * Function to returns the available theme list.
	 *
	 * @since 4.1.0
	 * @return HTML|string $html
	 */
	public function get_available_themes_return( $in_customizer = false ) {
		$html = '<ul class="rmp_theme_grids">';

		if ( ! $in_customizer ) {
			$html .= sprintf(
				'<li class="rmp_theme_grid_item">
					<input type="radio" checked id="default" class="rmp-theme-option" name="menu_theme" value="" theme-type="default"/>
					<label class="rmp-item-card default-item" for="default">
						<figure class="rmp-item-card_image">
							<img src="%1$s" alt="%2$s" loading="lazy"/>
						</figure>
						<div class="rmp-item-card-backside">
							<div class="rmp-item-card_contents">
								<h4> %2$s </h4>
							</div>
							<div class="rmp-item-card_action">
								<a href="https://demo.responsive.menu/themes/default-theme/?utm_source=plugin&utm_medium=new_menu_wizard" alt="%2$s" target="_blank" rel="noopener" class="button">%3$s</a>
							</div>
						</div>
					</label>
				</li>',
				esc_url( $this->theme_preview_img ),
				esc_html__( 'Default Theme', 'responsive-menu' ),
				esc_html__( 'View Demo', 'responsive-menu' )
			);
		}

		$downloaded_themes = $this->get_themes_from_uploads();
		foreach ( $downloaded_themes as $theme ) {
			$id = 'rmp-theme-' . preg_replace( '/\s+/', '', $theme['theme_name'] );

			$demo_link = '';
			if ( ! empty( $theme['demo_link'] ) ) {
				if ( $in_customizer ) {
					$link = add_query_arg(
						array(
							'utm_source' => 'plugin',
							'utm_medium' => 'change_theme_wizard',
						),
						$theme['demo_link']
					);
				} else {
					$link = add_query_arg(
						array(
							'utm_source' => 'plugin',
							'utm_medium' => 'new_menu_wizard',
						),
						$theme['demo_link']
					);
				}

				$demo_link = sprintf(
					'<a href="%s" alt="%s" target="_blank" rel="noopener" class="button">%s</a>',
					esc_url( $link ),
					esc_attr( $theme['theme_name'] ),
					esc_html__( 'View Demo', 'responsive-menu' )
				);
			}

			$select_option = $apply_button = '';
			if ( $in_customizer ) {
				$apply_button = sprintf(
					'<button class="button btn-blue rmp-theme-apply" theme-name="%s" theme-type="downloaded" >%s</button>',
					esc_html( $theme['theme_name'] ),
					esc_html__( 'Apply', 'responsive-menu' )
				);
			} else {
				$select_option = sprintf(
					'<input type="radio" id="%1$s" theme-type="downloaded" class="rmp-theme-option" name="menu_theme" value="%2$s"/>',
					esc_attr( $id ),
					esc_html( $theme['theme_name'] )
				);
			}

			$html .= sprintf(
				'
				<li class="rmp_theme_grid_item">
					%5$s
					<label class="rmp-item-card" for="%1$s">
						<figure class="rmp-item-card_image">
							<img src="%3$s" alt="%2$s" loading="lazy"/>
						</figure>
						<div class="rmp-item-card-backside">
							<div class="rmp-item-card_contents">
								<h4> %2$s </h4>
							</div>
							<div class="rmp-item-card_action">
								%4$s
								%6$s
							</div>
						</div>
					</label>
				</li>',
				esc_attr( $id ),
				esc_html( $theme['theme_name'] ),
				esc_url( $theme['theme_preview_url'] ),
				$demo_link,
				$select_option,
				$apply_button
			);
		}

		$html .= '</ul>';

		return $html;
	}

	/**
	 * Function to upload the theme by ajax.
	 *
	 * @since 4.1.0
	 * @since 4.7.3 Validates the archive contents and requires `install_plugins`.
	 *
	 * @return json
	 */
	public function rmp_theme_upload_from_wizard() {

		// Check nonce to verify the authenticate upload file.
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		// Upload the file in upload directory.
		status_header( 200 );

		$uploaded = $this->process_theme_upload();

		if ( is_wp_error( $uploaded ) ) {
			wp_send_json_error(
				array( 'message' => $uploaded->get_error_message() )
			);
		}

		// Return the response
		wp_send_json_success(
			array(
				'message' => esc_html__( 'Theme is uploaded successfully', 'responsive-menu' ),
				'html'    => $this->get_available_themes_return( $this->is_customizer() ),
			)
		);
	}

	/**
	 * Function to update the theme api cached data.
	 *
	 * @since 4.1.0
	 *
	 * @return json
	 */
	public function update_theme_api_cache() {

		// Check nonce to verify the authenticate upload file.
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		return wp_send_json_success(
			array(
				'message' => esc_html__( 'Cache data updated !', 'responsive-menu' ),
				'html'    => $this->get_themes_from_theme_store( $this->is_customizer() ),
			)
		);
	}

	/**
	 * Function to check the request origin either from customizer or create menu page.
	 *
	 * @since 4.1.3
	 *
	 * @return bool
	 */
	public function is_customizer() {
		$is_customizer_request = false;
		if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			wp_parse_str( wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) )['query'], $params );
			if ( ! empty( $params['action'] ) && ! empty( $params['editor'] ) ) {
				$is_customizer_request = true;
			}
		}

		return $is_customizer_request;
	}
}
