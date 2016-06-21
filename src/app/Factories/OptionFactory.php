<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Models\ComplexOption as ComplexOption;
use ResponsiveMenu\Helpers\OptionHelper as Helper;

class OptionFactory
{
  public function build($name, $value)
	{
		include dirname(dirname(dirname(__FILE__))) . '/config/option_helpers.php';
		include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';

    $filter = isset($option_helpers[$name]['filter'])
      ? new $option_helpers[$name]['filter']
      : new \ResponsiveMenu\Filters\TextFilter;

    $form_component = isset($option_helpers[$name]['form_component'])
      ? new $option_helpers[$name]['form_component']
      : new \ResponsiveMenu\Form\Text;

    $value = isset($value) || $value === '0' ? $value : $default_options[$name];
		$option = new ComplexOption($name, $value);
    $option->setFilter($filter);
    $option->setFormComponent($form_component);
    $option->setData(isset($option_helpers[$name]['custom'])?$option_helpers[$name]['custom']:null);
    $option->setIsPro(isset($option_helpers[$name]['pro'])?$option_helpers[$name]['pro']:null);
    $option->setIsSemiPro(isset($option_helpers[$name]['semi_pro'])?$option_helpers[$name]['semi_pro']:null);
    $option->setPosition(isset($option_helpers[$name]['position'])?$option_helpers[$name]['position']:null);
    $option->setLabel(isset($option_helpers[$name]['label'])?$option_helpers[$name]['label']:null);
		return $option;
	}

}
