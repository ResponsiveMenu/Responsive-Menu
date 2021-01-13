<?php
/**
 * Admin class.
 * This is core class which is responsible for admin functionality.
 *  
 * @version 4.0.0
 * @author  Expresstech System
 * 
 * @package responsive-menu-pro
 */

namespace RMP\Features\Inc;
use RMP\Features\Inc\Traits\Singleton;
use RMP\Features\Inc\RMP_Menu;
use RMP\Features\Inc\Theme_Manager;
use RMP\Features\Inc\Option_Manager;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Admin
 *
 */
class Admin {

	use Singleton;

	/**
	 * Instance of Option Manager class.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      object.
	 */
	protected static $option_manager;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		add_action( 'wp_ajax_rmp_save_global_settings', [ $this, 'save_menu_global_settings' ] );
		add_action( 'wp_ajax_rmp_rollback_version', [ $this, 'rollback_version' ] );
		add_action( 'wp_ajax_rmp_create_new_menu', [ $this, 'create_new_menu' ] );
		add_action( 'wp_ajax_rmp_export_menu', [ $this, 'rmp_export_menu' ] );
		add_action( 'wp_ajax_rmp_import_menu', [ $this, 'rmp_import_menu' ] );
		
		add_shortcode( 'rmp_menu', [ $this, 'register_menu_shortcode' ] );
		add_action( 'init', array($this,'rmp_menu_cpt'), 0 );

		add_filter( 'post_row_actions', array($this,'rmp_menu_row_actions'), 10, 2 );
		add_filter( 'get_edit_post_link', [ $this, 'my_edit_post_link' ], 10, 2 );

