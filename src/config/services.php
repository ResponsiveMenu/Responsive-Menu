<?php

$container = new ResponsiveMenu\Routing\Container();

$container['current_version'] = function($c) {
  return '3.0.18';
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
  global $wpdb;
  return new ResponsiveMenu\Database\WpDatabase($wpdb);
};

$container['translator'] = function($c) {
  return new ResponsiveMenu\Translation\Translator;
};

$container['minifier'] = function($c) {
  return new ResponsiveMenu\Formatters\Minify;
};

$container['js_mapper'] = function($c) {
  return new ResponsiveMenu\Mappers\JsMapper;
};

$container['js_factory'] = function($c) {
  return new ResponsiveMenu\Factories\JsFactory(
    $c['js_mapper'],
    $c['minifier']
  );
};

$container['scss_compiler'] = function($c) {
  return new scssc_free;
};

$container['css_base_mapper'] = function($c) {
  return new ResponsiveMenu\Mappers\ScssBaseMapper($c['scss_compiler']);
};

$container['css_button_mapper'] = function($c) {
  return new ResponsiveMenu\Mappers\ScssButtonMapper($c['scss_compiler']);
};

$container['css_menu_mapper'] = function($c) {
  return new ResponsiveMenu\Mappers\ScssMenuMapper($c['scss_compiler']);
};

$container['css_factory'] = function($c) {
  return new ResponsiveMenu\Factories\CssFactory(
    $c['minifier'],
    $c['css_base_mapper'],
    $c['css_button_mapper'],
    $c['css_menu_mapper']
  );
};

$container['scripts_builder'] = function($c) {
  return new ResponsiveMenu\Filesystem\ScriptsBuilder(
    $c['css_factory'],
    $c['js_factory'],
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
  return new ResponsiveMenu\View\FrontView(
    $c['js_factory'],
    $c['css_factory']
  );
};

$container['admin_controller'] = function($c) {
  return new ResponsiveMenu\Controllers\Admin(
  	$c['option_service'],
  	$c['admin_view']
 );
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
