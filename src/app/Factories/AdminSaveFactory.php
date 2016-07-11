<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Filesystem\FileCreator as FileCreator;
use ResponsiveMenu\Factories\CssFactory as CssFactory;
use ResponsiveMenu\Factories\JsFactory as JsFactory;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class AdminSaveFactory {

  public function build(OptionsCollection $options) {

      $flash = [];

      $css_factory = new CssFactory;
      $js_factory = new JsFactory;
      $file_creator = new FileCreator;

      # Create Data Folders if They don't exist
      $data_folder_dir = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/responsive-menu-data';
      $js_folder = $data_folder_dir . '/js';
      $css_folder = $data_folder_dir . '/css';
      if($options['external_files'] == 'on' && !is_dir($data_folder_dir)):
        if(!mkdir($data_folder_dir)) $flash['errors'][] = __('Unable to make data directory', 'responsive-menu');
        if(!mkdir($css_folder))  $flass['errors'][] = __('Unable to make CSS data directory', 'responsive-menu');
        if(!mkdir($js_folder))  $flash['errors'][] = __('Unable to make JS data directory', 'responsive-menu');
      endif;

      $css = $css_factory->build($options);
      $js = $js_factory->build($options);

      if(!$file_creator->createCssFile($css_folder, 'responsive-menu-' . get_current_blog_id() . '.css', $css))
        $flash['errors'][] = __('Unable to create CSS file', 'responsive-menu');

      if(!$file_creator->createJsFile($js_folder, 'responsive-menu-' . get_current_blog_id() . '.js', $js))
        $flash['errors'][] = __('Unable to create JS file', 'responsive-menu');

      return empty($flash) ? null : $flash;

  }

}
