<?php
/**
 * Plugin manifest class.
 *
 * @package responsive-menu-pro
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		// Load plugin classes.
		Admin::get_instance();
		Assets::get_instance();
		Editor::get_instance();
		Editor_Manager::get_instance();
		Preview::get_instance();
		Control_Manager::get_instance();
		Theme_Manager::get_instance();
		Option_Manager::get_instance();
		Style_Manager::get_instance();
		UI_Manager::get_instance();
		Widget_Manager::get_instance();
		RMP_Migration::get_instance();

		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		// Active and de-active plugin hook.
		register_activation_hook( RMP_PLUGIN_FILE,  [ $this , 'rmp_plugin_activation'] );
		register_deactivation_hook( RMP_PLUGIN_FILE, [ $this, 'rmp_plugin_deactivation' ] );
		add_action( 'plugins_loaded', [ $this, 'rmp_load_plugin_text_domain' ] );

		add_action( 'admin_notices', [ $this, 'rmp_license_upgrade_admin_notice'] );
		add_action( 'plugin_action_links_' . plugin_basename( RMP_PLUGIN_FILE ) , [ $this, 'rmp_license_upgrade_link' ] );
		add_action( "wp_ajax_rmp_license_admin_notice_dismiss", [ $this, 'rmp_license_admin_notice_dismiss'] );

		// Check current configurtion and environment support wp_body_open or not.

		if( $this->has_support( 'wp_body_open' ) ) {
			add_action( 'wp_body_open' , [ $this, 'menu_render_on_frontend'] );
		} else {
			add_action( 'wp_footer' , [ $this, 'menu_render_on_frontend'] );
		}
	}

	/**
	 * Activation of plugin.
	 * 
	 * @return void
	 */
	public function rmp_plugin_activation() {
		flush_rewrite_rules();
	}

	/**
	 * Deactivation of plugin.
	 * 
	 * @return void
	 */
	public function rmp_plugin_deactivation() {
		flush_rewrite_rules();
	}

	/**
	 * Load plugin text domain.
	 * 
	 * @version 4.0.0
	 * 
	 * @return void
	 */
	public function rmp_load_plugin_text_domain() {
		load_plugin_textdomain( 'responsive-menu-pro', false, RMP_PLUGIN_DIR_NAME . '/v4.0.0/languages' );
	}

	/**
	 * Add plugin upgrade link.
	 *
	 * Add a link to the settings page on the responsive menu page.
	 * 
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	function rmp_license_upgrade_link( $links ) {

		$license_type = get_option('responsive_menu_pro_license_type');

		if ( ! empty( $license_type ) ) {
			return $links;
		}

		$links = array_merge(
			$links,
			array( '<a class="responsive-menu-license-upgrade-link" href="' . esc_url( admin_url( 'edit.php?post_type=rmp_menu&page=settings' ) ) . '">' . __( 'Connect & Upgrade', 'responsive-menu-pro') . '</a>')
		);

		return $links;
	}

	/**
	 * Function to hide the admin notice permanent.
	 */
	function rmp_license_admin_notice_dismiss() {
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'responsive_menu_admin_notice', true );
	}


	/**
	 * Function to add the admin notice template.
	 * 
	 * @version 4.0.0
	 * 
	 */
	public function rmp_license_upgrade_admin_notice() {
		$license_type = get_option('responsive_menu_pro_license_type');

		if ( ! empty( $license_type ) ) {
			return;
		}

		$post_type = get_post_type(); 
		if ( empty( $post_type ) && ! empty( $_GET['post_type'] ) ) {
			$post_type = $_GET['post_type'];
		}

		if ( 'rmp_menu' !== $post_type ) {
			return;
		}

		$user_id = get_current_user_id();
		if ( ! empty( get_user_meta( $user_id, 'responsive_menu_admin_notice') ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/admin-notices.php';
	}

	/**
	 * Function to render tthe nenu on frontend.
	 * 
	 * @version 4.0.0
	 */
	function menu_render_on_frontend() {

		$option_manager  = Option_Manager::get_instance();
		$menu_ids   = get_all_rmp_menu_ids();
	
		if ( empty( $menu_ids ) ) {
			return;
		}
	
		foreach ( $menu_ids as $menu_id ) {
	
			$menu_show_on = $option_manager->get_option( $menu_id, 'menu_display_on' );
			$page_ids     = $option_manager->get_option( $menu_id, 'menu_show_on_pages' );
	
			if ( ! empty( $menu_show_on ) &&
				( 'shortcode' === $menu_show_on ||
				( 'exclude-pages' === $menu_show_on && in_array( get_queried_object_id(), $page_ids ) ) ||
				( 'include-pages' === $menu_show_on && ! in_array( get_queried_object_id(), $page_ids ) ) ) ) {
				continue;
			}
	
			$menu = new \RMP\Features\Inc\RMP_Menu( $menu_id );
			$menu->build_new_menu();
		}
	}

	/**
	 * Check support of wp_body_open for plugins and themes.
	 * 
	 * @since 4.0.0
	 * 
	 * @param string $hook Name of hook.
	 * @return boolean
	 */
	public function has_support( $hook ) {

		// Check wp core support wp_body_open hook or not.
		if( ! has_action( $hook ) ) {
			return false;
		}

		// If is_plugin_active function not exist then add plugin.php file from core.
		if( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// List of unsupported themes and plugins.
		$unsupported_extensions = [
			'oxygen/functions.php'
		];

		foreach( $unsupported_extensions as $extension ) {
			if( is_plugin_active( $extension ) ) {
				return false;
			}
		}

		return true;
	}

}
