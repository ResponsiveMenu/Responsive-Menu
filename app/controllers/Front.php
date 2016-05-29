<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Mappers\ScssButtonMapper as ScssButtonMapper;
use ResponsiveMenu\Mappers\ScssMenuMapper as ScssMenuMapper;
use ResponsiveMenu\Mappers\WpMenuMapper as MenuMapper;
use ResponsiveMenu\Mappers\JsMapper as JsMapper;

class Front extends Base
{
	public function index()
	{
    $options = $this->repository->all();

    $css_button_mapper = new ScssButtonMapper($options);
    $css_button = $css_button_mapper->map();

    $css_menu_mapper = new ScssMenuMapper($options);
    $css_menu = $css_menu_mapper->map();

    $js_mapper = new JsMapper($options);
    $js = $js_mapper->map();

    $menu_mapper = new MenuMapper(
      $options['menu_to_use']->getValue(),
      $options['menu_depth']->getValue(),
      $options['theme_location_menu']->getValue(),
      $options['custom_walker']->getValue() ? $options['custom_walker']->getValue() : 'ResponsiveMenu\Walkers\WpWalker',
      $options['active_arrow_shape']->getValue(),
      $options['inactive_arrow_shape']->getValue()
    );

    add_filter('body_class', function($classes) use($options) {
      $classes[] = 'responsive-menu-' . $options['animation_type'] . '-' . $options['menu_appear_from'];
      return $classes;
    });

    add_action('wp_head', function() use ($css_button, $css_menu, $js) {
      echo '<style>' . $css_button . $css_menu . '</style>';
      echo '<script>' . $js . '</script>';
    });

		$this->view->render('menu', $options, ['menu' => $menu_mapper->map()]);
		$this->view->render('button', $options);

	}

}
