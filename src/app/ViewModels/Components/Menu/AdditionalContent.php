<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class AdditionalContent implements ViewComponent {

  public function render(OptionsCollection $options) {

    if($options['menu_additional_content']->getValue()):
      return '<div id="responsive-menu-additional-content">'.
              do_shortcode($options['menu_additional_content']) .
            '</div>';
    endif;

  }

}
