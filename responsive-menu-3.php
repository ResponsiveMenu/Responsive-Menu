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
include dirname(__FILE__) . '/app/config/route_dependencies.php';

/* Route the admin */
$routing = new ResponsiveMenu\Routing\Routes(new ResponsiveMenu\Routing\WpRouting($container));
$routing->route();

/* Load Database and Setup */
$database = new ResponsiveMenu\Database\WpDatabase();

if(is_admin()):
  $migration = new ResponsiveMenu\Database\Migration($database);
  include dirname(__FILE__) . '/app/config/default_options.php';
  $migration->setup($default_options);
  $migration->migrate();
endif;
