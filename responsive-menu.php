<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.1.19
Author: Peter Featherstone
Text Domain: responsive-menu
Author URI: https://peterfeatherstone.com
License: GPL2
Tags: responsive, menu, responsive menu
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

include dirname(__FILE__) . '/vendor/autoload.php';
include dirname(__FILE__) . '/config/default_options.php';
include dirname(__FILE__) . '/config/services.php';
include dirname(__FILE__) . '/config/wp/scripts.php';
include dirname(__FILE__) . '/config/routing.php';
include dirname(__FILE__) . '/migration.php';
include dirname(__FILE__) . '/config/polylang.php';
