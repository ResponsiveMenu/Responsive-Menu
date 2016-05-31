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
		$helper = new Helper($option_helpers[$options->name]);
		$option = new Option($options->name, $options->value, $helper, $default_options[$options->name]);
		$value = $option->getFilter()->filter($option->getValue());
		$option->setValue($value);
    
		return $option;
	}

	static public function buildMany($options)
	{
		include dirname(dirname(dirname(__FILE__))) . '/config/option_helpers.php';
		include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';

    foreach($options as $key):
      $saved[$key->name] = new Option($key->name, $key->value, new Helper($option_helpers[$key->name]), $default_options[$key->name]);
	    $value = $saved[$key->name]->getFilter()->filter($saved[$key->name]->getValue());
	    $saved[$key->name]->setValue($value);
    endforeach;

		return $saved;
	}
}
