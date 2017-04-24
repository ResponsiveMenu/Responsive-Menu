<?php

namespace ResponsiveMenu\Collections;

class OptionsCollection implements \ArrayAccess, \Countable {

    private $options;

    public function __construct(array $options = []) {
        $this->options = array_map(function($o) {
            $val = is_array($o) ? json_encode($o) : $o;
            return stripslashes($val);
        }, $options);
    }

    public function add(array $option) {
        $this->options = array_replace($this->options, $option);
    }

    public function getActiveArrow() {
        if($this->options['active_arrow_image'])
            return '<img alt="' . $this->options['active_arrow_image_alt'] .'" src="' . $this->options['active_arrow_image'] .'" />';
        else
            return $this->options['active_arrow_shape'];

    }

    public function getInActiveArrow() {
        if($this->options['inactive_arrow_image'])
            return '<img alt="' . $this->options['inactive_arrow_image_alt'] .'" src="' . $this->options['inactive_arrow_image'] .'" />';
        else
            return $this->options['inactive_arrow_shape'];

    }

    public function getTitleImage() {
        if($this->options['menu_title_image'])
            return '<img alt="' . $this->options['menu_title_image_alt'] .'" src="' . $this->options['menu_title_image'] .'" />';
        else
            return null;

    }

    public function getButtonIcon() {
        if($this->options['button_image'])
            return '<img alt="' . $this->options['button_image_alt'] .'" src="' . $this->options['button_image'] .'" class="responsive-menu-button-icon responsive-menu-button-icon-active" />';
        else
            return '<span class="responsive-menu-inner"></span>';
    }

    public function getButtonIconActive() {
        if($this->options['button_image'])
            return '<img alt="' . $this->options['button_image_alt_when_clicked'] .'" src="' . $this->options['button_image_when_clicked'] .'" class="responsive-menu-button-icon responsive-menu-button-icon-inactive" />';
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->options);
    }

    /*
     * All items returned must be a string or null
     */
    public function offsetGet($offset) {
        if(isset($this->options[$offset]))
            if(is_array($this->options[$offset]))
                return json_encode($this->options[$offset]);
            else
                return $this->options[$offset];

        return null;
    }

    public function offsetSet($offset, $value) {
        $this->options[$offset] = $value;
    }

    public function offsetUnset($offset) {
        if(isset($this->options[$offset]))
            unset($this->options[$offset]);
    }

    public function toArray() {
        $array = [];
        foreach($this->options as $key => $val)
            $array[$key] = $val;
        return $array;
    }

    public function count() {
        return count($this->options);
    }

}
