<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;
use ResponsiveMenu\Walkers\WpWalker as Walker;

class Menu implements ViewComponent {

  public function render(OptionsCollection $options) {
    return wp_nav_menu(
      [
        'container' => '',
        'menu_id' => 'responsive-menu',
        'menu_class' => null,
        'menu' => $options['menu_to_use']->getValue() ? $options['menu_to_use']->getValue() : null,
        'depth' => $options['menu_depth']->getValue() ? $options['menu_depth']->getValue() : 0,
        'theme_location' => $options['theme_location_menu']->getValue() ? $options['theme_location_menu']->getValue() : null,
        'walker' => $options['custom_walker']->getValue() ? new $options['custom_walker']($options) : new Walker($options),
        'echo' => false
      ]
    );

  }

}
