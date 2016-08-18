<?php

namespace ResponsiveMenu\ViewModels;

use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\ViewModels\Components\ComponentFactory;

class Menu {

  public function __construct(OptionsCollection $options, ComponentFactory $factory) {
      $this->options = $options;
      $this->factory = $factory;
  }

  public function getHtml() {
    $content = '';

    foreach(json_decode($this->options['items_order']) as $key => $val)
      if($val == 'on')
        $content .= $this->factory->build($key)->render($this->options);

    return $content;
  }

}
