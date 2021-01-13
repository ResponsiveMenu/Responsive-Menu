<?php
/**
 * This file contain the Theme_Manager class and it's functionalities for menu.
 *
 * @since 4.0.0
 * @author  Expresstech System
 * 
 * @package responsive-menu-pro
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;
use responsive_menu_pro\frontend\RMP_Menu;

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
	 * @var string $theme_option
	 */
	protected static $theme_option = 'rmp_themes';

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
		add_action('wp_ajax_rmp_save_theme', array( $this, 'rmp_save_theme' ) );
		add_action('admin_post_rmp_upload_theme_file', array( $this, 'rmp_upload_theme' ) );
		add_action('wp_ajax_rmp_theme_delete', array( $this, 'rmp_theme_delete' ) );
		add_action('wp_ajax_rmp_theme_apply', array( $this, 'rmp_theme_apply' ) );
		add_action('wp_ajax_rmp_theme_download', array( $this, 'rmp_theme_download' ) );
	}

	/**
	 * Function to get the list of pro theme from store.
	 * 
	 * @since 4.0.0 Added this function to call the menu theme API
	 * @since 4.0.3 Cached the API response
	 *
     * @return array $pro_themes
     */
	public function get_themes_by_api() {

		// If theme list is cached then access it.
		$pro_themes = get_transient( 'rmp_theme_api_response' );
		if ( ! empty( $pro_themes ) ) {
			return $pro_themes;
		}

		$pro_themes = [];

		//These are older version theme which are not compatible with new version.
		$exclude_theme_ids = ['47704','47698','45318'];

        $endpoint_url  = 'https://responsive.menu/edd-api/v2/products/?category=theme';
		$rmp_response  = wp_remote_get( $endpoint_url, array( 'sslverify' => false ) );
		$rmp_response_body  = wp_remote_retrieve_body( $rmp_response );
		$rmp_response_body  = json_decode( $rmp_response_body, true );
		if ( ! empty( $rmp_response_body ) && is_array( $rmp_response_body ) ) {
			foreach ( $rmp_response_body['products'] as $key => $product ) {
				if ( ! in_array( $product['info']['id'], $exclude_theme_ids ) ) {
					$pro_themes[] = array(
						'name'          => $product['info']['title'],
						'slug'          => $product['info']['slug'],
						'preview_url'   => $product['info']['thumbnail'],
						'buy_link'      => $product['info']['link'],
						'price'         => $product['pricing']['amount']
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

		$theme_name = sanitize_text_field( $_POST['theme_name'] );
		if ( empty( $theme_name ) ) {
            wp_send_json_error( [ 'message' => __( 'Theme Name Missing', 'responsive-menu-pro' ) ] );
        }

		$theme_type = sanitize_text_field( $_POST['theme_type'] );
		$menu_id = sanitize_text_field( $_POST['menu_id'] );
		$menu_to_use  = sanitize_text_field( $_POST['menu_to_use'] );

		if ( 'template' === $theme_type ) {
			$theme_option = $this->get_saved_theme_options( $theme_name );
		} else {
			$theme_option = $this->get_downloaded_theme_settings( $theme_name );
		}

		$theme_option['menu_id'] = $menu_id;
		$theme_option['menu_theme'] = $theme_name;
		$theme_option['theme_type'] = $theme_type;
		$theme_option['menu_to_use'] = $menu_to_use;

		update_post_meta( $menu_id, 'rmp_menu_meta' ,$theme_option );

		/**
		 * Fires when menu theme applied and options are saved.
		 * 
		 * @since 4.0.0
		 * @param int $menu_id
		 */
		do_action('rmp_theme_apply', $menu_id );

		wp_send_json_error( [ 'message' => __( 'Theme applied', 'responsive-menu-pro' ) ] );

	}

	public function get_downloaded_theme_settings( $theme_name ) {
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		foreach( $theme_dirs as $theme_dir ) {			
			$config_file =  $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				if ( $config['name'] == $theme_name ) {
					$options = json_decode( file_get_contents( $theme_dir . '/options.json' ), true);
					return $options;
				}
			}
		}

		return [];
	}

	public function rmp_theme_delete() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$theme_name = sanitize_text_field( $_POST['theme_name'] );
		if ( empty( $theme_name ) ) {
            wp_send_json_error( [ 'message' => __( 'Theme Name Missing', 'responsive-menu-pro' ) ] );
        }

        $theme_type = sanitize_text_field( $_POST['theme_type'] );
		
		if ( 'template' === $theme_type ) {
			$this->delete_template( $theme_name );
		} else {
			$this->delete_theme_folder( $theme_name );
		}

		wp_send_json_success( [ 'message' => __( 'Theme deleted', 'responsive-menu-pro' ) ] );

	}

	public function rmp_theme_download() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$theme_name = sanitize_text_field( $_POST['theme_name'] );
		if ( empty( $theme_name ) ) {
            wp_send_json_error( [ 'message' => __( 'Theme Name Missing', 'responsive-menu-pro' ) ] );
        }

        $theme_type = sanitize_text_field( $_POST['theme_type'] );

		$theme_option = [];
		if ( 'template' === $theme_type ) {
			$theme_option = $this->get_saved_theme_options( $theme_name );
		} else {
			$theme = $this->download_zip( $theme_name );
		}

		$theme_options_json = json_encode( $theme_option );

		wp_send_json_success( $theme_options_json );
	}

	public function prepare_theme_zip_file( $theme_name, $options ) {
		$zip_file_name = $theme_name . '.zip';
		
		$zip = new ZipArchive();

		if ( $zip->open( $zip_file_name ) === TRUE ) {
			$zip->addFile( '/path/to/index.txt', 'newname.txt' );
			$zip->close();
		} else {
			echo 'failed';
		}
	}

	public function download_zip( $theme_name ) {

		$theme_dir = $this->get_theme_dir( $theme_name );

		if ( empty( $theme_dir ) ) {
			return;
		}

		$theme_root = wp_upload_dir()['basedir'] . '/rmp-menu/themes';

		// Filename for a zip package
		$file_name = strtolower ( preg_replace('/\s+/', '-', $theme_name ) ) . '.zip';
		$zip_file =  $theme_root.'/'.$file_name;

		$zip = new \ZipArchive();
		if ( $zip->open( $zip_file , \ZipArchive::CREATE | \ZipArchive::OVERWRITE ) ) {

			foreach ( glob( $theme_dir . "/*" ) as $file) {
				$zip->addFile($file);
			}

			$zip->close();
		}

		if ( file_exists( $zip_file ) ) {
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="'.basename( $zip_file ).'"');
			header('Content-Length: ' . filesize($zip_file));
		}
	}

	public function get_theme_dir( $theme_name ) {

		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		foreach( $theme_dirs as $theme_dir ) {			
			$config_file =  $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				if ( $config['name'] == $theme_name ) {
					return $theme_dir;
				}
			}
		}
		return false;
	}

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

	public function delete_files( $dir ) { 
		foreach( glob($dir . '/*') as $file) { 
		  if( is_dir($file)) delete_files($file); else unlink($file); 
		}
		rmdir($dir); 
	}

	public function delete_template( $theme_name ) {
		$rmp_themes = get_option( self::$theme_option );

		if ( empty( $rmp_themes ) ) {
			return false;
		}

		foreach( $rmp_themes as $theme_key => $options ) {
			if ( $theme_name == $theme_key ) {
				unset( $rmp_themes[$theme_key] );
				update_option( self::$theme_option , $rmp_themes );
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
	 *
	 * @since array $status
	 */
	public function rmp_upload_theme() {

		// Check nonce to verify the authenticate upload file.
		check_ajax_referer( 'rmp_nonce', 'rmp_theme_upload_nonce' );

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		status_header(200);

		$theme = $_FILES['file']['tmp_name'];
		WP_Filesystem();
		$upload_dir = wp_upload_dir()['basedir'] . '/rmp-menu/themes/';
		$unzip_file = unzip_file( $theme , $upload_dir );

		if ( is_wp_error( $unzip_file ) ) {
			$status = ['danger' => $unzip_file->get_error_message() ];
		} else {
			$status = [ 'success' => 'Theme Imported Successfully.'];
		}

		return $status;
	}

	/**
	 * Returns the theme list with meta info.
	 * 
	 * @since 4.0.0
	 *
	 * @return array $theme
	 */
	public function get_themes_from_uploads() {

		$theme_url = wp_upload_dir()['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

        $themes = [];

		foreach( $theme_dirs as $theme_dir ) {			
			$config_file =  $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				$themes[basename($theme_dir)]['theme_name']         = $config['name'];
				$themes[basename($theme_dir)]['theme_version']      = $config['version'];
				$themes[basename($theme_dir)]['status']             = ! empty( $config['is_paid'] ) ? 'Pro' : 'Free';
				$themes[basename($theme_dir)]['theme_preview_url'] = $theme_url .'/'. basename($theme_dir) . '/preview.png';
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

		$theme_dirs = [];
		foreach( $themes as $theme => $theme_meta ) {

			//  Replace the these older themes dir name as slug.
			if ( 'electric blue theme' == $theme ) {
				$theme_dirs[] = 'electric-blue-free';
			} else if( 'full-width-theme' == $theme ) {
				$theme_dirs[] = 'full-width-free';
			} else if( 'simple-red-theme' == $theme ) {
				$theme_dirs[] = 'simple-red-free';
			} else {
				$theme_dirs[] = strtolower( $theme );
			}
		}

		return $theme_dirs;
	}

    public function rmp_save_theme() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$theme_name = sanitize_text_field( $_POST['theme_name'] );
		if ( empty( $theme_name ) ) {
            wp_send_json_error( [ 'message' => __( 'Theme Name Missing', 'responsive-menu-pro' ) ] );
        }

        $menu_id = sanitize_text_field( $_POST['menu_id'] );
		if ( empty( $menu_id ) ) {
            wp_send_json_error( 
                [ 'message' => __( 'Menu ID missing !', 'responsive-menu-pro' )]);
		}

		$options = array();
		parse_str( $_POST['form'], $options );
		$options = $options['menu']; 

		foreach( $options['mega_menu'] as $key ) {
			$options['mega_menu_item'][$key] = get_post_meta( $menu_id, '_rmp_mega_menu_' . $key );
		}

		unset( $options['mega_menu'] );

		$rmp_themes = get_option( self::$theme_option );
		if ( empty( $rmp_themes ) || ! is_array( $rmp_themes ) ) {
			$rmp_theme = [];
		}

		$rmp_themes[$theme_name] =  $options;

		update_option( self::$theme_option , $rmp_themes );

		$data = $this->saved_theme_list();


	    wp_send_json_success( ['themes' => $data , 'message' => $theme_name . ' is saved' ] );

		exit();
	
    
	}


	public function saved_theme_list() {
		
		$rmp_themes = get_option( self::$theme_option );

		if ( empty( $rmp_themes ) ) {
			return;
		}

		$theme_list = [];
		foreach( $rmp_themes as $theme_name => $options ) {
			$theme_list[] = $theme_name;
		}

		return $theme_list;
	}

	public function get_saved_theme_options( $theme_name ) {


		$rmp_themes = get_option( self::$theme_option );

		if ( empty( $rmp_themes ) ) {
			return;
		}

		foreach( $rmp_themes as $theme_key => $options ) {
			if( $theme_name == $theme_key ) {
				return $options;
			}
		}

		return [];
	}

	public function rmp_saves_theme_list_html() {

		$rmp_themes = $this->saved_theme_list();

		if ( empty( $rmp_themes ) ) {
			return;
		}

		$html = '';
		foreach( $rmp_themes as $theme_name ) {
			$html .= sprintf(
				'<div class="rmp-theme-title ">
					<span class="item-title"> %1$s </span>
					<span class="item-controls">
						<a theme-name="%1$s" class="rmp-theme-apply" theme-type="template">Apply</a>
					</span>
				</div>',
				esc_attr( $theme_name )
			);
		}

		return $html;
	}

	public function rmp_saved_theme_list_for_new_menu() {

		$rmp_themes = $this->saved_theme_list();

		if ( empty( $rmp_themes ) ) {
			return;
		}

		$html = '';
		foreach( $rmp_themes as $theme_name ) {
			$html .= sprintf('
				<div class="rmp-theme-item">
					<input type="radio" class="rmp-theme-option" name="menu_theme" id="%1$s" value="%1$s" theme-type="template"/>
					<label class="rmp-theme-title" for="%1$s"> %1$s </label>
				</div>',
				esc_attr( $theme_name )
			);
		}

		return $html;
	}

	/**
	 * Design the theme list which are from stored.
	 *
	 * @since 4.0.0
	 * 
	 * @return HTML|string $html
	 */
	public function get_themes_from_theme_store( $in_wizard = false ) {

		$themes = $this->get_themes_by_api();

		if ( empty( $themes ) && $in_wizard ) {
			return __( '<h2>Coming soon..</h2>', 'responsive-menu-pro' );
		}

		$uploaded_themes = $this->get_uploaded_theme_dir();
		if ( empty( $uploaded_themes ) || ! is_array( $uploaded_themes ) ) {
			$uploaded_themes = [];
		}

		$html = '';
		foreach( $themes as $theme ) {

			// Avoid the themes which are already uploaded.
			if ( in_array( strtolower( $theme['slug'] ), $uploaded_themes ) ) {
				continue;
			}

			$action_label = __( 'BUY NOW','responsive-menu-pro' );
			$status  = __( 'Pro','responsive-menu-pro' );
			if ( 0 == $theme['price'] ) {
				$status  = __( 'Free','responsive-menu-pro' );
				$action_label = __( 'DOWNLOAD','responsive-menu-pro' );
			}

			$html .= sprintf(
				'<li class="rmp_theme_grid_item">
					<div class="rmp-item-card">
						<figure class="rmp-item-card_image">
							<img src="%1$s" alt="%2$s" loading="lazy"/>
							<figcaption class="rmp-item-card_label %3$s">
								<span class="dashicons dashicons-star-filled "></span> %3$s
							</figcaption>
						</figure>
						<div class="rmp-item-card_contents">
						<h4> %2$s </h4>
					</div>
					<div class="rmp-item-card_action">
						<a href="%4$s" target="_blank" class="button %5$s"> %5$s </a>
					</div>
					</div>
				</li>',
				esc_url( $theme['preview_url']),
				esc_attr( $theme['name'] ),
				$status,
				esc_url( $theme['buy_link'] ),
				$action_label
			);	
		}

		return $html;
	}

	public function all_theme_combine_list() {

		$all_themes = [];

		//Local saved themes.
		$themes = $this->saved_theme_list();

		if ( ! empty( $themes ) && is_array( $themes ) ) {
			foreach( $themes as $theme ) {
				$all_themes[] = [ 'name' => $theme , 'type' => 'Template' ];		
			}
		}

		//Uploaded themes.
		$themes = $this->get_themes_from_uploads();
		if ( ! empty( $themes ) && is_array( $themes ) ) {
			foreach( $themes as $theme ) {
				$all_themes[] = [ 'name' => $theme['theme_name'] , 'type' => 'Downloaded', 'preview_url' => $theme['theme_preview_url'] ];		
			}
		}

		return $all_themes;
	}

	public function get_theme_preview_url( $theme_name ) {

		$theme_url = wp_upload_dir()['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		foreach( $theme_dirs as $theme_dir ) {			
			$config_file =  $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				if ( $config['name'] == $theme_name ) {
					return $theme_url .'/'. basename($theme_dir) . '/preview.png';
				}
			}
		}
		return '';
	}

	public function get_theme_thumbnail( $theme_name, $theme_type ) {
		if( 'Default' == $theme_name || $theme_type != 'downloaded' ) {
			return sprintf( '<img src="%s" class="theme-thumbnail">',
				esc_url( RMP_PLUGIN_URL_V4 .'/assets/images/no-preview.jpeg' )
			);
		} else {
			$theme_url = $this->get_theme_preview_url( $theme_name );
			return sprintf( '<img src="%s" class="theme-thumbnail">',
				esc_url( $theme_url )
			);
		}
	}

}




