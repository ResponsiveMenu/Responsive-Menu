<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Models\Option;

class OptionFactory {

  public function __construct($default_options, $option_helpers) {
    $this->defaults = $default_options;
    $this->helper = $option_helpers;
  }

  public function build($name, $value) {

    $filter = isset($this->helper[$name]['filter'])
      ? new $this->helper[$name]['filter']
      : new \ResponsiveMenu\Filters\TextFilter;

    $value = isset($value) || $value == '0' ? $value : $this->defaults[$name];
    $option = new Option($name, stripslashes_deep($value));
    $option->setFilter($filter);

    return $option;

  }

}
