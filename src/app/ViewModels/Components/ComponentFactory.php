<?php

namespace ResponsiveMenu\ViewModels\Components;
use ResponsiveMenu\Translation\Translator;

class ComponentFactory {

  public function build($key) {

    $components = [
      'title' => 'ResponsiveMenu\ViewModels\Components\Menu\Title',
      'menu' => 'ResponsiveMenu\ViewModels\Components\Menu\Menu',
      'search' => 'ResponsiveMenu\ViewModels\Components\Menu\Search',
      'additional content' => 'ResponsiveMenu\ViewModels\Components\Menu\AdditionalContent'
    ];

    return new $components[$key](new Translator);

  }

}
