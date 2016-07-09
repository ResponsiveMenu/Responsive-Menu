<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\Factories\OptionFactory as OptionFactory;
use ResponsiveMenu\Factories\AdminSaveFactory as SaveFactory;
use ResponsiveMenu\WPML\WPML as WPML;

class Main extends Base {

	public function update($default_options) {

    $options = array_merge($default_options, $_POST['menu']);

    $option_factory = new OptionFactory;
    foreach($options as $key => $val)
      $this->repository->update($option_factory->build($key, $val));

    $options = $this->repository->all();
    $save_factory = new SaveFactory();
    $flash['errors'] = $save_factory->build($options);
    $flash['success'] = __('Responsive Menu Options Updated Successfully', 'responsive-menu');

    $wpml = new WPML;
    $wpml->saveFromOptions($options);

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);

	}

	public function reset($default_options) {

    $option_factory = new OptionFactory;
    foreach($options as $key => $val)
      $this->repository->update($option_factory->build($key, $val));

    $options = $this->repository->all();
    $save_factory = new SaveFactory();
    $flash['errors'] = $save_factory->build($options);
    $flash['success'] = __('Responsive Menu Options Reset Successfully', 'responsive-menu');

    $wpml = new WPML;
    $wpml->saveFromOptions($options);

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);

	}

  public function index() {
    $this->view->render('main', ['options' => $this->repository->all()]);
  }

  public function import() {

    if(!empty($_FILES['responsive_menu_import_file']['tmp_name'])):
      $file = file_get_contents($_FILES['responsive_menu_import_file']['tmp_name']);
      $decoded = json_decode($file);

      $option_factory = new OptionFactory;
      foreach($options as $key => $val)
        $this->repository->update($option_factory->build($key, $val));

      $options = $this->repository->all();
      $save_factory = new SaveFactory();
      $flash['errors'] = $save_factory->build($options);
      $flash['success'] = __('Responsive Menu Options Reset Successfully', 'responsive-menu');
    else:
      $flash['errors'][] = __('No file selected', 'responsive-menu');
      $options = $this->repository->all();
    endif;

    $wpml = new WPML;
    $wpml->saveFromOptions($options);

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);
  }

  public function export() {
    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=export.json' );
    header( "Expires: 0" );
    $final = [];
    foreach($this->repository->all()->all() as $option)
      $final[$option->getName()] = $option->getValue();
    echo json_encode($final);
    exit();
  }

}
