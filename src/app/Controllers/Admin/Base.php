<?php

namespace ResponsiveMenu\Controllers\Admin;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Repositories\OptionRepository as OptionRepository;

class Base {

	public function __construct(OptionRepository $repository, View $view) {
		$this->repository = $repository;
		$this->view = $view;
	}

}
