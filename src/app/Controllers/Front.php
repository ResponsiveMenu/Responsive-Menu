<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\ViewModels\Menu as MenuViewModel;
use ResponsiveMenu\Factories\FrontDisplayFactory as DisplayFactory;
use ResponsiveMenu\Shortcodes\ResponsiveMenuShortcode as Shortcode;

class Front extends Base
{
	public function index()
	{
    # Get Latest Options
    $options = $this->repository->all();

    # If we want mobile only and we are not on mobile, let's get outta here!
    if($options['mobile_only'] == 'on' && !wp_is_mobile())
      return;

    # This needs refactoring - Martin Fowler HELP!
    $display_factory = new DisplayFactory();
    $display_factory->build($options);

    # Build Our Menu Display
    $menu_display = new MenuViewModel($options);

    # Only load Font Icon Scripts if Needed
    if($options->usesFontIcons())
      wp_enqueue_script('responsive-menu-font-awesome', 'https://use.fontawesome.com/b6bedb3084.js', null, null);

    # Only render if we don't have shortcodes turned on
    if($options['shortcode'] == 'off'):
		  $this->view->render('menu', ['options' => $options, 'menu' => $menu_display->getHtml()]);
	    $this->view->render('button', ['options' => $options]);
    else:
      add_shortcode('responsive_menu', function($atts) use($options, $menu_display) {
        array_walk($atts, function($a, $b) use ($options) { $options[$b] = $a; });
        return $this->view->make('menu', ['options' => $options, 'menu' => $menu_display->getHtml()]) .
  	           $this->view->make('button', ['options' => $options]);
      });
    endif;

	}

}
