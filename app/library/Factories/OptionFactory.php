<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Helpers\OptionHelper as Helper;

class OptionFactory
{
	static public function build($options)
	{
		include dirname(dirname(dirname(__FILE__))) . '/config/option_helpers.php';
		include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';
		$helper = new Helper($option_helpers);
		$option = new Option($options->name, $options->value, $helper, $default_options[$options->name]);
		$value = $option->getFilter()->filter($option->getValue());
		$option->setValue($value);
		return $option;
	}
}
