<?php

namespace ResponsiveMenu\Mappers;

class WpMenuMapper
{
  public function __construct($menu_name, $depth, $theme_location = '', $walker = '')
  {
    $this->menu_name = $menu_name ? $menu_name : null;
    $this->depth = $depth ? $depth : 0;
    $this->theme_location = $theme_location ? $theme_location : null;
    $this->walker = $walker ? $walker : null;
  }

  public function map()
  {
    return wp_nav_menu(
      [
        'menu' => $this->menu_name,
        'depth' => $this->depth,
        'walker' => $this->walker,
        'theme_location' => $this->theme_location,
        'echo' => false
      ]
    );

  }
}
