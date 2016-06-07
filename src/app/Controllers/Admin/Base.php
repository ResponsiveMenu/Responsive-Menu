<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Repositories\Options\Repository as Repository;

class Base
{

	public function __construct(Repository $repository, View $view)
	{
		$this->repository = $repository;
		$this->view = $view;
	}

}
