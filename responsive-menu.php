<?php

/*
Plugin Name: Responsive Menu
Plugin URI: https://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.0.6
Author: Responsive Menu
Text Domain: responsive-menu
Author URI: https://responsive.menu
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
	  $migration = $container['migration'];
	  $migration->setup();
  	$migration->synchronise();
	});

  /*
  Polylang Integration Section */
  add_action('plugins_loaded', function() use($container) {
    if(function_exists('pll_register_string')):
      $service = $container['option_service'];
      $options = $service->all();

      $menu_to_use = isset($options['menu_to_use']) ? $options['menu_to_use']->getValue() : '';
      $button_title = isset($options['button_title']) ? $options['button_title']->getValue() : '';
      $menu_title = isset($options['menu_title']) ? $options['menu_title']->getValue() : '';
      $menu_title_link = isset($options['menu_title_link']) ? $options['menu_title_link']->getValue() : '';

      pll_register_string('Menu Slug', $menu_to_use, 'Responsive Menu');
      pll_register_string('Button Title', $button_title, 'Responsive Menu');
      pll_register_string('Menu Title', $menu_title, 'Responsive Menu');
      pll_register_string('Menu Title Link', $menu_title_link, 'Responsive Menu');
    endif;
  });
endif;
