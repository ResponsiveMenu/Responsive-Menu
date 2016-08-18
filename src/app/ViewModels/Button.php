<?php

namespace ResponsiveMenu\ViewModels;

use ResponsiveMenu\ViewModels\Components\Button\Button as ButtonComponent;
use ResponsiveMenu\Collections\OptionsCollection;

class Button {

  public function __construct(OptionsCollection $options, ButtonComponent $component) {
      $this->options = $options;
      $this->component = $component;
  }

  public function getHtml() {
      return $this->component->render($this->options);
  }

}
