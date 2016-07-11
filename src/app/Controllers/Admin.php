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
    $this->view->render('main', [
      'options' => $this->service->updateOptions(array_merge($default_options, array_filter($new_options))),
      'flash' => ['success' =>  __('Responsive Menu Options Updated Successfully', 'responsive-menu')]
    ]);
	}

	public function reset($default_options) {
    $this->view->render('main', [
      'options' => $this->service->updateOptions($default_options),
      'flash' => ['success' => __('Responsive Menu Options Reset Successfully', 'responsive-menu')]
    ]);
	}

  public function index() {
    $this->view->render('main', ['options' => $this->service->all()]);
  }

  public function import($default_options, $file) {
    if(!empty($file['tmp_name'])):
      $file = file_get_contents($file['tmp_name']);
      $decoded = json_decode($file);
      $options = $this->service->updateOptions(array_merge($default_options, array_filter($decoded)));
      $flash['success'] = __('Responsive Menu Options Reset Successfully', 'responsive-menu');
    else:
      $flash['errors'][] = __('No file selected', 'responsive-menu');
      $options = $this->service->all();
    endif;

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);
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
