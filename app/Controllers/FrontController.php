<?php

namespace ResponsiveMenuTest\Controllers;
use ResponsiveMenuTest\View\View;
use ResponsiveMenuTest\Management\OptionManager;
use ResponsiveMenuTest\Formatters\Minifier;

class FrontController {

    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    public function index($base_url, $blog_id) {
        $options = $this->manager->all();

        add_filter('body_class', function($classes) use($options) {
            $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
            return $classes;
        });

        if($options['external_files'] == 'on'):
            $css_file = $base_url . '/responsive-menu-test-data/css/responsive-menu-' . $blog_id . '.css';
            $js_file = $base_url . '/responsive-menu-test-data/js/responsive-menu-' . $blog_id . '.js';
            wp_enqueue_style('responsive-menu', $css_file, null, false);
            wp_enqueue_script('responsive-menu', $js_file, ['jquery'], false, $options['scripts_in_footer'] == 'on' ? true : false);
        else:
            add_action('wp_head', function() use($options)  {
                $css_data = $this->view->render('css/app.css.twig', ['options' => $options]);
                if($options['minify_scripts'] == 'on')
                    $css_data = Minifier::minify($css_data);

                echo '<style>' . $css_data . '</style>';
            }, 100);

            add_action($options['scripts_in_footer'] == 'on' ? 'wp_footer' : 'wp_head', function() use($options) {
                $js_data = $this->view->render('js/app.js.twig', ['options' => $options]);
                if($options['minify_scripts'] == 'on')
                    $js_data = Minifier::minify($js_data);

                echo '<script>' . $js_data . '</script>';
            }, 100);
        endif;

        if($options['shortcode'] == 'on'):
            add_shortcode('responsive_menu', function($atts) use($options) {
                return $this->view->render('app.html.twig', ['options' => array_merge($options, $atts)]);
            });
        else:
            add_action('wp_footer', function() use($options) {
                echo $this->view->render('app.html.twig', ['options' => $options]);
            });
        endif;

    }

    public function preview() {
        return $this->view->render('preview.html');
    }

}
