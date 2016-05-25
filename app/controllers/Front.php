<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Mappers\ScssButtonMapper as ScssButtonMapper;
use ResponsiveMenu\Mappers\WpMenuMapper as MenuMapper;
use ResponsiveMenu\Mappers\JsMapper as JsMapper;

class Front extends Base
{
	public function index()
	{
    $options = $this->repository->all();

    $css_mapper = new ScssButtonMapper($options);
    $css = $css_mapper->map();

    $js_mapper = new JsMapper($options);
    $js = $js_mapper->map();

    $menu_mapper = new MenuMapper(
      $options['menu_to_use']->getValue(),
      $options['menu_depth']->getValue(),
      $options['custom_walker']->getValue(),
      $options['theme_location_menu']->getValue()
    );

    add_action('wp_head', function() use ($css, $js) {
      echo '<style>' . $css . '</style>';
      echo '<script>' . $js . '</script>';
    });

		$this->view->render('menu', $options, ['menu' => $menu_mapper->map()]);
		$this->view->render('button', $options);

	}

}
