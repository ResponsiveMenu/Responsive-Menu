<?php

namespace ResponsiveMenu\Factories;
use ResponsiveMenu\Mappers\JsMapper as JsMapper;
use ResponsiveMenu\Formatters\Minify as Minify;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class JsFactory {

  public function build(OptionsCollection $options) {

    $js_mapper = new JsMapper($options);
    $js = $js_mapper->map();

    if($options['minify_scripts'] == 'on'):
      $minifier = new Minify;
      $js = $minifier->minify($js);
    endif;

    return $js;

  }
}
