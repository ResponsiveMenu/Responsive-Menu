<?php

require_once dirname(__FILE__) . '/src/app/Mappers/scss.inc.php';

spl_autoload_register( function( $class_name ) {

	$strip_namespace = str_replace( 'ResponsiveMenu\\', '', $class_name );
	$file_name = str_replace( '\\', '/', $strip_namespace );

	$file = __DIR__ . '/src/app/' . $file_name . '.php';

	if(file_exists($file))
		include $file;

} );
