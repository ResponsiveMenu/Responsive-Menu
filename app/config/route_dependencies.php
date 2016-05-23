<?php

$route_dependencies = array(

	'front.main' => array(
			'controller' => 'ResponsiveMenu\Controllers\Front',
			'repository' => 'ResponsiveMenu\Repositories\OptionRepository',
			'view' => 'ResponsiveMenu\View\FrontView',
			'database' => 'ResponsiveMenu\Database\WpDatabase',
	),
	'admin.main' => array(
			'controller' => 'ResponsiveMenu\Controllers\Admin\Main',
			'repository' => 'ResponsiveMenu\Repositories\OptionRepository',
			'view' => 'ResponsiveMenu\View\AdminView',
			'database' => 'ResponsiveMenu\Database\WpDatabase',
	),

);
