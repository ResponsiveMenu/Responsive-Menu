<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class AdditionalContent implements ViewComponent {

  public function render(OptionsCollection $options) {

    $content = apply_filters('wpml_translate_single_string', $options['menu_additional_content']->getValue(), 'Responsive Menu', 'menu_additional_content');

    /*
    Add Polylang Support */
    if(function_exists('pll__'))
      $content = pll__($content);

    if($options['menu_additional_content']->getValue()):
      return '<div id="responsive-menu-additional-content">'.
              do_shortcode($content) .
            '</div>';
    endif;

  }

}
