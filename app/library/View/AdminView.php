<?php

namespace ResponsiveMenu\View;

class AdminView implements View
{

  public function __construct()
  {
    if(is_admin() && isset($_GET['page']) && $_GET['page'] == 'responsive-menu-3'):
      add_action( 'admin_enqueue_scripts', function() {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
      });

      wp_register_style( 'admin-css',  plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/main.css', false, null );
      wp_enqueue_style( 'admin-css' );

      wp_register_script( 'admin-js',  plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/main.js', 'jquery', null );
      wp_enqueue_script( 'admin-js' );

      wp_register_script( 'sticky-sidebar-js',  plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/sticky-sidebar.js', 'jquery', null );
      wp_enqueue_script( 'sticky-sidebar-js' );
    endif;
  }

	public function render($location, $options = [], $flash = [])
	{
		$this->options = $options;
    $this->messages = $flash;
		include dirname(dirname(dirname(__FILE__))) . '/views/admin/' . $location . '.phtml';
	}

}
