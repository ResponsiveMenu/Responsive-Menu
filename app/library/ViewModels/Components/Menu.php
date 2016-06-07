<?php

namespace ResponsiveMenu\ViewModels\Components;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;
use ResponsiveMenu\Walkers\WpWalker as Walker;

class Menu implements ViewComponent {

  public function render(OptionsCollection $options) {

    return wp_nav_menu(
      [
        'container' => '',
        'menu_id' => 'responsive-menu',
        'menu_class' => null,
        'menu' => isset($options['menu_name']) ? $options['menu_name'] : null,
        'depth' => isset($options['depth']) ? $options['depth'] : 0,
        'theme_location' => isset($options['theme_location']) ? $options['theme_location'] : null,
        'walker' => isset($options['walker']) ? new $options['walker']($options) : new Walker($options),
        'echo' => false
      ]
    );

  }

}
