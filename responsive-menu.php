<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://expresstech.io
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 4.3.0
Author: ExpressTech
Text Domain: responsive-menu
Author URI: https://responsive.menu
License: GPL2
Tags: responsive, menu, responsive menu, mega menu, max mega menu, max menu
*/

/**
 * Constant as plugin version.
 */
if ( ! defined( 'RMP_PLUGIN_VERSION' ) ) {
	define( 'RMP_PLUGIN_VERSION', '4.3.0' );
}

define( 'RESPONSIVE_MENU_URL', plugin_dir_url( __FILE__ ) );

add_action( 'admin_init', 'check_responsive_menu_php_version' );
function check_responsive_menu_php_version() {
	if ( version_compare( PHP_VERSION, '5.4', '<' ) ) :
		add_action( 'admin_notices', 'responsive_menu_deactivation_text' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	endif;
}

function responsive_menu_deactivation_text() {
	echo '<div class="' . esc_attr( 'error' ) . '"><p>' . sprintf(
		'Responsive Menu requires PHP 5.4 or higher to function and has therefore been automatically disabled.
        You are still on %s.%sPlease speak to your web host about upgrading your PHP version.',
		PHP_VERSION,
		'<br /><br />'
	) . '</p></div>';
}

if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
	return;
}

// If this file called directly then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constant as plugin file.
 */
if ( ! defined( 'RMP_PLUGIN_FILE' ) ) {
	define( 'RMP_PLUGIN_FILE', plugin_dir_path( __FILE__ ) . 'responsive-menu.php' );
}

/**
 * Constant as dir of plugin.
 */
if ( ! defined( 'RMP_PLUGIN_DIR_NAME' ) ) {
	define( 'RMP_PLUGIN_DIR_NAME', untrailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );
}

/**
 * Constant as plugin path.
 */
if ( ! defined( 'RMP_PLUGIN_PATH' ) ) {
	define( 'RMP_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

/**
 * Constant as plugin URL.
 */
if ( ! defined( 'RMP_PLUGIN_URL' ) ) {
	define( 'RMP_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

/**
 * Constant as URI of assets build.
 */
if ( ! defined( 'RMP_PLUGIN_BUILD_URI' ) ) {
	define( 'RMP_PLUGIN_BUILD_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/build' );
}

/**
 * Constant as dir of assets build.
 */
if ( ! defined( 'RMP_PLUGIN_BUILD_DIR' ) ) {
	define( 'RMP_PLUGIN_BUILD_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/assets/build' );
}

/**
 * Constant as path of template file.
 */
if ( ! defined( 'RMP_PLUGIN_TEMPLATE_PATH' ) ) {
	define( 'RMP_PLUGIN_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
}

if ( ! defined( 'RMP_PLUGIN_PATH_V4' ) ) {
	define( 'RMP_PLUGIN_PATH_V4', RMP_PLUGIN_PATH . '/v4.0.0' );
}

if ( ! defined( 'RMP_PLUGIN_URL_V4' ) ) {
	define( 'RMP_PLUGIN_URL_V4', RMP_PLUGIN_URL . '/v4.0.0' );
}

/** Include the required files only*/
require_once RMP_PLUGIN_PATH_V4 . '/inc/helpers/autoloader.php';
require_once RMP_PLUGIN_PATH_V4 . '/inc/helpers/custom-functions.php';
require_once RMP_PLUGIN_PATH_V4 . '/inc/helpers/default-options.php';
require_once RMP_PLUGIN_PATH_V4 . '/libs/scssphp/vendor/autoload.php';
require_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-roadmap.php';

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function responsive_menu_features_plugin_loader() {
	\RMP\Features\Inc\Plugin::get_instance();
}

responsive_menu_features_plugin_loader();

// Active and de-active plugin hook.
register_activation_hook( __FILE__, 'responsive_menu_plugin_activation' );
register_deactivation_hook( __FILE__, 'responsive_menu_plugin_deactivation' );

/**
 * Activation of plugin.
 *
 * @return void
 */
function responsive_menu_plugin_activation() {
	$plugin = 'responsive-menu-pro/responsive-menu-pro.php';

	// Check if responsive menu (paid version) is activate then deactivate it.
	if ( is_plugin_active( $plugin ) ) {
		deactivate_plugins( $plugin );
		set_transient( 'og-admin-notice-activation-pro', true, 5 );
	}

	flush_rewrite_rules();
}

/**
 * Deactivation of plugin.
 *
 * @return void
 */
function responsive_menu_plugin_deactivation() {
	flush_rewrite_rules();
}

/**
 * Function to include the menu themes templates.
 *
 * @since 4.0.5
 *
 * @return void
 */
function rm_includes_menu_theme_template() {
	$theme_manager = \RMP\Features\Inc\Theme_Manager::get_instance();

	// Check class theme manager has this method or not.
	if ( ! method_exists( $theme_manager, 'get_menu_active_themes' ) ) {
		return;
	}

	$active_themes = $theme_manager->get_menu_active_themes();
	if ( empty( $active_themes ) ) {
		return;
	}

	// Include the file from each theme which has php template.
	foreach ( $active_themes as $key => $theme_name ) {
		$theme_index = $theme_manager->get_theme_index_file( $theme_name );

		if ( file_exists( $theme_index ) ) {
			require_once $theme_index;
		}
	}
}

rm_includes_menu_theme_template();

require_once 'review-banner-class.php';
