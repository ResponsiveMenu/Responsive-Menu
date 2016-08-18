<?php

use PHPUnit\Framework\TestCase;

class ComponentFactoryTest extends TestCase {

  public function setUp() {
    $this->factory = new ResponsiveMenu\ViewModels\Components\ComponentFactory;
  }

  public function testMapTitle() {
      $this->assertInstanceOf('ResponsiveMenu\ViewModels\Components\Menu\Title', $this->factory->build('title'));
  }

  public function testMapMenu() {
      $this->assertInstanceOf('ResponsiveMenu\ViewModels\Components\Menu\Menu', $this->factory->build('menu'));
  }

  public function testMapSearch() {
      $this->assertInstanceOf('ResponsiveMenu\ViewModels\Components\Menu\Search', $this->factory->build('search'));
  }

  public function testMapAdditionalContent() {
      $this->assertInstanceOf('ResponsiveMenu\ViewModels\Components\Menu\AdditionalContent', $this->factory->build('additional content'));
  }

}
