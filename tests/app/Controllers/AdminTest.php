<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {

  static public function setUpBeforeClass() {
    function __($a, $b) {
      return $a;
    }
  }
  public function setUp() {
    $this->view = $this->createMock('ResponsiveMenu\View\View');
    $this->service = $this->createMock('ResponsiveMenu\Services\OptionService');
    $this->view->method('render')->willReturn(true);
    $this->service->method('combineOptions')->willReturn([]);
    $this->controller = new ResponsiveMenu\Controllers\Admin($this->service, $this->view);

  }

  public function testUpdate() {
    $this->assertTrue($this->controller->update([],[]));
  }

  public function testReset() {
    $this->assertTrue($this->controller->reset([]));
  }

  public function testIndex() {
    $this->assertTrue($this->controller->index([]));
  }

}
