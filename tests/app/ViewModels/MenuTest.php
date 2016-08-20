<?php

use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->factory = $this->createMock('ResponsiveMenu\ViewModels\Components\ComponentFactory');
    $this->component = $this->createMock('ResponsiveMenu\ViewModels\Components\Menu\Title');
    $this->component->method('render')->willReturn('a');
    $this->factory->method('build')->willReturn($this->component);

    $this->menu = new ResponsiveMenu\ViewModels\Menu($this->factory);
  }

  public function testOutput() {
      $this->collection->add(new ResponsiveMenu\Models\Option('items_order', '{"title" : "on", "search" : "off"}'));
      $this->assertEquals('a', $this->menu->getHtml($this->collection));
  }

  public function testOutputWithTwoOptionsOn() {
      $this->collection->add(new ResponsiveMenu\Models\Option('items_order', '{"title" : "on", "search" : "on"}'));
      $this->assertEquals('aa', $this->menu->getHtml($this->collection));
  }

  public function testOutputWithOptionsOff() {
      $this->collection->add(new ResponsiveMenu\Models\Option('items_order', '{"title" : "off", "search" : "off"}'));
      $this->assertEquals('', $this->menu->getHtml($this->collection));
  }

}
