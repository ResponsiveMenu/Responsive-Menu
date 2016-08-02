<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Title implements ViewComponent {

  public function render(OptionsCollection $options) {

    $title = apply_filters('wpml_translate_single_string', $options['menu_title']->getValue(), 'Responsive Menu', 'menu_title');
    $link = apply_filters('wpml_translate_single_string', $options['menu_title_link']->getValue(), 'Responsive Menu', 'menu_title_link');

    /*
    Add Polylang Support */
    if(function_exists('pll__')):
      $title = pll__($title);
      $link = pll__($link);
    endif;

    if($options['menu_title']->getValue() || $options->getTitleImage()):
      $content = '<div id="responsive-menu-title">';

      if($options['menu_title_link']->getValue())
        $content .= '<a href="'.$link.'" target="'.$options['menu_title_link_location'].'">';

      if($options->getTitleImage())
        $content .= '<div id="responsive-menu-title-image">' . $options->getTitleImage() . '</div>';

      if($options['menu_title_link']->getValue())
        $content .= '</a>';

      if($options['menu_title_link']->getValue())
        $content .= '<a href="'.$link.'" target="'.$options['menu_title_link_location'].'">';

      $content .= $title;

      if($options['menu_title_link']->getValue())
        $content .= '</a>';

      $content .= '</div>';

      return $content;
    endif;

  }

}
