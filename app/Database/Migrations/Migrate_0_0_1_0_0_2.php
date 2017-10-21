<?php

namespace ResponsiveMenu\Database\Migrations;
use ResponsiveMenu\Collections\OptionsCollection;

class Migrate_0_0_1_0_0_2 extends Migrate {

    protected $migrations = [
        'foo' => 'baz'
    ];

    protected $migration_scripts = [
        'updateSun'
    ];

    protected function updateSun(OptionsCollection $options) {
        $sun = json_decode($options['sun']);
        if(is_array($sun)):
            $new_sun = [];
            foreach($sun as $update):
                unset($update[2]);
                $new_sun[] = $update;
            endforeach;
            $options['sun'] = $new_sun;
        endif;
        return $options;
    }

}
