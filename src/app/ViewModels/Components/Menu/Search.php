<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;
use ResponsiveMenu\ViewModels\Components\ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Translation\Translator;

class Search implements ViewComponent {

  public function __construct(Translator $translator) {
    $this->translator = $translator;
  }

  public function render(OptionsCollection $options) {
    
      return '<div id="responsive-menu-search-box">
        <form action="'.$this->translator->searchUrl().'" class="responsive-menu-search-form" role="search">
          <input type="search" name="s" placeholder="' . __('Search', 'responsive-menu') . '" class="responsive-menu-search-box">
        </form>
      </div>';

  }

}
