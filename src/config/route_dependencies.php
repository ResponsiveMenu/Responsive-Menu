<?php

$container = new ResponsiveMenu\Routing\Container();

$container['current_version'] = function($c) {
  return '3.0.10';
};

$container['option_helpers'] = function($c) {
  include dirname(__FILE__) . '/option_helpers.php';
  return $option_helpers;
};

$container['default_options'] = function($c) {
  include dirname(__FILE__) . '/default_options.php';
  return $default_options;
};

$container['site_id'] = get_current_blog_id();

$container['database'] = function($c) {
  return new ResponsiveMenu\Database\WpDatabase;
};

$container['translator'] = function($c) {
  return new ResponsiveMenu\Translation\Translator;
};

$container['scripts_builder'] = function($c) {
  return new ResponsiveMenu\Filesystem\ScriptsBuilder(
    new ResponsiveMenu\Factories\CssFactory,
    new ResponsiveMenu\Factories\JsFactory,
    new ResponsiveMenu\Filesystem\FileCreator,
    new ResponsiveMenu\Filesystem\FolderCreator,
    $c['site_id']
  );
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
    $c['option_factory'],
    $c['default_options']
  );
};

$container['option_service'] = function($c) {
  return new ResponsiveMenu\Services\OptionService(
  	$c['option_repository'],
  	$c['option_factory'],
    $c['translator'],
    $c['scripts_builder']
	);
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

$container['translator'] = function($c) {
  return new ResponsiveMenu\Translation\Translator;
};

$container['component_factory'] = function($c) {
  return new ResponsiveMenu\ViewModels\Components\ComponentFactory;
};

$container['button_component'] = function($c) {
  return new ResponsiveMenu\ViewModels\Components\Button\Button($c['translator']);
};

$container['menu_view'] = function($c) {
   return new ResponsiveMenu\ViewModels\Menu($c['component_factory']);
};

$container['button_view'] = function($c) {
   return new ResponsiveMenu\ViewModels\Button($c['button_component']);
};

$container['front_controller'] = function($c) {
  return new ResponsiveMenu\Controllers\Front(
    $c['option_service'],
    $c['front_view'],
    $c['menu_view'],
    $c['button_view']
  );
};
