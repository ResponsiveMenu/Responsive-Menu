<?php

namespace ResponsiveMenu\View;

class FrontView implements View
{

	public function render($location, $options = [], $data = [] )
	{

    add_action('wp_footer', function() use ($location, $options, $data) {
      $this->options = $options;
      $this->data = $data;
      include dirname(dirname(dirname(__FILE__))) . '/views/' . $location . '.phtml';
    });

	}

}
