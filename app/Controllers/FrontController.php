<?php

namespace ResponsiveMenuTest\Controllers;
use ResponsiveMenuTest\View\View;
use ResponsiveMenuTest\Management\OptionManager;

class FrontController {

    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    public function index() {
        $options = $this->manager->all();

        add_filter('body_class', function($classes) use($options) {
            $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
            return $classes;
        });

        if($options['external_files'] == 'on'):
            $css_file = plugins_url(). '/responsive-menu-data/css/responsive-menu-' . get_current_blog_id() . '.css';
            $js_file = plugins_url(). '/responsive-menu-data/js/responsive-menu-' . get_current_blog_id() . '.js';
            wp_enqueue_style('responsive-menu', $css_file, null, false);
            wp_enqueue_script('responsive-menu', $js_file, ['jquery'], false, $options['scripts_in_footer'] == 'on' ? true : false);
        else:
            add_action('wp_head', function() use($options)  {
                echo '<style>' . $this->view->render('css/app.css.twig', ['options' => $options]) . '</style>';
            }, 100);

            add_action($options['scripts_in_footer'] == 'on' ? 'wp_footer' : 'wp_head', function() use($options) {
                echo '<script>' . $this->view->render('js/app.js.twig', ['options' => $options]) . '</script>';
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

}
