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
    return "mapped";
  }
  
}
