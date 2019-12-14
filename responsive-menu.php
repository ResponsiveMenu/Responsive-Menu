<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.1.25
Author: ExpressTech
Text Domain: responsive-menu
Author URI: https://expresstech.io
License: GPL2
Tags: responsive, menu, responsive menu, mega menu, max mega menu, max menu
*/

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
