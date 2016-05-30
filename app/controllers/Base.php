<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View as View;
use ResponsiveMenu\Repositories\Repository as Repository;
use ResponsiveMenu\Factories\CssFactory as CssFactory;
use ResponsiveMenu\Factories\JsFactory as JsFactory;

class Base
{

	public function __construct(Repository $repository, View $view, CssFactory $css_factory, JsFactory $js_factory)
	{
		$this->repository = $repository;
		$this->view = $view;
    $this->css_factory = $css_factory;
    $this->js_factory = $js_factory;
	}

}
