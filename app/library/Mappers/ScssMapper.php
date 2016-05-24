<?php

namespace ResponsiveMenu\Mappers;

class ScssMapper
{

  public function __construct(array $options)
  {
    $this->options = $options;
    #Ugly
    require "scssphp/scss.inc.php";
    $this->compiler = new \scssc();
  }

}
