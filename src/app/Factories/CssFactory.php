<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Mappers\ScssBaseMapper as ScssBaseMapper;
use ResponsiveMenu\Mappers\ScssButtonMapper as ScssButtonMapper;
use ResponsiveMenu\Mappers\ScssMenuMapper as ScssMenuMapper;
use ResponsiveMenu\Mappers\ScssHeaderBarMapper as ScssHeaderBarMapper;
use ResponsiveMenu\Mappers\ScssSingleMenuMapper as ScssSingleMenuMapper;
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

    if($options['use_header_bar'] == 'on'):
      $css_header_bar_mapper = new ScssHeaderBarMapper($options);
      $css_header_bar = $css_header_bar_mapper->map();
    else:
      $css_header_bar = '';
    endif;

    if($options['use_single_menu'] == 'on'):
      $css_single_menu_mapper = new ScssSingleMenuMapper($options);
      $css_single_menu = $css_single_menu_mapper->map();
    else:
      $css_single_menu = '';
    endif;

    $css = $css_base . $css_button . $css_menu . $css_header_bar . $css_single_menu . $options['custom_css'];

    if($options['minify_scripts'] == 'on'):
      $minifier = new Minify;
      $css = $minifier->minify($css);
    endif;

    return $css;

  }

}
