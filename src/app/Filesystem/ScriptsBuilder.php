<?php

namespace ResponsiveMenu\Filesystem;
use ResponsiveMenu\Filesystem\FileCreator;
use ResponsiveMenu\Factories\CssFactory;
use ResponsiveMenu\Factories\JsFactory;
use ResponsiveMenu\Collections\OptionsCollection;

class ScriptsBuilder {

  public function __construct(CssFactory $css, JsFactory $js, FileCreator $creator) {
    $this->css = $css;
    $this->js = $js;
    $this->creator = $creator;
  }

  public function build(OptionsCollection $options) {

      $data_folder_dir = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/responsive-menu-data';

      $js_folder = $data_folder_dir . '/js';
      $css_folder = $data_folder_dir . '/css';

      if(!is_dir($data_folder_dir)):
        mkdir($data_folder_dir);
        mkdir($css_folder);
        mkdir($js_folder);
      endif;

      $this->creator->create($css_folder, 'responsive-menu-' . get_current_blog_id() . '.css', $this->css->build($options));
      $this->creator->create($js_folder, 'responsive-menu-' . get_current_blog_id() . '.js', $this->js->build($options));

  }

}
