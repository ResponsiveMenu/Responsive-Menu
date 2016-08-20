<?php

use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase {

  public function setUp() {
    $this->collection = $this->createMock('ResponsiveMenu\Collections\OptionsCollection');
    $this->component = $this->createMock('ResponsiveMenu\ViewModels\Components\Button\Button');
    $this->component->method('render')->willReturn('a');
    $this->button = new ResponsiveMenu\ViewModels\Button($this->component);
  }

  public function testOutput() {
      $this->assertEquals('a', $this->button->getHtml($this->collection));
  }

}
