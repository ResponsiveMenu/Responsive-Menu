<?php

namespace ResponsiveMenu\Formatters;

class Minifier {

    public static function minify($data) {

        /* remove comments */
        $minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $data);

        /* remove tabs, spaces, newlines, etc. */
        $minified = str_replace(array("\r\n","\r","\n","\t",'  ','    ','     '), '', $minified);

        /* remove other spaces before/after ; */
        $minified = preg_replace(array('(( )+{)','({( )+)'), '{', $minified);
        $minified = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $minified);
        $minified = preg_replace(array('(;( )+)','(( )+;)'), ';', $minified);

        return $minified;

    }

}
