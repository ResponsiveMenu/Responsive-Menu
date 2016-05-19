<?php

$route_dependencies = array(

	'admin.main' => array(
			'controller' => 'ResponsiveMenu\Controllers\Admin\Main',
			'repository' => 'ResponsiveMenu\Repositories\OptionRepository',
			'view' => 'ResponsiveMenu\View\AdminView',
			'database' => 'ResponsiveMenu\Database\WpDatabase',
	),
  
);
