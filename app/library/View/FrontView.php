<?php

namespace ResponsiveMenu\View;

class FrontView implements View
{

	public function render($location, $data = array())
	{

    add_action('wp_footer', function() use ($location, $data) {
      $this->options = $data;
      include dirname(dirname(dirname(__FILE__))) . '/views/' . $location . '.phtml';
    });

	}

}
