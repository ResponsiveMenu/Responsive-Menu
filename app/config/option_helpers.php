<?php

$option_helpers = array(

	'breakpoint' => array(
		'filter' => 'ResponsiveMenu\Filters\HtmlFilter',
		'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'look_and_feel.initial',
		'label' => 'Breakpoint Width'
	),

	'depth' => array(
		'filter' => 'ResponsiveMenu\Filters\HtmlFilter',
		'type' => 'ResponsiveMenu\Form\Image',
		'position' => 'look_and_feel.menu'
	),

	'transient_caching' => array(
		'filter' => 'ResponsiveMenu\Filters\HtmlFilter',
		'type' => 'ResponsiveMenu\Form\Checkbox',
		'position' => 'advanced.caching'
	),

);
