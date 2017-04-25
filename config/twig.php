<?php

$twig = new Twig_Environment(new Twig_Loader_Filesystem([
    dirname(dirname(__FILE__)) . '/views',
    dirname(dirname(__FILE__)) . '/public',
]), ['autoescape' => false]);

$twig->addFilter(new Twig_SimpleFilter('shortcode', function($string) {
    return do_shortcode($string);
}));

$twig->addFilter(new Twig_SimpleFilter('translate', function($string, $key) {
    $translated = apply_filters('wpml_translate_single_string', $string, 'Responsive Menu', $key);
    $translated = function_exists('pll__') ? pll__($translated) : $translated;
    return $translated;
}));

$twig->addFilter(new Twig_SimpleFilter('json_decode', function($string) {
    return json_decode($string, true);
}));

$twig->addFunction(new Twig_SimpleFunction('header_bar_items', function($items) {
    if(isset($items['button']))
        unset($items['button']);
    return $items;
}));

$twig->addFunction(new Twig_SimpleFunction('build_menu', function($env, $options) {

    $translator = $env->getFilter('translate')->getCallable();
    $menu = $translator($options['menu_to_use'], 'menu_to_use');
    $walker = $options['custom_walker'] ? new $options['custom_walker']($options) : new ResponsiveMenu\Walkers\Walker($options);

    return wp_nav_menu(
        [
            'container' => '',
            'menu_id' => 'responsive-menu',
            'menu_class' => null,
            'menu' => $menu && !$options['theme_location_menu'] ? $menu : null,
            'depth' => $options['menu_depth'] ? $options['menu_depth'] : 0,
            'theme_location' => $options['theme_location_menu'] ? $options['theme_location_menu'] : null,
            'walker' => $walker,
            'echo' => false
        ]
    );

}, ['needs_environment' => true]));

$twig->addGlobal('search_url', function_exists('icl_get_home_url') ? icl_get_home_url() : get_home_url());
$twig->addGlobal('admin_url', get_admin_url());
$twig->addFunction(new Twig_SimpleFunction('current_page', function() {
    return get_option('responsive_menu_current_page', 'initial-setup');
}));
return $twig;