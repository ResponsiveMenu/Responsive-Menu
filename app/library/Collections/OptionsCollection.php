<?php

namespace ResponsiveMenu\Collections;
use ResponsiveMenu\Models\Option as Option;

class OptionsCollection implements \ArrayAccess {

  private $options;

  public function add(Option $option) {
    $this->options[$option->getName()] = $option;
  }

  public function get($name) {
    return $this->options[$name];
  }

  public function all() {
    return $this->options;
  }

  public function offsetExists($offset) {
    return array_key_exists($offset, $this->options);
  }

  public function offsetGet($offset) {
    return $this->options[$offset];
  }

  public function offsetSet($offset, $value) {
    $this->add($value);
  }

  public function offsetUnset($offset) {
    unset($this->options[$offset]);
  }

}
