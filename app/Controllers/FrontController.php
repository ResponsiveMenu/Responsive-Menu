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

        add_action('wp_head', function() use($options)  {
            echo '<style>' . $this->view->render('css/app.css.twig', ['options' => $options]) . '</style>';
        }, 100);

        add_action('wp_head', function() use($options) {
            echo '<script>' . $this->view->render('js/app.js.twig', ['options' => $options]) . '</script>';
        }, 100);

    }

}
