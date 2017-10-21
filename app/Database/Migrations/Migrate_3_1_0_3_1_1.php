<?php

namespace ResponsiveMenu\Database\Migrations;

class Migrate_3_1_0_3_1_1 extends Migrate {

    protected $migrations = [
        'button_background_colour_active' => 'button_background_colour',
        'button_line_colour_hover' => 'button_line_colour',
        'button_line_colour_active' => 'button_line_colour',
        'menu_container_background_colour' => 'menu_background_colour',
    ];

}
