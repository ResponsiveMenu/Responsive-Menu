<?php

namespace ResponsiveMenu\ViewModels;

use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Menu {

  public function __construct(OptionsCollection $options) {
      $this->options = $options;
  }

  public function getHtml() {
    $mapping = [
      'title' => 'ResponsiveMenu\ViewModels\Components\Menu\Title',
      'menu' => 'ResponsiveMenu\ViewModels\Components\Menu\Menu',
      'search' => 'ResponsiveMenu\ViewModels\Components\Menu\Search',
      'additional content' => 'ResponsiveMenu\ViewModels\Components\Menu\AdditionalContent'
    ];
    $content = '';

    foreach(json_decode($this->options['items_order']) as $key => $val):
      $mapper = new $mapping[$key];
      $content .= $mapper->render($this->options);
    endforeach;
    return $content;
  }

}
