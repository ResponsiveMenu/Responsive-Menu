<?php

namespace ResponsiveMenu\Collections;
use ResponsiveMenu\Models\Option;

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

  public function usesFontIcons() {
    return false;
  }

  public function getActiveArrow() {
    if($this->options['active_arrow_image'] && $this->options['active_arrow_image']->getValue())
      return '<img alt="' . $this->options['active_arrow_image_alt'] .'" src="' . $this->options['active_arrow_image'] .'" />';
    else
      return $this->options['active_arrow_shape'];

  }

  public function getInActiveArrow() {
    if($this->options['inactive_arrow_image'] && $this->options['inactive_arrow_image']->getValue())
      return '<img alt="' . $this->options['inactive_arrow_image_alt'] .'" src="' . $this->options['inactive_arrow_image'] .'" />';
    else
      return $this->options['inactive_arrow_shape'];

  }

  public function getTitleImage() {
    if($this->options['menu_title_image'] && $this->options['menu_title_image']->getValue())
      return '<img alt="' . $this->options['menu_title_image_alt'] .'" src="' . $this->options['menu_title_image'] .'" />';
    else
      return null;

  }

  public function getButtonIcon() {
    if($this->options['button_image'] && $this->options['button_image']->getValue())
      return '<img alt="' . $this->options['button_image_alt'] .'" src="' . $this->options['button_image'] .'" class="responsive-menu-button-icon responsive-menu-button-icon-active" />';
    else
      return '<span class="responsive-menu-inner"></span>';
  }

  public function getButtonIconActive() {
    if($this->options['button_image'] && $this->options['button_image']->getValue())
      return '<img alt="' . $this->options['button_image_alt_when_clicked'] .'" src="' . $this->options['button_image_when_clicked'] .'" class="responsive-menu-button-icon responsive-menu-button-icon-inactive" />';
  }

  public function offsetExists($offset) {
    return array_key_exists($offset, $this->options);
  }

  public function offsetGet($offset) {
    return isset($this->options[$offset]) ? $this->options[$offset] : null;
  }

  public function offsetSet($offset, $value) {
      $this->options[$offset] = $value;
  }

  public function offsetUnset($offset) {
    if(isset($this->options[$offset]))
      unset($this->options[$offset]);
  }

  public function isEmpty() {
    return isset($this->options) && count($this->options) > 0 ? false : true;
  }

}
