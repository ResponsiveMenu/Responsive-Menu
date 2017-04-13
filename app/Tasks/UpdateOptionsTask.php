<?php

namespace ResponsiveMenu\Tasks;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Formatters\Minifier;
use ResponsiveMenu\View\View;

class UpdateOptionsTask {

    private $translatables = [
        'menu_to_use',
        'button_title',
        'menu_title',
        'menu_title_link',
        'menu_additional_content'
    ];

    public function run(OptionsCollection $options, View $view) {
        /*
         * Build CSS and Js files
         *
         */
        if($options['external_files'] == 'on'):

            $base_dir = dirname(dirname(dirname(dirname(__FILE__)))) . '/responsive-menu-data';

            if(!is_dir($base_dir))
                if(!mkdir($base_dir))
                    throw new \Exception('You don\'t have permissions to create data folder - please check permissions.');

            if(!is_dir($base_dir . '/css'))
                if(!mkdir($base_dir . '/css'))
                    throw new \Exception('You don\'t have permissions to create CSS data folder - please check permissions.');

            if(!is_dir($base_dir . '/js'))
                if(!mkdir($base_dir . '/js'))
                    throw new \Exception('You don\'t have permissions to create JS data folder - please check permissions.');

            $css_file = $base_dir . '/css/responsive-menu-' . get_current_blog_id() . '.css';
            $css_data = $view->render('css/app.css.twig', ['options' => $options]);

            if($options['minify_scripts'] == 'on')
                $css_data = Minifier::minify($css_data);

            if(!file_put_contents($css_file, $css_data))
                throw new \Exception('You don\'t have permissions to write external CSS file - please check permissions.');

            $js_file = $base_dir . '/js/responsive-menu-' . get_current_blog_id() . '.js';
            $js_data = $view->render('js/app.js.twig', ['options' => $options]);

            if($options['minify_scripts'] == 'on')
                $js_data = Minifier::minify($js_data);

            if(!file_put_contents($js_file, $js_data)):
                throw new \Exception('You don\'t have permissions to write external JS file - please check permissions.');
            endif;

        else:
            /*
             * TODO: Do some tidy up like removing external files if this option is not set - be a good citizen!
             */
        endif;

        /*
         * Update translations for WPML
         */
        foreach($this->translatables as $option_name)
            if(isset($options[$option_name]))
                do_action('wpml_register_single_string', 'Responsive Menu', $option_name, $options[$option_name]);

    }

}