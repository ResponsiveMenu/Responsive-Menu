<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;
use ResponsiveMenu\ViewModels\Components\ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Translation\Translator;

class AdditionalContent implements ViewComponent {

  public function __construct(Translator $translator) {
    $this->translator = $translator;
  }

  public function render(OptionsCollection $options) {

    $content = $this->translator->translate($options['menu_additional_content']);

    if($content)
      return '<div id="responsive-menu-additional-content">' . $this->translator->allowShortcode($content) . '</div>';

  }

}
