<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\Controllers\Base as Base;

class Main extends Base
{
	public function index()
	{
		if(isset($_POST['responsive_menu_submit'])):
      # This is Dirty and should be injected
      include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';

      $combined_options = array_merge($default_options, $_POST['menu']);
      
      $null_to_defaults = [];
      foreach($combined_options as $key => $val)
        $null_to_defaults[$key] = $val ? $val : $default_options[$key];

			$this->update($null_to_defaults);
    endif;

		$this->view->render('main', $this->repository->all());
	}

	public function update($options)
	{
		$this->repository->updateMany($options);
	}

}
