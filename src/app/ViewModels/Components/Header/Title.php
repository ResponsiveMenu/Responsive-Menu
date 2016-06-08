<?php

namespace ResponsiveMenu\ViewModels\Components\Header;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Title implements ViewComponent {

  public function render(OptionsCollection $options) {

    return '<div id="responsive-menu-header-bar-title" class="responsive-menu-header-bar-item responsive-menu-header-box">Title</div>';


  }

}
