<?php

$twig = new Twig_Environment(new Twig_Loader_Filesystem([
    dirname(dirname(__FILE__)) . '/views',
    dirname(dirname(__FILE__)) . '/public',
]), ['autoescape' => false, 'debug' => false]);

if(!is_admin()):

    $twig->addFilter(new Twig_SimpleFilter('shortcode', function($string) {
        return do_shortcode($string);
    }));

    $twig->addFilter(new Twig_SimpleFilter('translate', function($string, $key) {
        $translated = apply_filters('wpml_translate_single_string', $string, 'Responsive Menu', $key);
        $translated = function_exists('pll__') ? pll__($translated) : $translated;
        return $translated;
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

else:

    $twig->addFunction(new Twig_SimpleFunction('csrf', function() {
        return wp_nonce_field('update', 'responsive-menu-nonce', true, false);
    }));

    $twig->addFunction(new Twig_SimpleFunction('current_page', function() {
        return get_option('responsive_menu_current_page', 'initial-setup');
    }));

    $twig->addFunction(new Twig_SimpleFunction('header_bar_items', function($items) {
        if(isset($items['button']))
            unset($items['button']);
        return $items;
    }));

    $twig->addFunction(new Twig_SimpleFunction('menu_items', function($options) {

        if($options['theme_location_menu'])
            $menu = get_term(get_nav_menu_locations()[$options['theme_location_menu']], 'nav_menu')->name;
        elseif($options['menu_to_use'])
            $menu = $options['menu_to_use'];
        else
            $menu = get_terms('nav_menu')[0]->slug;

        return wp_get_nav_menu_items($menu);
    }));

    $twig->addFunction(new Twig_SimpleFunction('all_pages', function() {
        return get_pages();
    }));

    $twig->addFunction(new Twig_SimpleFunction('font_icons', function($array) {
        $new_array = [];
        for($i=0; $i < count($array['id']); $i++):
            $new_array[$i] = [
                'id' => $array['id'][$i],
                'icon' => $array['icon'][$i],
                'type' => $array['type'][$i]
            ];
        endfor;
        return $new_array;
    }));

    $twig->addGlobal('admin_url', get_admin_url());
    $twig->addGlobal('shortcode', '[responsive_menu]');

    $twig->addFunction(new Twig_SimpleFunction('get_available_themes', function() {
        $theme_folder_path = wp_upload_dir()['basedir'] . '/responsive-menu-themes';
        $theme_folders = glob($theme_folder_path . '/*' , GLOB_ONLYDIR);

        $themes = [];
        foreach($theme_folders as $theme_folder):
            $config = json_decode(file_get_contents($theme_folder . '/config.json'), true);
            $themes[basename($theme_folder)]['version'] = $config['version'];
            $themes[basename($theme_folder)]['name'] = $config['name'];
        endforeach;

        return $themes;
    }));

    $twig->addGlobal('themes_folder_url', wp_upload_dir()['baseurl'] . '/responsive-menu-themes/');
    $twig->addGlobal('themes_folder_dir', wp_upload_dir()['basedir'] . '/responsive-menu-themes/');

    $twig->addFunction(new Twig_SimpleFunction('hide_pro_options', function() {
        return get_option('hide_pro_options', 'no');
    }));

    $twig->addFunction(new Twig_SimpleFunction('nav_menus', function() {
        $menus_array = [];
        foreach(get_terms('nav_menu') as $menu)
            $menus_array[$menu->slug] = $menu->name;

        return $menus_array;
    }));

    $twig->addFunction(new Twig_SimpleFunction('location_menus', function() {
        $location_menus = ['' => 'None'];
        foreach(get_registered_nav_menus() as $location => $menu)
            $location_menus[$location] = $menu;

        return $location_menus;
    }));

endif;

$twig->addFilter(new Twig_SimpleFilter('json_decode', function($string) {
    return json_decode($string, true);
}));

return $twig;