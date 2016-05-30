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
    'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'initial_setup.initial',
    'custom' => array('select'=> $available_menus)
	),

	'theme_location_menu' => array(
    'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.advanced',
    'custom' => array('select'=> $location_menus )
	),

	'menu_depth' => array(
		'type' => 'ResponsiveMenu\Form\Select',
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
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_remove_search_bar' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.general',
	),

	'menu_disable_scrolling' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_overlay_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.advanced',
    'pro' => true
	),

	'menu_additional_content' => array(
    'type' => 'ResponsiveMenu\Form\Textarea',
		'position' => 'menu.advanced',
	),

	'menu_additional_content_position' => array(
    'type' => 'ResponsiveMenu\Form\Select',
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
    'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.general'
	),

	'menu_title_font_icon' => array(
		'position' => 'menu.general',
    'pro' => true
	),

	'menu_appear_from' => array(
    'type' => 'ResponsiveMenu\Form\Select',
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
    'type' => 'ResponsiveMenu\Form\Select',
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

  'minify_scripts' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.scripts',
	),

  'scripts_in_footer' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.scripts',
	),

  'external_files' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.scripts',
	),

  /*
  Button Related Settings
  */
  'button_title' => array(
		'position' => 'button.general'
	),

  'button_image' => array(
    'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'button.general'
	),

  'button_image_when_clicked' => array(
    'type' => 'ResponsiveMenu\Form\Image',
    'position' => 'button.general'
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
    'type' => 'ResponsiveMenu\Form\Select',
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
    'type' => 'ResponsiveMenu\Form\Select',
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
		'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'button.colours'
	),

	'button_line_colour' => array(
		'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'button.colours'
	),

	'button_text_colour' => array(
		'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'button.colours'
	),

	'button_transparent_background' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
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
    'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'button.location',
    'custom' => array('select' => array(
      'left' => 'Left',
      'right' => 'Right')
    )
	),

  'button_position_type' => array(
    'type' => 'ResponsiveMenu\Form\Select',
    'position' => 'button.advanced',
    'custom' => array('select' => array(
      'absolute' => 'Static',
      'fixed' => 'Fixed')
    )
  ),

  'button_push_with_animation' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
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
		'type' => 'ResponsiveMenu\Form\Select',
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
    'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.submenus',
    'pro' => true
	),

  'inactive_arrow_image' => array(
    'type' => 'ResponsiveMenu\Form\Image',
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

  'accordion_animation' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.submenus',
    'pro' => true
	),

  'menu_item_background_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_item_background_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_item_border_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_title_background_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_title_background_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_current_item_background_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_current_item_background_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.background_colours'
	),

  'menu_title_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_title_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_link_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_link_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_current_link_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_current_link_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.text_colours'
	),

  'menu_sub_arrow_border_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_border_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_background_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_background_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_shape_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'menu.sub_arrow_colours'
	),

  'menu_sub_arrow_shape_hover_colour' => array(
    'type' => 'ResponsiveMenu\Form\Colour',
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
    'type' => 'ResponsiveMenu\Form\Select',
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
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.menu'
	),

	'mobile_only' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'technical.menu',
    'pro' => true
	),

	'custom_walker' => array(
		'position' => 'technical.menu',
    'pro' => true
	)

);
