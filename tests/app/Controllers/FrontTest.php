<?php

use PHPUnit\Framework\TestCase;

class FrontTest extends TestCase {

  public function setUp() {
    $this->view = $this->createMock('ResponsiveMenu\View\View');
    $this->service = $this->createMock('ResponsiveMenu\Services\OptionService');
    $this->view->method('render')->willReturn(true);
    $this->controller = new ResponsiveMenu\Controllers\Front($this->service, $this->view);

  }

  public function testPreview() {
    $this->assertTrue($this->controller->preview());
  }

}
