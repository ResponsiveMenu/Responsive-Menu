<?php

namespace ResponsiveMenu\ViewModels\Components\Header;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class HtmlContent implements ViewComponent {

  public function render(OptionsCollection $options) {

    return '<div id="responsive-menu-header-bar-html" class="responsive-menu-header-bar-item responsive-menu-header-box">' .
              do_shortcode($options['header_bar_html_content']) .
            '</div>';

  }

}
