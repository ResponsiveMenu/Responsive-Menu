<?php

namespace ResponsiveMenu\View;

class AdminView implements View {

  public function __construct() {
    if(is_admin() && isset($_GET['page']) && $_GET['page'] == 'responsive-menu'):

      wp_enqueue_media();

      wp_enqueue_style('wp-color-picker');
      wp_enqueue_script('wp-color-picker');

      wp_enqueue_script('responsive-menu-font-awesome', 'https://use.fontawesome.com/b6bedb3084.js', null, null);

      wp_enqueue_script('postbox');

      wp_enqueue_script('jquery-ui-core');

      wp_register_style('responsive-menu-admin-css',  plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/main.css', false, null);
      wp_enqueue_style('responsive-menu-admin-css');

      wp_register_script('responsive-menu-admin-js',  plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/main.js', 'jquery', null);
      wp_enqueue_script('responsive-menu-admin-js');

    endif;
  }

	public function render($location, $l = []) {
		include dirname(dirname(dirname(__FILE__))) . '/views/admin/' . $location . '.phtml';
	}

  public function noCacheHeaders() {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=export.json');
  }

  public function stopProcessing() {
    exit();
  }

  public function display($content) {
    echo $content;
  }

}
