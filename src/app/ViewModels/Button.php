<?php

namespace ResponsiveMenu\ViewModels;
use ResponsiveMenu\ViewModels\Components\Button\Button as ButtonComponent;
use ResponsiveMenu\Collections\OptionsCollection;

class Button {

  public function __construct(ButtonComponent $component) {
      $this->component = $component;
  }

  public function getHtml(OptionsCollection $options) {
      return $this->component->render($options);
  }

}
