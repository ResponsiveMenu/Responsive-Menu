<?php

namespace ResponsiveMenu\Routing;

class Routes
{

	private $router;

	public function __construct(Routing $router)
	{
		$this->router = $router;
	}

	public function route()
	{
		$this->router->route();
	}

}
