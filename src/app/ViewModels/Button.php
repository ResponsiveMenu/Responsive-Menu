<?php

namespace ResponsiveMenu\ViewModels;

use ResponsiveMenu\ViewModels\Components\Button\Button as ButtonComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Button {

  public function __construct(OptionsCollection $options) {
      $this->options = $options;
  }

  public function getHtml() {
      $mapper = new ButtonComponent();
      return $mapper->render($this->options);
  }

}
