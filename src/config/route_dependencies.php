<?php

$container = new ResponsiveMenu\Routing\Container();

$container['option_helpers'] = function($c) {
  include dirname(__FILE__) . '/option_helpers.php';
  return $option_helpers;
};

$container['default_options'] = function($c) {
  include dirname(__FILE__) . '/default_options.php';
  return $default_options;
};

$container['database'] = function($c) {
  return new ResponsiveMenu\Database\WpDatabase;
};

$container['option_factory'] = function($c) {
  return new ResponsiveMenu\Factories\OptionFactory(
    $c['default_options'],
    $c['option_helpers']
  );
};

$container['option_repository'] = function($c) {
  return new ResponsiveMenu\Repositories\OptionRepository(
    $c['database'],
    $c['option_factory']
  );
};

$container['option_service'] = function($c) {
  return new ResponsiveMenu\Services\OptionService(
  	$c['option_repository'],
  	$c['option_factory']
	);
};

$container['current_version'] = function($c) {
  $plugin_data = get_plugin_data(dirname(dirname(dirname(__FILE__))) . '/responsive-menu.php', false, false);
  return $plugin_data['Version'];
};

$container['old_version'] = function($c) {
  return get_option('RMVer');
};

$container['old_options'] = function($c) {
  return get_option('RMOptions');
};

$container['migration'] = function($c) {
  return new ResponsiveMenu\Database\Migration(
    $c['database'],
    $c['option_service'],
    $c['default_options'],
    $c['current_version'],
    $c['old_version'],
    $c['old_options']
  );
};

$container['admin_view'] = function($c) {
  return new ResponsiveMenu\View\AdminView;
};

$container['front_view'] = function($c) {
  return new ResponsiveMenu\View\FrontView;
};

$container['admin_controller'] = function($c) {
  return new ResponsiveMenu\Controllers\Admin(
  	$c['option_service'],
  	$c['admin_view']
 );
};

$container['front_controller'] = function($c) {
  return new ResponsiveMenu\Controllers\Front(
    $c['option_service'],
    $c['front_view']
  );
};
