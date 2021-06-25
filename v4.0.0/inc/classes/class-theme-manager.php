<?php
/**
 * This file contain the Theme_Manager class and it's functionalities for menu.
 * 
 * @version 4.0.0
 * @author  Expresstech System
 * 
 * @package responsive-menu-pro
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
		add_action( 'wp_ajax_rmp_menu_theme_upload', [ $this, 'rmp_theme_upload_from_wizard' ] );		
		add_action('wp_ajax_rmp_theme_delete', array( $this, 'rmp_theme_delete' ) );
		add_action('wp_ajax_rmp_theme_apply', array( $this, 'rmp_theme_apply' ) );
		add_action('wp_ajax_rmp_call_theme_api', array( $this, 'update_theme_api_cache' ) );
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
						'demo_link'     => ! empty( $product['info']['demo_link'] ) ? $product['info']['demo_link'] : '',
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

		$theme_type  = sanitize_text_field( $_POST['theme_type'] );
		$menu_id     = sanitize_text_field( $_POST['menu_id'] );
		$menu_to_use = sanitize_text_field( $_POST['menu_to_use'] );

		if ( 'template' === $theme_type ) {
			$theme_option = $this->get_saved_theme_options( $theme_name );
		} else {
			$theme_option = $this->get_available_theme_settings( $theme_name );
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

		wp_send_json_success( [ 'message' => __( 'Theme applied', 'responsive-menu-pro' ) ] );

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

		//Themes from uploads directory.
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		//Themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*' , GLOB_ONLYDIR ), $theme_dirs );

		$options = [];
		$min_version = '4.0.0';

		foreach( $theme_dirs as $theme_dir ) {			
			$config_file =  $theme_dir . '/config.json';
			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				if ( $config['name'] == $theme_name ) {
					$min_version = ! empty( $config['min_rm_version'] ) ? $config['min_rm_version'] : '4.0.0';
					$options = json_decode( file_get_contents( $theme_dir . '/options.json' ), true);
					break;
				}
			}
		}

		// Check menu theme minimum version compatibility.
		if ( version_compare( RMP_PLUGIN_VERSION , $min_version , '<' ) ) {
			wp_send_json_error(
				[
					'message' => sprintf(
						'%s required Responsive Menu %s version or higher. Please update the plugin with the latest version.',
						$theme_name,
						$min_version
					)
				]
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

		$theme_name = sanitize_text_field( $_POST['theme_name'] );
		if ( empty( $theme_name ) ) {
            wp_send_json_error( [ 'message' => __( 'Theme Name Missing', 'responsive-menu-pro' ) ] );
        }

        $theme_type = sanitize_text_field( $_POST['theme_type'] );

		if ( $this->is_active_theme( $theme_name, $theme_type ) ) {
			wp_send_json_error( [ 'message' => __( 'This theme is currently active. Please choose another theme and then try deleting.', 'responsive-menu-pro' ) ] );
		}

		if ( 'template' === $theme_type ) {
			$this->delete_template( $theme_name );
		} else {
			$this->delete_theme_folder( $theme_name );
		}

		wp_send_json_success( [ 'message' => __( 'Theme deleted', 'responsive-menu-pro' ) ] );

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

		//Themes from uploads directory.
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		//Themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*' , GLOB_ONLYDIR ), $theme_dirs );

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
		foreach( glob($dir . '/*') as $file) { 
		  if( is_dir($file)) delete_files($file); else unlink($file); 
		}
		rmdir($dir); 
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
	 * @since 4.1.0 Added bundle themes.
	 *
	 * @return array $theme
	 */
	public function get_themes_from_uploads() {

		//Get theme from uploads directory.
		$upload_dir = wp_upload_dir(); 
		$theme_url  = $upload_dir['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = $upload_dir['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		//Get themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*' , GLOB_ONLYDIR ), $theme_dirs );

        $themes = [];
		foreach( $theme_dirs as $theme_dir ) {

			$config_file       = $theme_dir . '/config.json';
			$theme_preview_url = $theme_url .'/'. basename( $theme_dir ) . '/preview.png';

			//Theme preview image from plugin bundle.
			if ( strpos( $theme_dir , 'uploads' ) == false ) {
				$theme_preview_url = plugin_dir_url( $config_file ) . '/preview.png';
			}

			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				$themes[basename($theme_dir)]['theme_name']         = $config['name'];
				$themes[basename($theme_dir)]['theme_version']      = $config['version'];
				$themes[basename($theme_dir)]['demo_link']          = ! empty( $config['demo_link'] ) ? $config['demo_link'] : '';
				$themes[basename($theme_dir)]['theme_preview_url']  = $theme_preview_url;
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

		//Check the list is empty or not.
		if ( empty( $rmp_themes ) ) {
			return sprintf(
				'<div class="rmp-theme-page-empty">
					<span class="rmp-menu-library-blank-icon  dashicons dashicons-welcome-widgets-menus"></span>
					<h3 class="rmp-menu-library-title"> %s </h3>
				</div>',
				__( 'You have no template !', 'responsive-menu-pro' )
			);
		}

		//Prepare the saved theme list and wrapped into html.
		$html = '';
		foreach( $rmp_themes as $theme_name ) {

			$actions = '';
			if ( $in_customizer ) {
				$actions = sprintf( '<a theme-name="%1$s" class="rmp-theme-apply" theme-type="template">%2$s</a>',
					esc_attr( $theme_name ),
					__('Apply','responsive-menu-pro')
				);

			} else {
				$actions = sprintf(
					'<input type="radio" class="rmp-theme-option" name="menu_theme" id="%1$s" value="%1$s" theme-type="template"/>
					<label theme-name="%1$s" class="rmp-theme-use" for="%1$s">%2$s</label>',
					esc_attr( $theme_name ),
					__('Use','responsive-menu-pro')
				);
			}

			$html .= sprintf(
				'<div class="rmp-theme-title ">
					<span class="item-title"> %1$s </span>
					<span class="item-controls">
						%2$s
					</span>
				</div>',
				esc_attr( $theme_name ),
				$actions
			);
		}

		return $html;
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
			$uploaded_themes = [];
		}

		$html = '';
		foreach( $themes as $theme ) {

			// Avoid the themes which are already uploaded.
			if ( in_array( strtolower( $theme['slug'] ), $uploaded_themes ) ) {
				continue;
			}

			$action_label = __( 'Purchase','responsive-menu-pro' );
			if ( 0 == $theme['price'] ) {
				$action_label = __( 'Download','responsive-menu-pro' );
			}

			$demo_link = '';
			if ( ! empty( $theme['demo_link'] ) ) {
				if ( $in_customizer ) {
					$link = add_query_arg( [
						'utm_source' => 'plugin',
						'utm_medium' => 'change_theme_wizard'
					], $theme['demo_link'] );
				} else {
					$link = add_query_arg( [
						'utm_source' => 'plugin',
						'utm_medium' => 'new_menu_wizard'
					], $theme['demo_link'] );
				}

				$demo_link = sprintf(
					'<a href="%s" alt="%s" target="_blank" class="button">%s</a>',
					esc_url( $link ),
					esc_attr( $theme['name'] ),
					__( 'View Demo','responsive-menu-pro' )
				);
			}

			if ( $in_customizer ) {
				$buy_link = add_query_arg( [
					'utm_source' => 'plugin',
					'utm_medium' => 'change_theme_wizard'
				], $theme['buy_link'] );
			} else {
				$buy_link = add_query_arg( [
					'utm_source' => 'plugin',
					'utm_medium' => 'new_menu_wizard'
				], $theme['buy_link'] );
			}

			$html .= sprintf(
				'<li class="rmp_theme_grid_item">
					<div class="rmp-item-card">
						<figure class="rmp-item-card_image">
							<img src="%1$s" alt="%2$s" loading="lazy"/>
						</figure>
						<div class="rmp-item-card-backside">
							<div class="rmp-item-card_contents">
								<h4> %2$s </h4>
							</div>
							<div class="rmp-item-card_action">
								%5$s
								<a href="%3$s" target="_blank" class="button btn-blue %4$s"> %4$s </a>
							</div>
						</div>
					</div>
				</li>',
				esc_url( $theme['preview_url']),
				esc_attr( $theme['name'] ),
				esc_url( $buy_link ),
				$action_label,
				$demo_link
			);
		}

		if ( empty( $html ) ) {
			return sprintf(
				'<div class="rmp-theme-page-empty">
					<span class="rmp-menu-library-blank-icon fas fa-file-download"></span>
					<h3 class="rmp-menu-library-title"> %s </h3>
				</div>',
				__( 'No theme available !', 'responsive-menu-pro' )
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

	/**
	 * Returns the thumbnail of theme.
	 * 
	 * @since 4.0.0
	 * 
	 * @return string|url|null
	 */
	public function get_theme_preview_url( $theme_name ) {

		//Get theme from uploads directory.
		$upload_dir = wp_upload_dir(); 
		$theme_url  = $upload_dir['baseurl'] . '/rmp-menu/themes';
		$theme_dir_path = $upload_dir['basedir'] . '/rmp-menu/themes';
        $theme_dirs = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		//Get themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*' , GLOB_ONLYDIR ), $theme_dirs );

		foreach( $theme_dirs as $theme_dir ) {

			$config_file       = $theme_dir . '/config.json';
			$theme_preview_url = $theme_url .'/'. basename( $theme_dir ) . '/preview.png';

			//Theme preview image from plugin bundle.
			if ( strpos( $theme_dir , 'uploads' ) == false ) {
				$theme_preview_url = plugin_dir_url( $config_file ) . '/preview.png';
			}

			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
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

		//If theme is template
		if ( $theme_type == 'template' ) {
			return sprintf( '<img src="%s" class="theme-thumbnail">',
				esc_url( RMP_PLUGIN_URL_V4 .'/assets/images/no-preview.jpeg' )
			);
		}

		//If theme is default.
		if( 'default' == $theme_type ) {
			return sprintf( '<img src="%s" class="theme-thumbnail">',
				esc_url( RMP_PLUGIN_URL_V4 .'/assets/images/default-theme-preview.png' )
			);
		}

		$theme_preview_url = $this->get_theme_preview_url( $theme_name );
		if ( empty( $theme_preview_url ) ) {
			return;
		}

		return sprintf(
			'<img src="%s" class="theme-thumbnail">',
			esc_url( $theme_preview_url )
		);

	}

	/**
	 * Returns the theme index file path.
	 *
	 * @since 4.1.0
	 *
	 * @return string;
	 */
	public function get_theme_index_file( $theme_name ) {

		//Get theme from uploads directory.
		$theme_dir_path = wp_upload_dir()['basedir'] . '/rmp-menu/themes';
		$theme_dirs     = glob( $theme_dir_path . '/*' , GLOB_ONLYDIR );

		//Get themes from plugin bundle.
		$theme_dirs = array_merge( glob( RMP_PLUGIN_PATH_V4 . '/themes/*' , GLOB_ONLYDIR ), $theme_dirs );

		foreach( $theme_dirs as $theme_dir ) {
			$config_file =  $theme_dir . '/config.json';

			if ( file_exists( $config_file ) ) {
				$config = json_decode( file_get_contents( $config_file ), true);
				if ( $config['name'] == $theme_name && ! empty( $config['index'] ) ) {
					return $theme_dir . '/' . $config['index'];
				}
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

		$active_themes = [];
		$themes = $this->get_themes_from_uploads();
		foreach ( $themes as $key => $theme ) {

			if ( empty(  $theme['theme_name'] ) ) {
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
			$options   = $option_manager->get_options( $menu_id );

			if ( empty( $options['menu_theme'] ) || empty(  $options['theme_type'] ) ) {
				continue;
			}

			if ( $options['menu_theme'] == $theme_name &&  $options['theme_type'] == $theme_type ) {
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
								<a href="https://demo.responsive.menu/themes/default-theme/?utm_source=plugin&utm_medium=new_menu_wizard" alt="%2$s" target="_blank" class="button">%3$s</a>
							</div>
						</div>
					</label>
				</li>',
				esc_url( RMP_PLUGIN_URL_V4 .'/assets/images/default-theme-preview.png' ),
				__( 'Default Theme', 'responsive-menu-pro'),
				__( 'View Demo','responsive-menu-pro' )
			);
		}

		$downloaded_themes = $this->get_themes_from_uploads();
		foreach( $downloaded_themes as $theme ) {
			$id = 'rmp-theme-' . preg_replace('/\s+/', '', $theme['theme_name'] );
			
			$demo_link = '';
			if ( ! empty( $theme['demo_link' ] ) ) {
				if ( $in_customizer ) {
					$link = add_query_arg( [
						'utm_source' => 'plugin',
						'utm_medium' => 'change_theme_wizard'
					], $theme['demo_link' ] );
				} else {
					$link = add_query_arg( [
						'utm_source' => 'plugin',
						'utm_medium' => 'new_menu_wizard'
					], $theme['demo_link' ] );
				}

				$demo_link = sprintf(
					'<a href="%s" alt="%s" target="_blank" class="button">%s</a>',
					esc_url( $link ),
					esc_attr( $theme['theme_name'] ),
					__( 'View Demo','responsive-menu-pro' )
				);
			}

			$select_option = $apply_button = '';
			if ( $in_customizer ) {
				$apply_button = sprintf( '<button class="button btn-blue rmp-theme-apply" theme-name="%s" theme-type="downloaded" >%s</button>',
					esc_html( $theme['theme_name'] ),
					__('Apply', 'responsive-menu-pro')
				);
			} else {
				$select_option = sprintf(
					'<input type="radio" id="%1$s" theme-type="downloaded" class="rmp-theme-option" name="menu_theme" value="%2$s"/>',
					esc_attr( $id ),
					esc_html( $theme['theme_name'] )
				);
			}

			$html .= sprintf('
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
	 * 
     * @return json
     */
	public function rmp_theme_upload_from_wizard() {

		//Check nonce to verify the authenticate upload file.
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		//Check if files are empty then return error message.
		if ( empty( $_FILES['file']['tmp_name'] ) ) {
			wp_send_json_error( 
                [ 'message' => __( 'File not found !', 'responsive-menu-pro' )]
			);
		}

		//Upload the file in upload directory.
		status_header(200);
		WP_Filesystem();
		$upload_dir = wp_upload_dir()['basedir'] . '/rmp-menu/themes/';
		$unzip_file = unzip_file( $_FILES['file']['tmp_name'] , $upload_dir );

		if ( is_wp_error( $unzip_file ) ) {
			return wp_send_json_error( 
                [ 'message' => $unzip_file->get_error_message() ]
			);
		}

		//Return the response
		return wp_send_json_success(
			[
				'message' => __( 'Theme is uploaded successfully', 'responsive-menu-pro' ),
				'html'    => $this->get_available_themes( $this->is_customizer() )
			]
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

		//Check nonce to verify the authenticate upload file.
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		return wp_send_json_success(
			[
				'message' => __( 'Cache data updated !', 'responsive-menu-pro' ),
				'html'    => $this->get_themes_from_theme_store( $this->is_customizer() )
			]
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
		if ( ! empty( $_SERVER[ 'HTTP_REFERER' ] ) ) {
			parse_str( parse_url( $_SERVER[ 'HTTP_REFERER' ] )['query'], $params );
			if ( ! empty( $params['action'] ) && ! empty( $params['editor'] ) ) {
				$is_customizer_request = true;
			}
		}

		return $is_customizer_request;
	}
}
