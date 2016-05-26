<?php

namespace ResponsiveMenu\Mappers;

class ScssMapper
{

  public function __construct(array $options)
  {
    $this->options = $options;
    #Ugly
    require_once "scssphp/scss.inc.php";
    $this->compiler = new \scssc();
  }

}
