<?php

$container = new ResponsiveMenu\Routing\Container();

$container['database'] = function($c) {
  return new ResponsiveMenu\Database\WpDatabase;
};

$container['option_factory'] = function($c) {
  return new ResponsiveMenu\Factories\OptionFactory;
};

$container['option_repository'] = function($c) {
    return new ResponsiveMenu\Repositories\Options\ComplexOptionRepository($c['database'], $c['option_factory']);
};

$container['simple_option_repository'] = function($c) {
    return new ResponsiveMenu\Repositories\Options\SimpleOptionReadRepository($c['database']);
};

$container['admin_view'] = function($c) {
  return new ResponsiveMenu\View\AdminView;
};

$container['front_view'] = function($c) {
  return new ResponsiveMenu\View\FrontView;
};

$container['admin_controller'] = function($c) {
    return new ResponsiveMenu\Controllers\Admin\Main($c['option_repository'], $c['admin_view']);
};

$container['front_controller'] = function($c) {
    return new ResponsiveMenu\Controllers\Front($c['simple_option_repository'], $c['front_view']);
};
