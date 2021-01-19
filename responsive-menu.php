<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://expresstech.io
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 4.0.4
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
    define( 'RMP_PLUGIN_VERSION', '4.0.4' );
}

define('RESPONSIVE_MENU_URL', plugin_dir_url( __FILE__ ) );

add_action('admin_init', 'check_responsive_menu_php_version');
function check_responsive_menu_php_version() {
    if(version_compare(PHP_VERSION, '5.4', '<')):
        add_action('admin_notices', 'responsive_menu_deactivation_text');
        deactivate_plugins(plugin_basename(__FILE__));
    endif;
}

function responsive_menu_deactivation_text() {
    echo '<div class="error"><p>' . sprintf(
        'Responsive Menu requires PHP 5.4 or higher to function and has therefore been automatically disabled.
        You are still on %s.%sPlease speak to your web host about upgrading your PHP version.',
        PHP_VERSION,
        '<br /><br />'
    ) . '</p></div>';
}

if(version_compare(PHP_VERSION, '5.4', '<'))
    return;

if ( empty( get_option( 'is_rmp_new_version') ) && ! empty( get_option('responsive_menu_version') ) ) {

/**
 * Add admin notice to upgrade the plugin license.
 */
add_action( 'admin_notices', 'rmp_move_new_version_admin_notice' );
function rmp_move_new_version_admin_notice() {

    if ( ! empty( get_option( 'rm_upgrade_admin_notice' ) ) ) {
        return;
    }

    if ( empty( $_GET['page'] ) || 'responsive-menu' !== $_GET['page'] ) {
        return;
    }
?>

    <div class="notice-responsive-menu notice error is-dismissible rmp-version-upgrade-notice">
        <div class="notice-responsive-menu-logo">
            <img src="<?php echo RESPONSIVE_MENU_URL;?>/imgs/responsive-menu-logo.png" width="60" height="60" alt="logo" />
        </div>
        <div class="notice-responsive-menu-message">
            <h4 style="font-weight: 700;"><?php _e('Responsive Menu', 'responsive-menu-pro'); ?></h4>
            <p><?php _e( 'Try out our new version with improved layout, live preview and many more.', 'responsive-menu-pro' ); ?></p>
        </div>
        <div class="notice-responsive-menu-action">
            <a href="javascript:void(0)" class="rmp-upgrade-version" > <?php _e('Try, New version', 'responsive-menu-pro'); ?> </a>
        </div>
    </div>
<?php
}

add_action( 'admin_notices', 'og_pro_deactivate_pro_version_notice');

function og_pro_deactivate_pro_version_notice() {
    if(get_transient('og-admin-notice-activation-pro')) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>Responsive Menu Pro has been deactivated<p/>

        </div>
        <?php
        delete_transient('og-admin-notice-activation-pro');
    }
}

function og_deactivate_responsive_menu_pro() {

    $plugin = 'responsive-menu-pro/responsive-menu-pro.php';
  
   if( is_plugin_active($plugin) ){
        deactivate_plugins( 'responsive-menu-pro/responsive-menu-pro.php');
        set_transient( 'og-admin-notice-activation-pro', true, 5 );
        
        return;
    }
}
//to check weather another plugin is acivated or not.
register_activation_hook( __FILE__, 'og_deactivate_responsive_menu_pro');

include dirname(__FILE__) . '/vendor/autoload.php';
include dirname(__FILE__) . '/config/default_options.php';
include dirname(__FILE__) . '/config/services.php';
include dirname(__FILE__) . '/config/wp/scripts.php';
include dirname(__FILE__) . '/config/routing.php';
include dirname(__FILE__) . '/migration.php';
include dirname(__FILE__) . '/config/polylang.php';

} else {

    // If this file called directly then abort.
    if ( ! defined( 'WPINC' ) ) {
        die;
    }

    /**
     * Constant as plugin file.
     */
    if ( ! defined( 'RMP_PLUGIN_FILE' ) ) {
        define('RMP_PLUGIN_FILE', plugin_dir_path( __FILE__ ) . 'responsive-menu.php');
    }

    /**
     * Constant as dir of plugin.
     */
    if ( ! defined( 'RMP_PLUGIN_DIR_NAME' ) ) {
        define( 'RMP_PLUGIN_DIR_NAME', untrailingslashit ( dirname( plugin_basename( __FILE__ ) ) ) );
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
        define ( 'RMP_PLUGIN_PATH_V4', RMP_PLUGIN_PATH . '/v4.0.0' );
    }

    if ( ! defined( 'RMP_PLUGIN_URL_V4' ) ) {
        define ( 'RMP_PLUGIN_URL_V4', RMP_PLUGIN_URL . '/v4.0.0' );
    }

    /** Include the required files only*/
    require_once RMP_PLUGIN_PATH_V4 . '/inc/helpers/autoloader.php';
    require_once RMP_PLUGIN_PATH_V4 . '/inc/helpers/custom-functions.php';
    require_once RMP_PLUGIN_PATH_V4 . '/inc/helpers/default-options.php';
    require_once RMP_PLUGIN_PATH_V4 . '/libs/scssphp/vendor/autoload.php';

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
    register_activation_hook( __FILE__,  'responsive_menu_plugin_activation' );
    register_deactivation_hook( __FILE__, 'responsive_menu_plugin_deactivation' );

    /**
	 * Activation of plugin.
	 * 
	 * @return void
	 */
	function responsive_menu_plugin_activation() {

        $plugin = 'responsive-menu-pro/responsive-menu-pro.php';

        // Check if responsive menu (paid version) is activate then deactivate it.
		if( is_plugin_active( $plugin ) ) {
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

}