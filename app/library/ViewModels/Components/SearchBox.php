<?php

namespace ResponsiveMenu\ViewModels\Components;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class SearchBox implements ViewComponent {

  public function render(OptionsCollection $collection) {

    if($collection->get('menu_remove_search_box'])->getValue() != 'on'):
      echo '<div id="responsive-menu-search-box">
        <?php $action = function_exists( 'icl_get_home_url' ) ? icl_get_home_url() : get_home_url(); ?>
        <form action="<?php echo $action; ?>" class="responsive-menu-search-form" role="search">
          <input type="search" name="s" placeholder="' . $collection->get('menu_search_box_text') .>' class="responsive-menu-search-box">
        </form>
      </div>';
    endif;
    
  }

}
