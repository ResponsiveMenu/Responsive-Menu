<?php

namespace ResponsiveMenu\ViewModels\Components\Header;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Logo implements ViewComponent {

  public function render(OptionsCollection $options) {

    return '<div id="responsive-menu-header-bar-logo" class="responsive-menu-header-bar-item">
            <img src="'. $options['header_bar_logo'].'" />
            </div>';


  }

}
