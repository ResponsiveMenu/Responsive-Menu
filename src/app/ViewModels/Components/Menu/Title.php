<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Title implements ViewComponent {

  public function render(OptionsCollection $options) {

    if($options['menu_title']->getValue() || $options->getTitleImage()):
      $content = '<div id="responsive-menu-title">';
      if($options->getTitleImage())
        $content .= '<div id="responsive-menu-title-image">' . $options->getTitleImage() . '</div>';
      if($options['menu_title_link']->getValue())
        $content .= '<a href="'.$options['menu_title_link'].'" target="'.$options['menu_title_link_location'].'">';
      $content .= $options['menu_title'];
      if($options['menu_title_link']->getValue())
        $content .= '</a>';
      $content .= '</div>';
      return $content;
    endif;

  }

}
