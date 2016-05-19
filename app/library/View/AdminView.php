<?php

namespace ResponsiveMenu\View;

class AdminView implements View
{
	public function render($location, $data = array())
	{
		$this->options = $data;
		include dirname(dirname(dirname(__FILE__))) . '/views/admin/' . $location . '.phtml';
	}

}
