<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Services\OptionService as OptionService;

class Admin {

  public function __construct(OptionService $service, View $view) {
		$this->service = $service;
		$this->view = $view;
	}

	public function update($default_options, $new_options) {
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

  public function import($default_options, $file) {
    if(!empty($file['tmp_name'])):
      $file = file_get_contents($file['tmp_name']);
      $decoded = (array) json_decode($file);
      $updated_options = $this->service->combineOptions($default_options, $decoded);
      $options = $this->service->updateOptions($updated_options);
      $flash['success'] = __('Responsive Menu Options Imported Successfully', 'responsive-menu');
    else:
      $flash['errors'][] = __('No file selected', 'responsive-menu');
      $options = $this->service->all();
    endif;

    return $this->view->render('main', ['options' => $options, 'flash' => $flash]);
  }

  public function export() {
    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=export.json' );
    header( "Expires: 0" );
    $final = [];
    foreach($this->service->all()->all() as $option)
      $final[$option->getName()] = $option->getValue();
    echo json_encode($final);
    exit();
  }

}
