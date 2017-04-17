<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Controllers\AdminController;

class AdminControllerTest extends TestCase {

    private $view_mock;
    private $manager_mock;

    public function setUp() {
        $this->view_mock = $this->createMock('ResponsiveMenu\View\View');
        $this->manager_mock = $this->createMock('ResponsiveMenu\Management\OptionManager');
    }

    public function testAllMethod() {
        $controller = new AdminController($this->manager_mock, $this->view_mock);
        $controller->index('foo', 'bar');
    }

}
