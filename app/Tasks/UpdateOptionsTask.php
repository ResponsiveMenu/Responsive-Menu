<?php

namespace ResponsiveMenuTest\Tasks;
use ResponsiveMenuTest\Factories\Factory;


class UpdateOptionsTask {

    private $translatables = [
        'menu_to_use',
        'button_title',
        'menu_title',
        'menu_title_link',
        'menu_additional_content'
    ];

    public function run($options) {
        //throw new \Exception('test');
        //mkdir(DATA_CS_PATH);

        /*
         * Build CSS and Js files
         *
         */

        $view = Factory::View();

        /*
         * Update translations for WPML
         */
        foreach($this->translatables as $option_name)
            if(isset($options[$option_name]))
                do_action('wpml_register_single_string', 'Responsive Menu', $option_name, $options[$option_name]);


    }

}