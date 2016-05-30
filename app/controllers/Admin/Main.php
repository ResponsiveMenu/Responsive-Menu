<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Filesystem\FileCreator as FileCreator;

class Main extends Base
{
	public function index()
	{
    $flash = [];

		if(isset($_POST['responsive_menu_submit'])):

      # This is Dirty and should be injected
      include dirname(dirname(dirname(__FILE__))) . '/config/default_options.php';
      $combined_options = array_merge($default_options, $_POST['menu']);

      # Again there must be a better way
      $null_to_defaults = [];
      foreach($combined_options as $key => $val)
        $null_to_defaults[$key] = $val || $val === '0' ? $val : $default_options[$key];

      # Update All Options
			$this->repository->updateMany($null_to_defaults);

      if($null_to_defaults['external_files'] == 'on'):

        # Create Data Folders if They don't exist
        $data_folder_dir = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/responsive-menu-3-data';
        $js_folder = $data_folder_dir . '/js';
        $css_folder = $data_folder_dir . '/css';
        if($null_to_defaults['external_files'] == 'on' && !is_dir($data_folder_dir)):
          if(!mkdir($data_folder_dir)) $flash['errors'][] = 'Unable to make data directory';
          if(!mkdir($css_folder))  $flass['errors'][] = 'Unable to make CSS data directory';
          if(!mkdir($js_folder))  $flash['errors'][] = 'Unable to make JS data directory';
        endif;

        $css = $this->css_factory->build($null_to_defaults);
        $js = $this->js_factory->build($null_to_defaults);

        # Create CSS and Javascript files
        $file_creator = new FileCreator;

        if(!$file_creator->createCssFile($css_folder, 'responsive-menu-' . get_current_blog_id() . '.css', $css))
          $flash['errors'][] = 'Unable to create CSS file';

        if(!$file_creator->createJsFile($js_folder, 'responsive-menu-' . get_current_blog_id() . '.js', $js))
          $flash['errors'][] = 'Unable to create JS file';

      endif;

      $flash['success'] = 'Responsive Menu Options Updates Successfully';

    endif;

		$this->view->render('main', $this->repository->all(), $flash);

	}

}
