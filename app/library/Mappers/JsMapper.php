<?php

namespace ResponsiveMenu\Mappers;

class JsMapper
{
  public function __construct(array $options)
  {
    $this->options = $options;
  }

  public function map()
  {
$js = <<<JS
  jQuery(document).ready(function($) {
    $('#responsive-menu-button-holder').click(function(){
      $('#responsive-menu-button-holder a.navicon-button').toggleClass('open');
    });
  });
JS;

  return $js;

  }

}
