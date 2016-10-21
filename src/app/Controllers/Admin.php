<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View;
use ResponsiveMenu\Services\OptionService;

class Admin {

  public function __construct(OptionService $service, View $view) {
		$this->service = $service;
		$this->view = $view;
	}

	public function update($default_options, $new_options) {

    update_option('responsive_menu_current_page', $new_options['responsive_menu_current_page']);
    unset($new_options['responsive_menu_current_page']);

    $updated_options = $this->service->combineOptions($default_options, $new_options);
    return $this->view->render('main', [
      'options' => $this->service->updateOptions($updated_options),
      'flash' => ['success' =>  __('Responsive Menu Options Updated Successfully', 'responsive-menu')]
    ]);
	}

	public function reset($default_options) {
    return $this->view->render('main', [
      'options' => $this->service->updateOptions($default_options),
      'flash' => ['success' => __('Responsive Menu Options Reset Successfully', 'responsive-menu')]
    ]);
	}

  public function index() {
    return $this->view->render('main', ['options' => $this->service->all()]);
  }

  public function import($default_options, $imported_options) {

    if(!empty($imported_options)):
      $updated_options = $this->service->combineOptions($default_options, $imported_options);
      $options = $this->service->updateOptions($updated_options);
      $flash['success'] = __('Responsive Menu Options Imported Successfully', 'responsive-menu');
    else:
      $flash['errors'][] = __('No file selected', 'responsive-menu');
      $options = $this->service->all();
    endif;

    return $this->view->render('main', ['options' => $options, 'flash' => $flash]);
  }

  public function export() {
    $this->view->noCacheHeaders();
    $final = [];
    foreach($this->service->all()->all() as $option)
      $final[$option->getName()] = $option->getValue();
    $this->view->display(json_encode($final));
    $this->view->stopProcessing();
  }

}