		add_filter( 'manage_rmp_menu_posts_columns', array($this,'set_custom_edit_menu_columns') );
		add_action( 'manage_rmp_menu_posts_custom_column' , array($this,'add_custom_columns'), 10, 2 );
		add_action( 'admin_footer' , array($this,'add_new_menu_widget') );
		add_action( 'admin_menu', array( $this, 'rmp_register_submenu_page' ) );
		add_action( 'admin_menu', [$this, 'remove_default_add_cpt_page']);
		add_action( 'rmp_create_new_menu', array( $this , 'set_global_options' ), 10 , 0 );
	}

	/**
	 * Function to save the global settings of setting page.
	 * 
	 * @return json
	 */
	public function save_menu_global_settings() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$options = array();
		parse_str( $_POST['form'], $options );

		foreach( $options as $key => $value ) {
			$options[$key] = sanitize_text_field( $value );
		}

		update_option( 'rmp_global_setting_options', $options );
		
		/**
		 * Fires after global settings is saved.
		 * 
		 * @since 4.0.0
		 * 
		 * @param array $option List of global settings.
		 */
		do_action( 'rmp_save_global_settings', $options );

		wp_send_json_success( 'Saved' );
	}

	/**
	 * Rollback to older version from setting page.
	 *
	 * @since	4.0.0
	 * 
	 * @return void
	 */
	public function rollback_version() {

		if ( empty ( update_option( 'is_rmp_new_version', 0 ) ) ) {
			add_option( 'is_rmp_new_version', 0 );
		}

		wp_send_json_success( ['redirect' => admin_url('admin.php?page=responsive-menu')] );
	}

	/**
	 * Function to create a new theme.
	 *
	 * @since	4.0.0
	 * 
	 * @return json
	 */
	public function create_new_menu() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_name = sanitize_text_field( $_POST['menu_name'] );
		if ( empty( $menu_name ) ) {
			wp_send_json_error( [ 'message' => __('Enter the Menu name !', 'responsive-menu-pro') ] );
		}

		$menu_to_use = sanitize_text_field( $_POST['menu_to_use'] );
		if ( empty( $menu_to_use ) ) {
			wp_send_json_error( [ 'message' => __('Select menu to use !', 'responsive-menu-pro') ] );
		}

		$menu_theme = '';
		if ( ! empty( $_POST['menu_theme'] )  ) {
			$menu_theme  = sanitize_text_field( $_POST['menu_theme'] );
		}

		$theme_type = '';
		if ( ! empty( $_POST['theme_type'] )  ) {
			$theme_type  = sanitize_text_field( $_POST['theme_type'] );
		}

		$menu_show_on   = sanitize_text_field( $_POST['menu_show_on'] );

		$menu_show_on_pages = [];
		if ( ! empty( $_POST['menu_show_on_pages'] ) && is_array( $_POST['menu_show_on_pages'] ) ) {
			foreach ( $_POST['menu_show_on_pages']  as $key => $val ) {
				$menu_show_on_pages[ $key ] = sanitize_text_field( $val );
			}
		}

		$theme_options  = [];

		// Get appropriate theme as per theme type and theme name.
		if ( ! empty( $theme_type ) && 'downloaded' == $theme_type ) {
			$theme_manager   = Theme_Manager::get_instance();
			$theme_options   = $theme_manager->get_downloaded_theme_settings( $menu_theme );
		} else if ( ! empty( $theme_type ) && 'template' == $theme_type ) {
			$theme_manager   = Theme_Manager::get_instance();
			$theme_options   =  $theme_manager->get_saved_theme_options( $menu_theme );
		} else {
			$theme_options   = rmp_get_default_options();
		}

		// Create menu as post with rmp_menu cpt.
		$new_menu = array(
			'post_title'  => wp_strip_all_tags( $menu_name ),
			'post_author' => get_current_user_id(),
			'post_status' => 'publish',
			'post_type'   => 'rmp_menu',
		);

		$menu_id = wp_insert_post( $new_menu );

		$new_options = array(
			'menu_name'           => $menu_name,
			'menu_to_use'         => $menu_to_use,
			'menu_theme'          => $menu_theme,
			'theme_type'          => $theme_type,
			'menu_display_on'     => $menu_show_on,
			'menu_show_on_pages'  => $menu_show_on_pages,
			'menu_id'             => $menu_id
 		);

		$new_options  = array_merge( $theme_options , $new_options );

		if ( ! empty( $menu_id ) ) {

			update_post_meta( $menu_id, 'rmp_menu_meta', $new_options);

			/**
			 * Fires when menu is created and options is saved.
			 * 
			 * @param int $menu_id Menu ID.
			 */
			do_action( 'rmp_create_new_menu', $menu_id );

			$status = __('Menu is created successfully', 'responsive-menu-pro');			

		} else {
			$status = __('Unable to create new Menu', 'responsive-menu-pro');
		}

		wp_send_json_success( ['message' => $status ] );
	}

	/**
	 * This function register the shortcode for menu.
	 *
	 * @since  4.0.0
	 * 
	 * @param  Array  $atts    Attributes List.
	 * @param  string $content It contain text from shortcode.
	 * 
	 * @return HTML   $output  Menu contents.
	 */
	public function register_menu_shortcode( $attrs = [] ) {

		$attrs = shortcode_atts( [ 'id' => '' ], $attrs );

		$attrs = array_change_key_case( (array) $attrs, CASE_LOWER );

		// Check given id is valid.
		if ( empty( $attrs['id'] ) ) {
			return __( 'Please pass menu id as attribute.', 'responsive-menu-pro' );
		}

		$menu_id   = $attrs['id'];	
		if ( 'publish' !== get_post_status( $menu_id ) ) {
			return __( "Shortcode with menu id $menu_id is not published.", 'responsive-menu-pro' );
		}

		// Check shortcode option is activated or not.
		$option_manager = Option_Manager::get_instance();
		$option         = $option_manager->get_option( $menu_id, 'menu_display_on' );

		if ( 'shortcode' !== $option ) {
			return __( 'Shortcode deactivated', 'responsive-menu-pro' );
		}

		ob_start();

		$menu = new RMP_Menu( $menu_id );
		$menu->build_menu();

		return ob_get_clean();
	}

	/**
	 * Function to update the global options.
	 * 
	 * @since 4.0.0
	 * 
	 * @return void
	 */
	public function set_global_options() {

		$global_settings = get_option( 'rmp_global_setting_options' );
		if( empty( $global_settings )  ) {
			$default_options = rmp_global_default_setting_options();
			update_option( 'rmp_global_setting_options', $default_options  );
		}
	}

	/**
	 * Add sub menu pages in responsive menu admin.
	 *
	 * @since	4.0.0
	 */
	public function rmp_register_submenu_page() {

		add_submenu_page(
			'edit.php?post_type=rmp_menu',
			__( 'Settings', 'responsive-menu-pro' ),
			__( 'Settings', 'responsive-menu-pro' ),
			'manage_options',
			'settings',
			array( $this, 'rmp_global_settings_page' )
		);

		add_submenu_page (
			'edit.php?post_type=rmp_menu',
			__( 'Themes', 'responsive-menu-pro' ),
			__( 'Themes', 'responsive-menu-pro' ),
			'manage_options',
			'themes',
			array( $this, 'rmp_theme_admin_page' )
		);

		add_submenu_page (
			'edit.php?post_type=rmp_menu',
			__( 'Roadmap', 'responsive-menu-pro' ),
			__( 'Roadmap', 'responsive-menu-pro' ),
			'manage_options',
			'roadmap',
			array( $this, 'rmp_roadmap_admin_page' )
		);

	}

	/**
	 * Add template for roadmap page.
	 *
	 * @since	4.0.1
	 */
	public function rmp_roadmap_admin_page() {
		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-roadmap.php';
	}

	/**
	 * Add template to the themes page.
	 *
	 * @since	4.0.0
	 */
	public function rmp_theme_admin_page() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-themes.php';
	}

	/**
	 * Add template to the setting page.
	 *
	 * @since	4.0.0
	 * 
	 * @return void
	 */
	public function rmp_global_settings_page() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-settings.php';
	}

	/**
	 * Remove create new menu default link of rmp_menu post type.
	 * 
	 * @since 4.0.0
	 * 
	 * @return void
	 */
	function remove_default_add_cpt_page() {
		remove_submenu_page( 'edit.php?post_type=rmp_menu', 'post-new.php?post_type=rmp_menu' );
	}

	/**
	 * Function to add the new menu wizard template.
	 * 
	 * @since 4.0.0
	 * 
	 * @return void
	 */
	public function add_new_menu_widget() {
		include_once RMP_PLUGIN_PATH_V4 . '/templates/new-menu-wizard.php';
	}

	/**
	 * Function to change the edit label and url.
	 * @since 4.0.0
	 * 
	 * @param array $actions List of post row actions.
	 * @param Object $post Post object
	 * 
	 * @return array $actions
	 */
	public function rmp_menu_row_actions( $actions, $post ) {

		if ( 'rmp_menu' == $post->post_type ) {
			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="Edit"> %s </a>',
				esc_url( get_edit_post_link( $post->ID ) ),
				__( 'Customize', 'responsive-menu-pro' )
			);
		}

		return $actions;
	}

	/**
	 * Function to add the custom column.
	 * 
	 * @since 4.0.0
	 * 
	 * @param array $columns List of columns.
	 * 
	 * @return array $columns Edited columns list.  
	 */
	public function  set_custom_edit_menu_columns($columns) {
		
		unset( $columns['date'] );
		$columns['shortcode_place']  = __( 'Shortcode', 'responsive-menu-pro' );
		$columns['actions']          = __( 'Actions', 'responsive-menu-pro' );
		$columns['date']             = __( 'Date', 'responsive-menu-pro' );

		return $columns;
	}

	/**
	 * Function to change the edit url of post type rmp_menu
	 * 
	 * @since 4.0.0
	 * 
	 * @param string $url     Post edit URL.
	 * @param int    $post_id Post ID
	 * 
	 * @return string $url    Edited post url URL
	 */
	public function my_edit_post_link( $url, $post_id ) {

		if ( 'rmp_menu' == get_post_type() ) {	
			$url = get_admin_url() .'post.php?post='. $post_id .'&action=edit&editor=true';
		}

		return $url;
	}

	/**
	 * Function to add the data to the custom columns for the rmp_menu post type.
	 * 
	 * @since 4.0.0
	 * 
	 * @param string $column  Column Name
	 * @param int    $post_id Post ID
	 * 
	 * @return void
	 */
	function add_custom_columns( $column, $post_id ) {
		$option_manager = Option_Manager::get_instance();

		switch ( $column ) {

			case 'actions' :
				echo sprintf(
					'<a href="%s" class="button" aria-label="Customize"> %s </a>',
					esc_url( get_edit_post_link( $post_id) ),
					__( 'Customize', 'responsive-menu-pro' )
				);
				break;
			case 'shortcode_place' :

				$option  = $option_manager->get_option( $post_id, 'menu_display_on' );
				if( 'shortcode' === $option ) {
					echo sprintf('<code>[rmp_menu id="%s"]</code>', $post_id );
				} else {
					esc_html_e( 'Shortcode deactivated', 'responsive-menu-pro' );
				}

			break;

		}
	}

	/**
	 * Register rmp_menu custom post type.
	 *
	 * @since 4.0.0
	 */
	public function rmp_menu_cpt() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$labels = array(
			'name'                => __( 'Responsive Menu', 'responsive-menu-pro' ),
			'singular_name'       => 'Rmp_Menu',
			'menu_name'           => __( 'Responsive Menu', 'responsive-menu-pro' ),
			'parent_item_colon'   => __( 'Parent Menu', 'responsive-menu-pro' ),
			'all_items'           => __( 'Menus', 'responsive-menu-pro' ),
			'view_item'           => __( 'View Menu', 'responsive-menu-pro' ),
			'add_new_item'        => __( 'Add New Menu', 'responsive-menu-pro' ),
			'add_new'             => __( 'Create New Menu', 'responsive-menu-pro' ),
			'edit_item'           => __( 'Edit Menu', 'responsive-menu-pro' ),
			'update_item'         => __( 'Update Menu', 'responsive-menu-pro' ),
			'search_items'        => __( 'Search Menu', 'responsive-menu-pro' ),
			'not_found'           => __( 'Not Found', 'responsive-menu-pro' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'responsive-menu-pro' )
		);

		$args = array(
			'label'               => __( 'Responsive Menu', 'responsive-menu-pro' ),
			'description'         => __( 'Responsive Menu' , 'responsive-menu-pro' ),
			'labels'              => $labels,
			'supports'            => array( 'title',  'author'),
			'public'              => false,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'has_archive'         => false,
			'can_export'          => false,
			'exclude_from_search' => true,
			'taxonomies' 	      => array(),
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'menu_icon'           =>  RMP_PLUGIN_URL_V4 .'/assets/images/rmp-logo.png'
		);

		register_post_type( 'rmp_menu', $args );

		/**
		 * This action will be useful when need hooks after cpt register.
		 * @param CPT rmp_menu
		 */
		do_action( 'rmp_after_cpt_registered', 'rmp_menu' );
	}

	/**
	 * Function to export the menu
	 *
	 * @since	4.0.0
	 * 
	 * @return json
	 */
	public function rmp_export_menu() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_id = sanitize_text_field( $_POST['menu_id'] );
		if ( empty( $menu_id ) ) {
			wp_send_json_error( [ 'message' => __('Select menu !', 'responsive-menu-pro') ] );
		}

		$option_manager = Option_Manager::get_instance();
		$option         = $option_manager->get_options( $menu_id );

		wp_send_json_success( json_encode( $option ) );
	}

	/**
	 * Function to import the menu settings.
	 *
	 * @since	4.0.0
	 * 
	 * @return json
	 */
	public function rmp_import_menu() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		if( empty( $_FILES['file']['name'] ) ) {
			wp_send_json_error( [ 'message' => __('Please add file !', 'responsive-menu-pro') ] );
		}

		$file_type = pathinfo( basename( $_FILES["file"]["name"] ), PATHINFO_EXTENSION );

		if( empty( $_FILES['file']['tmp_name'] ) || 'json' != $file_type ) {
			wp_send_json_error( [ 'message' => __('Please add json file !', 'responsive-menu-pro') ] );
		}

		$menu_id = sanitize_text_field( $_POST['menu_id'] );
		if ( empty( $menu_id ) ) {
			wp_send_json_error( [ 'message' => __('Select menu !', 'responsive-menu-pro') ] );
		}

		$file_contents  = file_get_contents( $_FILES['file']['tmp_name'] );
		$import_options = json_decode( $file_contents, true ); 

		$option_manager = Option_Manager::get_instance();
		$exist_option         = $option_manager->get_options( $menu_id );

		// Some required options replced in imported settings with existing menu settings.
		$import_options['menu_name'] = $exist_option['menu_name'];
		$import_options['theme_type'] = 'default';
		$import_options['menu_theme'] = null;
		$import_options['menu_to_use'] = $exist_option['menu_to_use'];
		$import_options['menu_to_use_in_mobile'] = $exist_option['menu_to_use_in_mobile'];

		update_post_meta( $menu_id, 'rmp_menu_meta' , $import_options );
		/**
		 * Fires when menu is imported.
		 * 
		 * @since 4.0.0
		 * 
		 * @param int $menu_id
		 */
		do_action( 'rmp_import_menu', $menu_id );

		wp_send_json_success( [ 'message' => __( 'Menu settings imported successfully!', 'responsive-menu-pro') ] );
	}

}
