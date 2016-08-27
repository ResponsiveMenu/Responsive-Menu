<?php

namespace ResponsiveMenu\View;
use PHPUnit\Framework\Testcase;

class FrontViewTest extends TestCase {

  public function setUp() {
    $this->js_factory = $this->createMock('ResponsiveMenu\Factories\JsFactory');
    $this->css_factory = $this->createMock('ResponsiveMenu\Factories\CssFactory');
  }

  public function testSetup() {
    $front_view = new FrontView($this->js_factory, $this->css_factory);
    $this->assertInstanceOf('ResponsiveMenu\View\FrontView', $front_view);
  }

}
