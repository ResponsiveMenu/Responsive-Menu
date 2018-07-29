<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\View\View;
use ResponsiveMenu\Management\OptionManager;
use ResponsiveMenu\Formatters\Minifier;

/**
* Entry point for all front end functionality.
*
* All routing for the front end comes through the functions below. When a
* front end page is loaded in the browser it will come through here.
*
* @author Peter Featherstone <peter@featherstone.me>
*
* @since 3.0
*/
class FrontController {

    /**
    * Constructor for setting up the FrontController.
    *
    * The constructor allows us to switch implementations for managing options
    * and for rendering views. Useful for switching out mocked or stubbed
    * classes during testing.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param OptionManager  $manager    Instance of a Management options class.
    * @param View           $view       Instance of a View class for rendering.
    */
    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    /**
    * Main route for the front end.
    *
    * This is the default view for the plugin on an initial GET request to the
    * front end page.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @return string    Output HTML from rendered view.
    */
    public function index() {
        $options = $this->manager->all();
        $this->buildFrontEnd($options);
    }

    /**
    * Preview route for the front end.
    *
    * This is the preview view for the plugin when the preview admin option is
    * pressed.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @return string    Output HTML from rendered view.
    */
    public function preview() {
        return $this->view->render('preview.html.twig');
    }

    /**
    * Helper private method to setup and build the front end.
    *
    * This is the preview view for the plugin when the preview admin option is
    * pressed. We turn external files off to enable the preview options to
    * take effect.
    *
    * TODO: This is a horrible method that really needs to be broken up in some
    * way. There is a lot of setup and WordPress specific functionality going
    * on here that would ideally be abstracted away.
    *
    * @author Peter Featherstone <peter@featherstone.me>
    *
    * @since 3.0
    *
    * @param OptionsCollection  $options    A OptionsCollection object.
    */
    private function buildFrontEnd(OptionsCollection $options) {
        add_filter('body_class', function($classes) use($options) {
            $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
            return $classes;
        });

        if($options['external_files'] == 'on'):
            add_action('wp_enqueue_scripts', function() use($options) {
                $css_file = wp_upload_dir()['baseurl'] . '/responsive-menu/css/responsive-menu-' . get_current_blog_id() . '.css';
                $js_file = wp_upload_dir()['baseurl'] . '/responsive-menu/js/responsive-menu-' . get_current_blog_id() . '.js';
                wp_enqueue_style('responsive-menu', $css_file, null, false);
                wp_enqueue_script('responsive-menu', $js_file, ['jquery'], false, $options['scripts_in_footer'] == 'on' ? true : false);
            });
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
