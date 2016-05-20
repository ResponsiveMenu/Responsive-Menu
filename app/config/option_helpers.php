<?php

$option_helpers = array(

	'breakpoint' => array(
		'position' => 'look_and_feel.initial',
		'label' => 'Breakpoint Width'
	),

	'depth' => array(
		'type' => 'ResponsiveMenu\Form\Select',
		'position' => 'look_and_feel.menu',
    'custom' => array('select' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5))
	),

	'transient_caching' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'advanced.caching'
	),

	'button_background_colour' => array(
		'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'colours.button'
	),

	'button_line_colour' => array(
		'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'colours.button'
	),

	'button_text_colour' => array(
		'type' => 'ResponsiveMenu\Form\Colour',
		'position' => 'colours.button'
	),

	'button_transparent_background' => array(
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'colours.button'
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
