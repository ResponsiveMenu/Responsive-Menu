<?php

/*
Plugin Name: Responsive Menu 3
Plugin URI: http://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.0.0
Author: Responsive Menu
Text Domain: responsive-menu
Author URI: http://responsive.menu
License: GPL2
Tags: responsive, menu, responsive menu
*/

/* Required includes for plugin to function */
include dirname(__FILE__) . '/autoload.php';
include dirname(__FILE__) . '/src/config/route_dependencies.php';

/* Route the plugin */
$wp_router = new ResponsiveMenu\Routing\WpRouting($container);
$wp_router->route();

if(is_admin()):
  include dirname(__FILE__) . '/src/config/default_options.php';
  $migration = new ResponsiveMenu\Database\Migration($container['database'], $default_options);
  $migration->setup();
  $migration->synchronise();
endif;
