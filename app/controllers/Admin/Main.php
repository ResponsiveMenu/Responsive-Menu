<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\Controllers\Base as Base;
use ResponsiveMenu\Mappers\CssMapper as CssMapper;
use ResponsiveMenu\Mappers\JsMapper as JsMapper;
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
        $null_to_defaults[$key] = $val ? $val : $default_options[$key];

      # Update All Options
			$this->repository->updateMany($null_to_defaults);

      # Create Data Folders if They don't exist
      $data_folder_dir = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/responsive-menu-3-data';
      $js_folder = $data_folder_dir . '/js';
      $css_folder = $data_folder_dir . '/css';
      if($null_to_defaults['external_files'] == 'on' && !is_dir($data_folder_dir)):
        if(!mkdir($data_folder_dir)) $flash['errors'][] = 'Unable to make data directory';
        if(!mkdir($css_folder))  $flass['errors'][] = 'Unable to make CSS data directory';
        if(!mkdir($js_folder))  $flash['errors'][] = 'Unable to make JS data directory';
      endif;

      # Pass options to CSS and JS creation scripts
      if($null_to_defaults['external_files'] == 'on'):

        # Map Options to CSS
        $css_mapper = new CssMapper($null_to_defaults);
        $css = $css_mapper->map();

        # Map Options to Javascript
        $js_mapper = new JsMapper($null_to_defaults);
        $js = $js_mapper->map();

        # Create CSS and Javascript files
        $file_creator = new FileCreator;
        
        if(!$file_creator->createCssFile($css_folder, 'responsive-menu-' . get_current_blog_id(), $css))
          $flash['errors'][] = 'Unable to create CSS file';

        if(!$file_creator->createJsFile($js_folder, 'responsive-menu-' . get_current_blog_id(), $js))
          $flash['errors'][] = 'Unable to create JS file';

      endif;

      $flash['success'] = 'Responsive Menu Options Updates Successfully';

    endif;

		$this->view->render('main', $this->repository->all(), $flash);

	}

}
