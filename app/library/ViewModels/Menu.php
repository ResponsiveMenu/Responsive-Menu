<?php

namespace ResponsiveMenu\ViewModels;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Menu {

  public function __construct(OptionsCollection $options) {
      $this->options = $options;
  }

  public function getHtml() {
    $mapping = [
      'title' => 'ResponsiveMenu\ViewModels\Components\Title',
      'menu' => 'ResponsiveMenu\ViewModels\Components\Menu',
      'search' => 'ResponsiveMenu\ViewModels\Components\Search',
      'additional content' => 'ResponsiveMenu\ViewModels\Components\AdditionalContent'
    ];
    $content = '';
    foreach($this->options['items_order']->getValue() as $key => $val):
      $mapper = new $mapping[$key];
      $content .= $mapper->render($this->options);
    endforeach;
    return $content;
  }

}
