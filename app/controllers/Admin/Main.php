<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Factories\OptionFactory as Option;
use ResponsiveMenu\Factories\SaveFactory as SaveFactory;

class Main extends Base
{

	public function update($default_options)
	{

    $options = array_merge($default_options, $_POST['menu']);

    foreach($options as $key => $val):
      $option = new Option();
      $option = $option->build($key, $val);
      $this->repository->update($option);
    endforeach;

    $options = $this->repository->all();
    $save_factory = new SaveFactory();
    $flash['errors'] = $save_factory->build($options);
    $flash['success'] = 'Responsive Menu Options Updated Successfully';

    $this->view->render('main', $options, $flash);

	}

	public function reset($default_options)
	{

    foreach($default_options as $key => $val):
      $option = new Option();
      $option = $option->build($key, $val);
      $this->repository->update($option);
    endforeach;

    $options = $this->repository->all();
    $save_factory = new SaveFactory();
    $flash['errors'] = $save_factory->build($options);
    $flash['success'] = 'Responsive Menu Options Reset Successfully';

    $this->view->render('main', $options, $flash);

	}

  public function index() {
    $this->view->render('main', $this->repository->all());
  }

}
