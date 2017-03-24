<?php

/*
* Twig builder
*/

$loader = new Twig_Loader_Filesystem([
    dirname(dirname(__FILE__)) . '/public',
    dirname(dirname(__FILE__)) . '/views',
]);

$twig = new Twig_Environment($loader);

$shortcode_filter = new Twig_Filter('shortcode', function($string) {
    return do_shortcode($string);
});

$translate_filter = new Twig_Filter('translate', function($string, $key) {
    // WPML Support
    $translated = apply_filters('wpml_translate_single_string', $string, 'Responsive Menu', $key);

    // Polylang Support
    $translated = function_exists('pll__') ? pll__($translated) : $translated;

    return $translated;
});
$twig->addFilter($shortcode_filter);
$twig->addFilter($translate_filter);

$build_menu = new Twig_Function('build_menu', function($options) use ($translate_filter) {

    $translator = $translate_filter->getCallable();
    $menu = $translator($options['menu_to_use'], 'menu_to_use');

    return wp_nav_menu(
        [
            'container' => '',
            'menu_id' => 'responsive-menu',
            'menu_class' => null,
            'menu' => $menu && !$options['theme_location_menu'] ? $menu : null,
            'depth' => $options['menu_depth'] ? $options['menu_depth'] : 0,
            'theme_location' => $options['theme_location_menu'] ? $options['theme_location_menu'] : null,
            'walker' => new ResponsiveMenuTest\Walkers\Walker($options),
            'echo' => false
        ]
    );

});

$twig->addFunction($build_menu);
$twig->addGlobal('search_url', function_exists('icl_get_home_url') ? icl_get_home_url() : get_home_url());

return $twig;