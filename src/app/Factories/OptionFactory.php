<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Models\Option as Option;

class OptionFactory {

  public function __construct() {
    include dirname(dirname(dirname(__FILE__))) . '/config/option_helpers.php';
    include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';
    $this->defaults = $default_options;
    $this->helper = $option_helpers;
  }

  public function build($name, $value) {

    $filter = isset($this->helper[$name]['filter'])
      ? new $this->helper[$name]['filter']
      : new \ResponsiveMenu\Filters\TextFilter;

    $value = isset($value) || $value === '0' ? $value : $this->defaults[$name];
    $value = stripslashes_deep($value);
		$option = new Option($name, $value);
    $option->setFilter($filter);
		return $option;
	}

}
