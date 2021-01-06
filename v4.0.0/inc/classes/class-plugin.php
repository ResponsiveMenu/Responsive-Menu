<?php
/**
 * Plugin manifest class.
 *
 * @package responsive-menu-pro
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Elementor\Elementor_Manager;
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
		RMP_Migration::get_instance();
		Elementor_Manager::get_instance();

		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		add_action( 'plugins_loaded', [ $this, 'rmp_load_plugin_text_domain' ] );
		add_action( 'admin_notices', [ $this, 'rmp_deactivate_paid_version_notice' ] );

		// Check current config and environment support wp_body_open or not.

		if( $this->has_support( 'wp_body_open' ) ) {
			add_action( 'wp_body_open' , [ $this, 'menu_render_on_frontend'] );
		} else {
			add_action( 'wp_footer' , [ $this, 'menu_render_on_frontend'] );
		}
	}

	/**
	 * Function to show the admin notice if plugin deactivate.
	 * 
	 * @return void
	 */
	public function rmp_deactivate_paid_version_notice() {
		if( get_transient('og-admin-notice-activation') ) {
			printf(
				'<div class="notice notice-error is-dismissible">
				<p>%s</p>
				</div>',
				__('Responsive Menu has been deactivated','responsive-menu-pro' )	
			);
			delete_transient( 'og-admin-notice-activation-pro' );
		}
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
	 * Function to render the nenu on frontend.
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
	
			if ( ! empty( $menu_show_on ) && 'shortcode' === $menu_show_on ) {
				continue;
			}

			$menu = new \RMP\Features\Inc\RMP_Menu( $menu_id );
			$menu->build_menu();
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

		// Check wp footer option is enabled.
		$option_manager  = Option_Manager::get_instance();
		if ( 'wp_body_open' == $hook && 'on' == $option_manager->get_global_option( 'rmp_wp_footer_hook' ) ) {
			return false;
		}

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
