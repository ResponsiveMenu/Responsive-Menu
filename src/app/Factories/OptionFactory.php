<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Models\Option as Option;

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
    //$value = $this->map_deep($value, [$this, 'stripslashes_from_strings_only']);
		$option = new Option($name, $value);
    $option->setFilter($filter);
		return $option;
	}

  public function map_deep($value, $callback) {
    if(is_array($value))
      foreach($value as $index => $item)
        $value[$index] = $this->map_deep($item, $callback);
    else
      $value = call_user_func( $callback, $value );

    return $value;
  }

  public function stripslashes_from_strings_only($value) {
    return is_string($value) ? stripslashes($value) : $value;
	}

}
