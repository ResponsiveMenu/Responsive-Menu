<?php

namespace ResponsiveMenu\View;

class FrontView implements View
{

	public function render($location, $l = [])
	{
    add_action('wp_footer', function() use ($location, $l) {
      include dirname(dirname(dirname(__FILE__))) . '/views/' . $location . '.phtml';
    });
	}

	public function make($location, $l = [])
	{
    ob_start();
      include dirname(dirname(dirname(__FILE__))) . '/views/' . $location . '.phtml';
      $output = ob_get_contents();
    ob_end_clean();
    return $output;
	}

}
