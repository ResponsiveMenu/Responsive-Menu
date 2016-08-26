<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;
use ResponsiveMenu\ViewModels\Components\ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Translation\Translator;

class Title implements ViewComponent {

  public function __construct(Translator $translator) {
    $this->translator = $translator;
  }

  public function render(OptionsCollection $options) {

    $title = $this->translator->translate($options['menu_title']);
    $link = $this->translator->translate($options['menu_title_link']);

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
