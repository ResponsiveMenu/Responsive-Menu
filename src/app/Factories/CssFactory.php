<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Mappers\ScssBaseMapper;
use ResponsiveMenu\Mappers\ScssButtonMapper;
use ResponsiveMenu\Mappers\ScssMenuMapper;
use ResponsiveMenu\Formatters\Minify;
use ResponsiveMenu\Collections\OptionsCollection;

class CssFactory {

  public function __construct(Minify $minifier, ScssBaseMapper $base, ScssButtonMapper $button, ScssMenuMapper $menu) {
    $this->minifier = $minifier;
    $this->base = $base;
    $this->button = $button;
    $this->menu = $menu;
  }

  public function build(OptionsCollection $options) {

    $css =  $this->base->map($options) . $this->button->map($options) . $this->menu->map($options);

    if($options['minify_scripts'] == 'on')
      $css = $this->minifier->minify($css);

    return $css;

  }

}
