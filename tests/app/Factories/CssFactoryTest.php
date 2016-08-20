<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Factories\CssFactory;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Models\Option;

class CssFactoryTest extends TestCase {

  public function setUp() {
      $this->base_mapper = $this->createMock('ResponsiveMenu\Mappers\ScssBaseMapper');
      $this->button_mapper = $this->createMock('ResponsiveMenu\Mappers\ScssButtonMapper');
      $this->menu_mapper = $this->createMock('ResponsiveMenu\Mappers\ScssMenuMapper');
      $this->minifier = $this->createMock('ResponsiveMenu\Formatters\Minify');

      $this->base_mapper->method('map')->willReturn('a');
      $this->button_mapper->method('map')->willReturn('b');
      $this->menu_mapper->method('map')->willReturn('c');
      $this->minifier->method('minify')->willReturn('d');
      $this->factory = new CssFactory($this->minifier, $this->base_mapper, $this->button_mapper, $this->menu_mapper);
  }

  public function testMinified() {
    $collection = new OptionsCollection;
    $collection->add(new Option('minify_scripts', 'on'));
    $this->assertequals('d', $this->factory->build($collection));
  }

  public function testNotMinified() {
    $collection = new OptionsCollection;
    $collection->add(new Option('minify_scripts', 'off'));
    $this->assertequals('abc', $this->factory->build($collection));
  }

}
