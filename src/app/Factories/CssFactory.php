<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Mappers\ScssBaseMapper as ScssBaseMapper;
use ResponsiveMenu\Mappers\ScssButtonMapper as ScssButtonMapper;
use ResponsiveMenu\Mappers\ScssMenuMapper as ScssMenuMapper;
use ResponsiveMenu\Formatters\Minify as Minify;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class CssFactory {

  public function build(OptionsCollection $options) {

    $css_base_mapper = new ScssBaseMapper($options);
    $css_base = $css_base_mapper->map();

    $css_button_mapper = new ScssButtonMapper($options);
    $css_button = $css_button_mapper->map();

    $css_menu_mapper = new ScssMenuMapper($options);
    $css_menu = $css_menu_mapper->map();

    $css = $css_base . $css_button . $css_menu;

    if($options['minify_scripts'] == 'on'):
      $minifier = new Minify;
      $css = $minifier->minify($css);
    endif;

    return $css;

  }

}
