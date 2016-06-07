<?php

// Ugly - is there a functional way?
foreach(get_terms('nav_menu') as $menu) $available_menus[$menu->slug] = $menu->name;
$location_menus = get_registered_nav_menus();
$location_menus[''] = 'None';

$option_helpers = array(

	'breakpoint' => array(
		'position' => 'initial_setup.initial',
	),

	'menu_to_hide' => array(
		'position' => 'initial_setup.initial',
	),

	'menu_to_use' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'initial_setup.initial',
    'custom' => array('select'=> $available_menus)
	),

	'theme_location_menu' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.advanced',
    'custom' => array('select'=> $location_menus )
	),

	'menu_depth' => array(
		'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.advanced',
    'custom' => array('select' => array_combine(range(1,5),range(1,5)))
	),

	'menu_minimum_width' => array(
		'position' => 'menu.advanced',
	),

	'menu_maximum_width' => array(
		'position' => 'menu.advanced',
	),

	'menu_auto_height' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_remove_search_box' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.general',
	),

	'menu_search_box_text' => array(
		'position' => 'menu.general',
	),

	'menu_word_wrap' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_font_icons' => array(
    'form_component' => 'ResponsiveMenu\Form\FontIconPageList',
    'filter' => 'ResponsiveMenu\Filters\JsonFilter',
		'position' => 'menu.font_icons',
    'pro' => true
	),

	'menu_disable_scrolling' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_overlay' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_overlay_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_additional_content' => array(
    'filter' => 'ResponsiveMenu\Filters\HtmlFilter',
    'form_component' => 'ResponsiveMenu\Form\Textarea',
		'position' => 'menu.advanced',
	),

	'menu_additional_content_position' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.advanced',
    'custom' => array('select' => array(
      'below' => 'Below Menu Links',
      'above' => ' Above Menu Links')
    )
	),

	'menu_title' => array(
		'position' => 'menu.general'
	),

	'menu_title_image' => array(
    'form_component' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.general'
	),

	'menu_title_font_icon' => array(
		'position' => 'menu.general',
    'pro' => true
	),

	'menu_appear_from' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.general',
    'custom' => array('select' => array(
      'left' => 'Left',
      'right' => 'Right',
      'top' => 'Top',
      'bottom' => 'Bottom'
    )
    )
	),

	'menu_title_link' => array(
		'position' => 'menu.general'
	),

	'menu_title_link_location' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.general',
    'custom' => array('select' => array(
      '_blank' => 'New Tab',
      '_self' => 'Same Page',
      '_parent' => 'Parent Page',
      '_top' => 'Full Window Body')
    )
	),

	'menu_width' => array(
		'position' => 'menu.general'
	),

	'menu_close_on_link_click' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced'
	),

	'menu_item_click_to_trigger_submenu' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced'
	),

  'minify_scripts' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.scripts',
	),

  'scripts_in_footer' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.scripts',
	),

  'external_files' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.scripts',
	),

  /*
  Button Related Settings
  */
  'button_title' => array(
		'position' => 'button.general'
	),

  'button_image' => array(
    'form_component' => 'ResponsiveMenu\Form\Image',
		'position' => 'button.general'
	),

  'button_image_when_clicked' => array(
    'form_component' => 'ResponsiveMenu\Form\Image',
    'position' => 'button.general'
  ),

  'button_font' => array(
    'position' => 'button.general',
    'pro' => true
  ),

  'button_font_icon' => array(
    'position' => 'button.general',
    'pro' => true
  ),

  'button_font_icon_when_clicked' => array(
    'position' => 'button.general',
    'pro' => true
  ),

  'button_title_position' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
    'position' => 'button.general',
    'custom' => array('select' => array(
      'left' => 'Left',
      'right' => 'Right',
      'top' => 'Top',
      'bottom' => 'Bottom')
    )
  ),

  'button_title_line_height' => array(
    'position' => 'button.general'
  ),

  'button_click_animation' => array(
    'position' => 'button.general',
    'form_component' => 'ResponsiveMenu\Form\Select',
    'custom' => array('select' => array(
      'off' => 'Off',
      '3dx' => '3DX',
      '3dx-r' => '3DX Reverse',
      '3dy' => '3DY',
      '3dy-r' => '3DY Reverse',
      'arrow' => 'Arrow',
      'arrow-r' => 'Arrow Reverse',
      'arrowalt' => 'Arrow Alt',
      'arrowalt-r' => 'Arrow Alt Reverse',
      'boring' => 'Boring',
      'collapse' => 'Collapse',
      'collapse-r' => 'Collapse Reverse',
      'elastic' => 'Elastic',
      'elastic-r' => 'Elastic Reverse',
      'emphatic' => 'Emphatic',
      'emphatic-r' => 'Emphatic Reverse',
      'slider' => 'Slider',
      'slider-r' => 'Slider Reverse',
      'spin' => 'Spin',
      'spin-r' => 'Spin Reverse',
      'spring' => 'Spring',
      'spring-r' => 'Spring Reverse',
      'stand' => 'Stand',
      'stand-r' => 'Stand Reverse',
      'squeeze' => 'Squeeze',
      'vortex' => 'Vortex',
      'vortex-r' => 'Vortex Reverse'
    )),
    'pro' => true
  ),

	'button_background_colour' => array(
		'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'button.colours'
	),

	'button_line_colour' => array(
		'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'button.colours'
	),

	'button_text_colour' => array(
		'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'button.colours'
	),

	'button_transparent_background' => array(
		'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'button.colours'
	),

	'button_width' => array(
		'position' => 'button.sizing'
	),

  'button_height' => array(
		'position' => 'button.sizing'
	),

  'button_line_margin' => array(
		'position' => 'button.sizing'
	),

  'button_line_height' => array(
		'position' => 'button.sizing'
	),

  'button_line_width' => array(
		'position' => 'button.sizing'
	),

  'button_top' => array(
		'position' => 'button.location'
	),

  'button_distance_from_side' => array(
		'position' => 'button.location'
	),

  'button_left_or_right' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'button.location',
    'custom' => array('select' => array(
      'left' => 'Left',
      'right' => 'Right')
    )
	),

  'button_position_type' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
    'position' => 'button.advanced',
    'custom' => array('select' => array(
      'absolute' => 'Static',
      'fixed' => 'Fixed')
    )
  ),

  'button_push_with_animation' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'button.advanced'
	),

  'button_click_trigger' => array(
		'position' => 'button.advanced'
	),

  'button_font_size' => array(
		'position' => 'button.sizing'
	),

  /* Animation Settings */
	'animation_type' => array(
		'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'animation.menu',
    'custom' => array('select' => array(
      'slide' => 'Slide',
      'push' => 'Push')
    )
	),

	'page_wrapper' => array(
		'position' => 'animation.menu'
	),

  'animation_speed' => array(
		'position' => 'animation.menu'
	),

  'transition_speed' => array(
		'position' => 'animation.menu'
	),

  /* Menu Settings */
  'active_arrow_shape' => array(
		'position' => 'menu.submenus'
	),

  'inactive_arrow_shape' => array(
		'position' => 'menu.submenus'
	),

  'active_arrow_image' => array(
    'form_component' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.submenus',
    'pro' => true
	),

  'inactive_arrow_image' => array(
    'form_component' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.submenus',
    'pro' => true
	),

  'active_arrow_font_icon' => array(
		'position' => 'menu.submenus',
    'pro' => true
	),

  'inactive_arrow_font_icon' => array(
		'position' => 'menu.submenus',
    'pro' => true
	),

  'submenu_arrow_width' => array(
		'position' => 'menu.submenus',
	),

  'submenu_arrow_height' => array(
		'position' => 'menu.submenus',
	),

  'accordion_animation' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.submenus',
    'pro' => true
	),

  'auto_expand_all_submenus' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.submenus'
	),

  'auto_expand_current_submenus' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.submenus'
	),

  'menu_background_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_item_background_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_item_background_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_item_border_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_title_background_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_title_background_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_current_item_background_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_current_item_background_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_title_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_title_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_link_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_link_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_current_link_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_current_link_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_sub_arrow_border_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_border_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_background_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_background_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_shape_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_shape_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_font' => array(
		'position' => 'menu.style'
	),

  'menu_font_size' => array(
		'position' => 'menu.style'
	),

  'menu_title_font_size' => array(
		'position' => 'menu.style'
	),

  'menu_text_alignment' => array(
    'form_component' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.style',
    'custom' => array('select' => array(
      'left' => 'Left',
      'right' => 'Right')
    )
	),

  'menu_links_height' => array(
		'position' => 'menu.style'
	),

	'shortcode' => array(
		'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.menu'
	),

	'mobile_only' => array(
		'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.menu',
    'pro' => true
	),

	'custom_walker' => array(
		'position' => 'technical.menu',
    'pro' => true
	),

	'custom_css' => array(
    'form_component' => 'ResponsiveMenu\Form\TextArea',
		'position' => 'custom_css.main',
    'pro' => true
	),

	'items_order' => array(
    'form_component' => 'ResponsiveMenu\Form\MenuOrdering',
    'filter' => 'ResponsiveMenu\Filters\JsonFilter',
		'position' => 'items_order.order'
	),

	'use_single_menu' => array(
    'form_component' => 'ResponsiveMenu\Form\CheckBox',
		'position' => 'single_menu.setup',
    'pro' => true
	),

	'single_menu_height' => array(
		'position' => 'single_menu.sizing',
    'pro' => true
	),

	'single_menu_item_link_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'single_menu.colours',
    'pro' => true
	),

	'single_menu_item_hover_colour' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'single_menu.colours',
    'pro' => true
	),

  'use_header_bar' => array(
    'form_component' => 'ResponsiveMenu\Form\CheckBox',
		'position' => 'header_bar.setup',
    'pro' => true
	),

  'header_bar_logo' => array(
    'form_component' => 'ResponsiveMenu\Form\Image',
		'position' => 'header_bar.setup',
    'pro' => true
	),

  'header_bar_logo_link' => array(
		'position' => 'header_bar.setup',
    'pro' => true
	),

  'header_bar_html_content' => array(
    'form_component' => 'ResponsiveMenu\Form\TextArea',
		'position' => 'header_bar.setup',
    'pro' => true
	),

  'header_bar_height' => array(
		'position' => 'header_bar.sizing',
    'pro' => true
	),

  'header_bar_background_color' => array(
    'form_component' => 'ResponsiveMenu\Form\Colour',
		'position' => 'header_bar.colours',
    'pro' => true
	),

  'header_include_search_bar' => array(
    'form_component' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'header_bar.advanced',
    'pro' => true
	),

);
