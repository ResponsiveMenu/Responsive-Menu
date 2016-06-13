<?php

namespace ResponsiveMenu\ViewModels;

use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class HeaderBar {

  public function __construct(OptionsCollection $options) {
      $this->options = $options;
  }

  public function getHtml() {
    $mapping = [
      'logo' => 'ResponsiveMenu\ViewModels\Components\Header\Logo',
      'title' => 'ResponsiveMenu\ViewModels\Components\Header\Title',
      'search' => 'ResponsiveMenu\ViewModels\Components\Header\Search',
      'html content' => 'ResponsiveMenu\ViewModels\Components\Header\HtmlContent',
      'button' => 'ResponsiveMenu\ViewModels\Components\Button\Button'
    ];
    $content = '';

    foreach(json_decode($this->options['header_bar_items_order']) as $key => $val):
      if($val == 'on'):
        $mapper = new $mapping[$key];
        $content .= $mapper->render($this->options);
      endif;
    endforeach;
    return $content;
  }

}
