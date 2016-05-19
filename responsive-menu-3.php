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

/* Only required includes in system */

if(is_admin()):
  include dirname(__FILE__) . '/autoload.php';
  include dirname(__FILE__) . '/app/config/default_options.php';
  include dirname(__FILE__) . '/app/config/route_dependencies.php';

  /* Route the admin */
  $routing = new ResponsiveMenu\Routing\Routes(new ResponsiveMenu\Routing\WpRouting($route_dependencies));
  $routing->route();

  /* Load Database and Setup */
  $database = new ResponsiveMenu\Database\WpDatabase();
  $migration = new ResponsiveMenu\Database\Migration($database);
  $migration->setup($default_options);
  $migration->migrate();
endif;
