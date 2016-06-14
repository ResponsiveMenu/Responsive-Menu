<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\Factories\OptionFactory as OptionFactory;
use ResponsiveMenu\Factories\AdminSaveFactory as SaveFactory;

class Main extends Base
{

	public function update($default_options)
	{

    $options = array_merge($default_options, $_POST['menu']);

    foreach($options as $key => $val):
      $option_factory = new OptionFactory;
      $option = $option_factory->build($key, $val);
      $this->repository->update($option);
    endforeach;

    $options = $this->repository->all();
    $save_factory = new SaveFactory();
    $flash['errors'] = $save_factory->build($options);
    $flash['success'] = __('Responsive Menu Options Updated Successfully', 'responsive-menu');

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);

	}

	public function reset($default_options)
	{

    foreach($default_options as $key => $val):
      $option_factory = new OptionFactory;
      $option = $option_factory->build($key, $val);
      $this->repository->update($option);
    endforeach;

    $options = $this->repository->all();
    $save_factory = new SaveFactory();
    $flash['errors'] = $save_factory->build($options);
    $flash['success'] = __('Responsive Menu Options Reset Successfully', 'responsive-menu');

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);

	}

  public function index() {
    $this->view->render('main', ['options' => $this->repository->all()]);
  }

  public function import() {

    if(!empty($_FILES['responsive_menu_import_file']['tmp_name'])):
      $file = file_get_contents($_FILES['responsive_menu_import_file']['tmp_name']);
      $decoded = json_decode($file);
      foreach($decoded as $key => $val):
        $option_factory = new OptionFactory;
        $option = $option_factory->build($key, $val);
        $this->repository->update($option);
      endforeach;

      $options = $this->repository->all();
      $save_factory = new SaveFactory();
      $flash['errors'] = $save_factory->build($options);
      $flash['success'] = __('Responsive Menu Options Reset Successfully', 'responsive-menu');
    else:
      $flash['errors'][] = __('No file selected', 'responsive-menu');
      $options = $this->repository->all();
    endif;

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
