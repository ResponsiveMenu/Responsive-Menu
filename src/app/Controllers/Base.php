<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Repositories\Options\ReadRepository as Repository;

class Base
{

	public function __construct(Repository $repository, View $view)
	{
		$this->repository = $repository;
		$this->view = $view;
	}

}
