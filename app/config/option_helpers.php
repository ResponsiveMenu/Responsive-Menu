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
		'position' => 'advanced.menu',
    'custom' => array('select'=> $location_menus )
	),

	'depth' => array(
		'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'menu.general',
    'custom' => array('select' => array_combine(range(1,5),range(1,5)))
	),

	'transient_caching' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'advanced.caching'
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

  'button_x_on_click' => array(
		'position' => 'button.general'
	),

  'button_image_when_clicked' => array(
    'type' => 'ResponsiveMenu\Form\Image',
    'position' => 'button.general'
  ),

  'button_click_trigger' => array(
    'position' => 'button.general'
  ),

  'button_title_position' => array(
    'type' => 'ResponsiveMenu\Form\Select',
    'position' => 'button.general',
    'custom' => array('select' => array('left' => 'Left', 'right' => 'Right'))
  ),

  'button_title_height' => array(
    'position' => 'button.general'
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

  'button_top' => array(
		'position' => 'button.location'
	),

  'button_distance_from_side' => array(
		'position' => 'button.location'
	),

  'button_left_or_right' => array(
    'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'button.location',
    'custom' => array('select' => array('left' => 'Left', 'right' => 'Right'))
	),

  'button_push_with_animation' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'button.main'
	),

  'button_font_size' => array(
		'position' => 'button.sizing'
	),

  /* Animation Settings */
	'animation_type' => array(
		'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'animation.menu',
    'custom' => array('select' => array('overlay' => 'Overlay', 'push' => 'Push'))
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
  'accordion_animation' => array(
    'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'menu.submenus'
	),

  'active_arrow_shape' => array(
		'position' => 'menu.submenus'
	),

  'inactive_arrow_shape' => array(
		'position' => 'menu.submenus'
	),

  'active_arrow_image' => array(
    'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.submenus'
	),

  'inactive_arrow_image' => array(
    'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'menu.submenus'
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
    'custom' => array('select' => array('left' => 'Left', 'right' => 'Right'))
	),

  'menu_links_height' => array(
		'position' => 'menu.style'
	),

	'shortcode' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'advanced.shortcode'
	),

	'mobile_only' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'advanced.mobile_only',
    'custom' => array('pro' => true)
	),

);
