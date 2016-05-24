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
        $('.hamburger').click(function(){
          $(this).toggleClass('is-active');
        });
      });
JS;

  return $js;

  }

}
