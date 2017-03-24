<?php

namespace ResponsiveMenuTest\Factories;
use ResponsiveMenuTest\Management\OptionManager;
use ResponsiveMenuTest\Database\Database;
use ResponsiveMenuTest\View\View;


class Factory {

    public static function OptionManager() {
        return new OptionManager(self::Database());
    }

    public static function Database() {
        global $wpdb;
        return new Database($wpdb);
    }

    public static function View() {
        include dirname(__FILE__) . '/twig.php';
        return new View($twig);
    }

}