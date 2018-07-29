<?php

namespace ResponsiveMenu\Collections;

class OptionsCollection implements \ArrayAccess, \Countable {

    private $options;

    public function __construct(array $options = []) {
        $this->options = array_map(function($o) {
            return is_array($o) ? json_encode($o) : $o;
        }, $options);
    }

    public function add(array $option) {
        $value = $option[key($option)];
        $this->options[key($option)] = is_array($value) ? json_encode($value) : $value;
    }

    public function getActiveArrow() {
        if($this->options['active_arrow_image'])
            return '<img alt="' . $this->options['active_arrow_image_alt'] .'" src="' . $this->getThemedUrl($this->options['active_arrow_image']) .'" />';

        return $this->options['active_arrow_shape'];

    }

    public function getInActiveArrow() {
        if($this->options['inactive_arrow_image'])
            return '<img alt="' . $this->options['inactive_arrow_image_alt'] .'" src="' . $this->getThemedUrl($this->options['inactive_arrow_image']) .'" />';

        return $this->options['inactive_arrow_shape'];

    }

    public function getTitleImage() {
        if($this->options['menu_title_image'])
            return '<img alt="' . $this->options['menu_title_image_alt'] .'" src="' . $this->getThemedUrl($this->options['menu_title_image']) .'" />';

        return null;

    }

    public function getButtonIcon() {
        if($this->options['button_image'])
            return '<img alt="' . $this->options['button_image_alt'] .'" src="' . $this->getThemedUrl($this->options['button_image']) .'" class="responsive-menu-button-icon responsive-menu-button-icon-active" />';

        return '<span class="responsive-menu-inner"></span>';
    }

    public function getButtonIconActive() {
        if($this->options['button_image'])
            return '<img alt="' . $this->options['button_image_alt_when_clicked'] .'" src="' . $this->getThemedUrl($this->options['button_image_when_clicked']) .'" class="responsive-menu-button-icon responsive-menu-button-icon-inactive" />';
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->options);
    }

    public function offsetGet($offset) {
        if(isset($this->options[$offset]))
            return $this->options[$offset];
        return null;
    }

    public function offsetSet($offset, $value) {
        $this->add([$offset => $value]);
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

    private function getThemedUrl($image_url) {
        if($this->options['menu_theme']):
            $theme_url = wp_upload_dir()['baseurl'] . '/responsive-menu-themes/' . $this->options['menu_theme'];
            $image_url = str_replace('{theme_images}', $theme_url . '/images', $image_url);
        endif;

        return $image_url;
    }

}
