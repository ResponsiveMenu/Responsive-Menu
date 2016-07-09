<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.0.3
Author: Responsive Menu
Text Domain: responsive-menu
Author URI: https://responsive.menu
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

  /*
  * Initial Migration and Version Check synchronisation */
	add_action('admin_init', function() use($container) {
	  include dirname(__FILE__) . '/src/config/default_options.php';
	  $migration = new ResponsiveMenu\Database\Migration($container['database'], $default_options);
	  $migration->setup();
  	$migration->synchronise();
	});

  /*
  Polylang Integration Section */
  add_action('plugins_loaded', function() use($container) {
    if(function_exists('pll_register_string')):
      $repo = new ResponsiveMenu\Repositories\Options\SimpleOptionReadRepository($container['database']);
      $options = $repo->all();
      pll_register_string('Menu Slug', $options['menu_to_use']->getValue(), 'Responsive Menu');
      pll_register_string('Button Title', $options['button_title']->getValue(), 'Responsive Menu');
      pll_register_string('Menu Title', $options['menu_title']->getValue(), 'Responsive Menu');
      pll_register_string('Menu Title Link', $options['menu_title_link']->getValue(), 'Responsive Menu');
    endif;
  });

endif;
