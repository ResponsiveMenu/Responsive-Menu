<?php

namespace ResponsiveMenu\Translattion;
use ResponsiveMenu\Modelsl\Option;

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

}
