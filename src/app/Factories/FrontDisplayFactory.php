<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Factories\CssFactory as CssFactory;
use ResponsiveMenu\Factories\JsFactory as JsFactory;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class FrontDisplayFactory {

  public function build(OptionsCollection $options) {

    $css_factory = new CssFactory;
    $js_factory = new JsFactory;

    $css = $css_factory->build($options);
    $js = $js_factory->build($options);

    add_filter('body_class', function($classes) use($options) {
      $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
      return $classes;
    });

    if($options['external_files'] == 'on') :
      $data_folder_dir = plugins_url(). '/responsive-menu-data';
      $css_file = $data_folder_dir . '/css/responsive-menu-' . get_current_blog_id() . '.css';
      $js_file = $data_folder_dir . '/js/responsive-menu-' . get_current_blog_id() . '.js';
      wp_enqueue_style('responsive-menu', $css_file, null, null);
      wp_enqueue_script('responsive-menu', $js_file, ['jquery'], null, $options['scripts_in_footer'] == 'on' ? true : false);
    else :
      add_action('wp_head', function() use ($css) {
        echo '<style>' . $css . '</style>';
      });
      add_action($options['scripts_in_footer'] == 'on' ? 'wp_footer' : 'wp_head', function() use ($js) {
        if(wp_script_is('jquery', 'done')) {
          echo '<script>' . $js . '</script>';
        }
      });
    endif;

  }
}
