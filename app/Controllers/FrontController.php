<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\View\View;
use ResponsiveMenu\Management\OptionManager;
use ResponsiveMenu\Formatters\Minifier;

class FrontController {

    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    public function index() {
        $options = $this->manager->all();
        $this->buildFrontEnd($options);
    }

    public function preview() {
        return $this->view->render('preview.html.twig');
    }

    private function buildFrontEnd(OptionsCollection $options) {
        add_filter('body_class', function($classes) use($options) {
            $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
            return $classes;
        });

        if($options['external_files'] == 'on'):
            $css_file = plugins_url() . '/responsive-menu-data/css/responsive-menu-' . get_current_blog_id() . '.css';
            $js_file = plugins_url() . '/responsive-menu-data/js/responsive-menu-' . get_current_blog_id() . '.js';
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

                if(is_array($atts))
                    $merged_options = array_merge($options->toArray(), $atts);
                else
                    $merged_options = $options->toArray();

                $new_collection = new OptionsCollection($merged_options);
                return $this->view->render('app.html.twig', ['options' => $new_collection]);
            });
        else:
            add_action('wp_footer', function() use($options) {
                echo $this->view->render('app.html.twig', ['options' => $options]);
            });
        endif;

    }

}
