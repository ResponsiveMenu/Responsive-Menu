<?php

namespace ResponsiveMenu\Controllers;

use ResponsiveMenu\Services\OptionService;
use ResponsiveMenu\View\View;
use ResponsiveMenu\ViewModels\Menu;
use ResponsiveMenu\ViewModels\Button;

class Front  {

  public function __construct(OptionService $service, View $view, Menu $menu, Button $button) {
    $this->service = $service;
    $this->view = $view;
    $this->menu = $menu;
    $this->button = $button;
  }

	public function index() {

    $options = $this->service->all();

    $this->view->echoOrIncludeScripts($options);

    if($options['shortcode'] == 'off'):
      $this->view->render('button', ['options' => $options, 'button' => $this->button->getHtml($options)]);
      return $this->view->render('menu', ['options' => $options, 'menu' => $this->menu->getHtml($options)]);
    else:
      return $this->view->addShortcode($options, $this->button, $this->menu);
    endif;

	}

  public function preview() {
    return $this->view->render('preview');
  }

}
