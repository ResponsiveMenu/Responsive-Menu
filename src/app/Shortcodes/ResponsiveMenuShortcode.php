<?php

namespace ResponsiveMenu\Shortcodes;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;
use ResponsiveMenu\View\View as View;

class ResponsiveMenuShortcode {

  public function render(array $attributes = [], OptionsCollection $options, View $view) {
    $this->view->render('menu', ['options' => $options, 'menu' => $menu_display->getHtml()]);
    $this->view->render('button', ['options' => $options]);
  }

}
