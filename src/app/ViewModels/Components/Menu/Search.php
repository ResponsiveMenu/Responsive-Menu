<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Search implements ViewComponent {

  public function render(OptionsCollection $options) {

    if($options['menu_remove_search_box'] != 'on'):
      $action = function_exists( 'icl_get_home_url' ) ? icl_get_home_url() : get_home_url();
      return '<div id="responsive-menu-search-box">
        <form action="'.$action.'" class="responsive-menu-search-form" role="search">
          <input type="search" name="s" placeholder="' . $options['menu_search_box_text'] . '" class="responsive-menu-search-box">
        </form>
      </div>';
    endif;

  }

}
