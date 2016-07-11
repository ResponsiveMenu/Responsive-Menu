<?php

namespace ResponsiveMenu\Routing;
use ResponsiveMenu\Routing\Container as Container;

class WpRouting implements Routing {

  protected $container;

  public function __construct(Container $container) {
    $this->container = $container;
  }

  public function route() {
    if(is_admin())
      add_action('admin_menu', [$this, 'adminPage']);
    else
      add_action('template_redirect', [$this->container['front_controller'], 'index']);
  }

  public function adminPage() {
    /* Heavily reliant on WordPress so very hard coded */
    if(isset($_POST['responsive_menu_submit'])):
      $method = 'update';
    elseif(isset($_POST['responsive_menu_reset'])):
      $method = 'reset';
    elseif(isset($_POST['responsive_menu_export'])):
      $controller = $this->container['admin_controller'];
      $controller->export();
    elseif(isset($_POST['responsive_menu_import'])):
      $method = 'import';
    else:
      $method = 'index';
    endif;

    add_menu_page(
      'Responsive Menu',
      'Responsive Menu',
      'manage_options',
      'responsive-menu',
      function() use ($method) {
        $controller = $this->container['admin_controller'];
        switch ($method) :
          case 'update':
            $controller->$method($this->container['default_options'], $_POST['menu']);
            break;
          case 'reset':
            $controller->$method($this->container['default_options']);
            break;
          case 'import':
            $controller->$method($this->container['default_options'], $_FILES['responsive_menu_import_file']);
            break;
          default:
            $controller->$method();
            break;
        endswitch;
      },
      'dashicons-menu');
  }

}
