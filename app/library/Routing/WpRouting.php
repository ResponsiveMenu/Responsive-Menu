<?php

namespace ResponsiveMenu\Routing;

class WpRouting implements Routing
{

	protected $dependencies;

	public function __construct($dependencies)
	{
		$this->dependencies = $dependencies;
	}

	public function route()
	{
		if(is_admin()):
			add_action( 'admin_menu', [$this, 'adminPage']);
    else:
      $controller = $this->getController('front.main');
      add_action('template_redirect', [$controller, 'index']);
    endif;
	}

	public function adminPage()
	{
		add_menu_page(
			'Responsive Menu',
			'Responsive Menu',
			'manage_options',
			'responsive-menu-3',
			array($this->getController('admin.main'), 'index'),
			plugin_dir_url(dirname(dirname(__FILE__))) . 'public/imgs/icon.png',
			0 );
	}

	protected function getController($key)
	{
		$view = $this->getView($key);
		$repo = $this->getRepository($key);
		return new $this->dependencies[$key]['controller']($repo,$view);
	}

	protected function getView($key)
	{
		return new $this->dependencies[$key]['view'];
	}

	protected function getRepository($key)
	{
		return new $this->dependencies[$key]['repository']($this->getDatabase($key));
	}

	protected function getDatabase($key)
	{
		global $wpdb;
		return new $this->dependencies[$key]['database']($wpdb);
	}

}
