<?php

add_action('plugins_loaded', function() {
  load_plugin_textdomain('responsive-menu', false, basename(dirname(dirname(dirname(__FILE__)))) . '/translations/');
});

if(is_admin()):

  /*
  Polylang Integration Section */
  add_action('plugins_loaded', function() use($container) {
    if(function_exists('pll_register_string')):
      $service = $container['option_service'];
      $options = $service->all();

      $menu_to_use = isset($options['menu_to_use']) ? $options['menu_to_use']->getValue() : '';
      $button_title = isset($options['button_title']) ? $options['button_title']->getValue() : '';
      $menu_title = isset($options['menu_title']) ? $options['menu_title']->getValue() : '';
      $menu_title_link = isset($options['menu_title_link']) ? $options['menu_title_link']->getValue() : '';
      $menu_additional_content = isset($options['menu_additional_content']) ? $options['menu_additional_content']->getValue() : '';

      pll_register_string('menu_to_use', $menu_to_use, 'Responsive Menu');
      pll_register_string('button_title', $button_title, 'Responsive Menu');
      pll_register_string('menu_title', $menu_title, 'Responsive Menu');
      pll_register_string('menu_title_link', $menu_title_link, 'Responsive Menu');
      pll_register_string('menu_additional_content', $menu_additional_content, 'Responsive Menu');
    endif;
  });
endif;
