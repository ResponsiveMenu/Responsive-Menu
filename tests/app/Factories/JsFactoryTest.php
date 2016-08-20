<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Factories\JsFactory;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Models\Option;

class JsFactoryTest extends TestCase {

  public function setUp() {
      $this->mapper = $this->createMock('ResponsiveMenu\Mappers\JsMapper');
      $this->minifier = $this->createMock('ResponsiveMenu\Formatters\Minify');
      $this->mapper->method('map')->willReturn('a');
      $this->minifier->method('minify')->willReturn('b');
      $this->factory = new JsFactory($this->mapper, $this->minifier);
  }

  public function testMinified() {
    $collection = new OptionsCollection;
    $collection->add(new Option('minify_scripts', 'on'));
    $this->assertequals('b', $this->factory->build($collection));
  }

  public function testNotMinified() {
    $collection = new OptionsCollection;
    $collection->add(new Option('minify_scripts', 'off'));
    $this->assertequals('a', $this->factory->build($collection));
  }

}
