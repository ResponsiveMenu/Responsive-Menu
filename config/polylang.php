<?php

add_action('plugins_loaded', function() {
    load_plugin_textdomain('responsive-menu', false, basename(dirname(dirname(dirname(__FILE__)))) . '/translations/');
});

if(is_admin()):
    add_action('plugins_loaded', function() {
        if(function_exists('pll_register_string')):
            $options = get_responsive_menu_service('option_manager')->all();
            pll_register_string('menu_to_use', $options['menu_to_use'], 'Responsive Menu');
            pll_register_string('button_title', $options['button_title'], 'Responsive Menu');
            pll_register_string('menu_title', $options['menu_title'], 'Responsive Menu');
            pll_register_string('menu_title_link', $options['menu_title_link'], 'Responsive Menu');
            pll_register_string('menu_additional_content', $options['menu_additional_content'], 'Responsive Menu');
        endif;
    });
endif;
