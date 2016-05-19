<?php

spl_autoload_register( function( $class_name ) {

	$strip_namespace = str_replace( 'ResponsiveMenu\\', '', $class_name ); 
	$get_folder = str_replace( '\\', '/', $strip_namespace );

	$b_file = __DIR__ . '/app/library/' . $get_folder . '.php';
	$m_file = __DIR__ . '/app/models/' . str_replace('Models/', '', $get_folder ) . '.php';
	$c_file = __DIR__ . '/app/controllers/' . str_replace('Controllers/', '', $get_folder ) . '.php';
	$r_file = __DIR__ . '/app/repositories/' . str_replace('Repositories/', '', $get_folder ) . '.php';
	$h_file = __DIR__ . '/app/helpers/' . str_replace('Helpers/', '', $get_folder ) . '.php';

	if( file_exists( $b_file ) )
		include $b_file;
	elseif( file_exists( $m_file ) )
		include $m_file;
	elseif( file_exists( $c_file ) )
		include $c_file;
	elseif( file_exists( $r_file ) )
		include $r_file;
	elseif( file_exists( $h_file ) )
		include $h_file;

} );
