<?php

namespace ResponsiveMenu\WPML;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class WPML {

  public function saveFromOptions(OptionsCollection $options) {
    do_action('wpml_register_single_string', 'Responsive Menu', 'menu_to_use', $options['menu_to_use']->getValue());
    do_action('wpml_register_single_string', 'Responsive Menu', 'button_title', $options['button_title']->getValue());
    do_action('wpml_register_single_string', 'Responsive Menu', 'menu_title', $options['menu_title']->getValue());
    do_action('wpml_register_single_string', 'Responsive Menu', 'menu_title_link', $options['menu_title_link']->getValue());
  }

}
