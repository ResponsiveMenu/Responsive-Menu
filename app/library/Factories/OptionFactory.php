<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Helpers\OptionHelper as Helper;

class OptionFactory
{
  public function build($name, $value)
	{
		include dirname(dirname(dirname(__FILE__))) . '/config/option_helpers.php';
		include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';
		$helper = new Helper($option_helpers[$name]);

    $value = $value || $value === '0' ? $value : $default_options[$name];
		$option = new Option($name, $value);
    $option->setHelper($helper);
		$value = $option->getFilter()->filter($option->getValue());
		$option->setValue($value);
		return $option;
	}

}
