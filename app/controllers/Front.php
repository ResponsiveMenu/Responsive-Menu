<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Mappers\ScssButtonMapper as ScssButtonMapper;
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

    add_action('wp_head', function() use ($css, $js) {
      echo '<style>' . $css . '</style>';
      echo '<script>' . $js . '</script>';
    });

		$this->view->render('button', $options);
	}

}
