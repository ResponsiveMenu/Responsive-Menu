<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Repositories\Repository as Repository;

class Base
{

	public function __construct(Repository $repository, View $view)
	{
		$this->repository = $repository;
		$this->view = $view;
	}

}
