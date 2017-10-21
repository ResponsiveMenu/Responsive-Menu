<?php

namespace ResponsiveMenu\Database\Migrations;
use ResponsiveMenu\Collections\OptionsCollection;

class Migrate_3_1_7_3_1_8 extends Migrate {

    protected $migrations = [
        'use_desktop_menu' => 'use_single_menu'
    ];

    protected $migration_scripts = [
        'setOldTheme'
    ];

    protected function setOldTheme(OptionsCollection $options) {
        $options['admin_theme'] = 'light';
        return $options;
    }

}
