<?php

$option_helpers = array(

	'breakpoint' => array(
		'position' => 'look_and_feel.initial',
		'label' => 'Breakpoint Width'
	),

	'depth' => array(
		'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'look_and_feel.menu',
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

	'animation_type' => array(
		'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'animation.menu',
    'custom' => array('select' => array('overlay' => 'Overlay', 'push' => 'Push'))
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
