<?php

namespace ResponsiveMenu\Translation;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Collections\OptionsCollection;

class Translator {

  public function translate(Option $option) {
    // WPML Support
    $translated = apply_filters('wpml_translate_single_string', $option->getValue(), 'Responsive Menu', $option->getName());

    // Polylang Support
    if(function_exists('pll__'))
      $translated = pll__($translated);

    return $translated;
  }

  public function searchUrl() {
    return function_exists('icl_get_home_url') ? icl_get_home_url() : get_home_url();
  }

  public function saveTranslations(OptionsCollection $options) {
    if(isset($options['menu_to_use']))
    	do_action('wpml_register_single_string', 'Responsive Menu', 'menu_to_use', $options['menu_to_use']->getValue());

    if(isset($options['button_title']))
    	do_action('wpml_register_single_string', 'Responsive Menu', 'button_title', $options['button_title']->getValue());

  	if(isset($options['menu_title']))
      	do_action('wpml_register_single_string', 'Responsive Menu', 'menu_title', $options['menu_title']->getValue());

  	if(isset($options['menu_title_link']))
      	do_action('wpml_register_single_string', 'Responsive Menu', 'menu_title_link', $options['menu_title_link']->getValue());

  	if(isset($options['menu_additional_content']))
      	do_action('wpml_register_single_string', 'Responsive Menu', 'menu_additional_content', $options['menu_additional_content']->getValue());
  }

}
