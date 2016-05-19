<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Repositories\Repository as Repository;

class Base
{
	protected $repository;
	protected $view;

	public function __construct(Repository $repository, View $view)
	{
		$this->repository = $repository;
		$this->view = $view;
	}

}
