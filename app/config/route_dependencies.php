<?php

$route_dependencies = [

	'front.main' => [
			'controller' => 'ResponsiveMenu\Controllers\Front',
			'repository' => 'ResponsiveMenu\Repositories\OptionRepository',
			'view' => 'ResponsiveMenu\View\FrontView',
			'database' => 'ResponsiveMenu\Database\WpDatabase',
      'css_factory' => 'ResponsiveMenu\Factories\CssFactory',
      'js_factory' => 'ResponsiveMenu\Factories\JsFactory'
	],

	'admin.main' => [
			'controller' => 'ResponsiveMenu\Controllers\Admin\Main',
			'repository' => 'ResponsiveMenu\Repositories\OptionRepository',
			'view' => 'ResponsiveMenu\View\AdminView',
			'database' => 'ResponsiveMenu\Database\WpDatabase',
      'css_factory' => 'ResponsiveMenu\Factories\CssFactory',
      'js_factory' => 'ResponsiveMenu\Factories\JsFactory'
	],

];
