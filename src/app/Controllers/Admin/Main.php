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
    $flash['success'] = 'Responsive Menu Options Updated Successfully';

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
    $flash['success'] = 'Responsive Menu Options Reset Successfully';

    $this->view->render('main', ['options' => $options, 'flash' => $flash]);

	}

  public function index() {
    $this->view->render('main', ['options' => $this->repository->all()]);
  }

}
