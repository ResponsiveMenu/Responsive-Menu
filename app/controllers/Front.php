<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Factories\CssFactory as CssFactory;
use ResponsiveMenu\Factories\JsFactory as JsFactory;
use ResponsiveMenu\ViewModels\Menu as MenuViewModel;

class Front extends Base
{
	public function index()
	{

    $options = $this->repository->all();

    if($options['mobile_only'] == 'on' && !wp_is_mobile())
      return;

    $css_factory = new CssFactory;
    $js_factory = new JsFactory;

    $css = $css_factory->build($options);
    $js = $js_factory->build($options);

    add_filter('body_class', function($classes) use($options) {
      $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
      return $classes;
    });

    if($options['external_files'] == 'on') :
      $data_folder_dir = plugins_url(). '/responsive-menu-3-data';
      $css_file = $data_folder_dir . '/css/responsive-menu-' . get_current_blog_id() . '.css';
      $js_file = $data_folder_dir . '/js/responsive-menu-' . get_current_blog_id() . '.js';
      wp_enqueue_style('responsive-menu', $css_file, null, null);
      wp_enqueue_script('responsive-menu', $js_file, ['jquery'], null, $options['scripts_in_footer'] == 'on' ? true : false);
    else :
      add_action('wp_head', function() use ($css) {
        echo '<style>' . $css . '</style>';
      });
      add_action($options['scripts_in_footer'] == 'on' ? 'wp_footer' : 'wp_head', function() use ($js) {
        echo '<script>' . $js . '</script>';
      });
    endif;

    $menu_display = new MenuViewModel($options);

    wp_enqueue_script('responsive-menu-font-awesome', 'https://use.fontawesome.com/b6bedb3084.js', null, null);

		$this->view->render('menu', ['options' => $options, 'menu' => $menu_display->getHtml()]);
		$this->view->render('button', ['options' => $options]);

	}

}
