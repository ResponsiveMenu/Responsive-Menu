<?php

/*
Plugin Name: Responsive Menu
Plugin URI: http://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.0.1
Author: Responsive Menu
Text Domain: responsive-menu
Author URI: http://responsive.menu
License: GPL2
Tags: responsive, menu, responsive menu
*/

/* Check correct PHP version first */
register_activation_hook(__FILE__, 'activate_responsive_menu');
function activate_responsive_menu() {
  if(version_compare(PHP_VERSION, '5,4', '<')):
    deactivate_plugins(plugin_basename(__FILE__ ));
    wp_die(sprintf('Responsive Menu requires PHP 5.4 or higher. You are still on %s', PHP_VERSION));
  endif;
}

/* Required includes for plugin to function */
include dirname(__FILE__) . '/autoload.php';
include dirname(__FILE__) . '/src/config/route_dependencies.php';

/* Internationalise the plugin */
add_action('plugins_loaded', function() {
  load_plugin_textdomain('responsive-menu', false, basename(dirname(__FILE__)) . '/translations/');
});

/* Route the plugin */
$wp_router = new ResponsiveMenu\Routing\WpRouting($container);
$wp_router->route();

if(is_admin()):
	add_action('admin_init', function() use($container) {
	  include dirname(__FILE__) . '/src/config/default_options.php';
	  $migration = new ResponsiveMenu\Database\Migration($container['database'], $default_options);
	  $migration->setup();
	  if(isset($_GET['page']) && $_GET['page'] == 'responsive-menu')
	  	$migration->synchronise();
	});
endif;
