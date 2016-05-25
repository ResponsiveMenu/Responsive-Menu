<?php

namespace ResponsiveMenu\View;

interface View
{
	public function render($location, $options = [], $data = []);
}
