<?php

namespace ResponsiveMenu\Mappers;

class WpMenuMapper
{
  public function __construct($menu_name, $depth, $theme_location = '', $walker = '', $active_arrow = '', $inactive_arrow = '')
  {
    $this->menu_name = $menu_name ? $menu_name : null;
    $this->depth = $depth ? $depth : 0;
    $this->theme_location = $theme_location ? $theme_location : null;
    $this->walker = $walker ? $walker : null;
    $this->arrows = ['active' => $active_arrow, 'inactive' => $inactive_arrow];
  }

  public function map()
  {
    return wp_nav_menu(
      [
        'container' => '',
        'menu_id' => 'responsive-menu',
        'menu_class' => null,
        'menu' => $this->menu_name,
        'depth' => $this->depth,
        'theme_location' => $this->theme_location,
        'walker' => new $this->walker($this->arrows),
        'echo' => false
      ]
    );
  }
}
