<?php

/* Horrible hack
* named something random so as not to conflict and can be accessed using the factory method at the
* bottom of this file
 *
 */
global $services_428734872364;
$services_428734872364 = new ResponsiveMenuTest\Container\Container;

$services_428734872364['database'] = function($c) {
    global $wpdb;
    return new ResponsiveMenuTest\Database\Database($wpdb);
};

$services_428734872364['option_manager'] = function($c) {
    return new ResponsiveMenuTest\Management\OptionManager($c['database']);
};

$services_428734872364['twig'] = function($c) {
    include_once dirname(__FILE__) . '/twig.php';
    return $twig;
};

$services_428734872364['view'] = function($c) {
    return new ResponsiveMenuTest\View\View($c['twig']);
};

$services_428734872364['admin_controller'] = function ($c) {
    return new ResponsiveMenuTest\Controllers\AdminController($c['option_manager'], $c['view']);
};

$services_428734872364['front_controller'] = function ($c) {
    return new ResponsiveMenuTest\Controllers\FrontController($c['option_manager'], $c['view']);
};

function get_responsive_menu_test_service($service) {
    global $services_428734872364;
    return $services_428734872364[$service];
}