<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\ViewModels\Menu as MenuViewModel;
use ResponsiveMenu\ViewModels\Button as ButtonViewModel;
use ResponsiveMenu\ViewModels\HeaderBar as HeaderBarViewModel;
use ResponsiveMenu\Factories\FrontDisplayFactory as DisplayFactory;
use ResponsiveMenu\Shortcodes\ResponsiveMenuShortcode as Shortcode;

class Front extends Base
{
	public function index()
	{
    # Get Latest Options
    $options = $this->repository->all();

    # This needs refactoring - Martin Fowler HELP!
    $display_factory = new DisplayFactory();
    $display_factory->build($options);

    # Build Our Menu Display
    $menu = new MenuViewModel($options);
    $html = new ButtonViewModel($options);

    # Only render if we don't have shortcodes turned on
    if($options['shortcode'] == 'off'):
      $this->view->render('button', ['options' => $options, 'button' => $html->getHtml()]);
      $this->view->render('menu', ['options' => $options, 'menu' => $menu->getHtml()]);
    else:
      add_shortcode('responsive_menu', function($atts) use($options, $html, $menu) {

        array_walk($atts, function($a, $b) use ($options) { $options[$b] = $a; });

        $this->view->make('button', ['options' => $options, 'button' => $html->getHtml()]);

        return $html . $this->view->make('menu', ['options' => $options, 'menu' => $menu->getHtml()]);

      });
    endif;

	}

}
