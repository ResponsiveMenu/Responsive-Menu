<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.0.18
Author: Peter Featherstone
Text Domain: responsive-menu
Author URI: https://peterfeatherstone.com
License: GPL2
Tags: responsive, menu, responsive menu
*/

/* Check correct PHP version first */
add_action('admin_init', 'check_responsive_menu_php_version');
function check_responsive_menu_php_version() {
  if(version_compare(PHP_VERSION, '5.4', '<')):
    add_action('admin_notices', 'responsive_menu_deactivation_text');
    deactivate_plugins(plugin_basename(__FILE__));
  endif;
}

function responsive_menu_deactivation_text() {
  echo '<div class="error"><p>' . sprintf(__('Responsive Menu requires PHP 5.4 or higher to function and has therefore been automatically disabled. You are still on %s.%sPlease speak to your webhost about upgrading your PHP version. For more information please visit %s', 'responsive-menu'), PHP_VERSION, '<br /><br />', '<a target="_blank" href="https://responsive.menu/why-php-5-4/">this page</a>.') . '</p></div>';
}

if(version_compare(PHP_VERSION, '5.4', '<'))
  return;

/* Required includes for plugin to function */
include dirname(__FILE__) . '/autoload.php';
include dirname(__FILE__) . '/src/config/services.php';

/* Initial Migration and Version Check synchronisation */
include dirname(__FILE__) . '/src/config/setup.php';

/* Finally route and initialise the plugin */
include dirname(__FILE__) . '/src/config/routing.php';
include dirname(__FILE__) . '/src/config/internationalise.php';
