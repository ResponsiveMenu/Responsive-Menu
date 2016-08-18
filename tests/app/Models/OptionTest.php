<?php

use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase {

  public function setUp() {
    $this->option = new ResponsiveMenu\Models\Option('a', 'b');
  }

  public function testGetName() {
    $this->assertEquals('a', $this->option->getName());
  }

  public function testGetValue() {
    $this->assertEquals('b', $this->option->getValue());
  }

  public function testSetValue() {
    $this->option->setValue('c');
    $this->assertEquals('c', $this->option->getValue());
  }

  public function testToString() {
    $this->assertEquals('b', $this->option);
  }

  public function testAddFilter() {
    $filter = $this->createMock('ResponsiveMenu\Filters\TextFilter');
    $filter->method('filter')->willReturn('d');
    $this->option->setFilter($filter);
    $this->assertEquals('b', $this->option->getValue());
    $this->assertEquals('d', $this->option->getFiltered());
  }

  public function testAddAndGetFilter() {
    $filter = $this->createMock('ResponsiveMenu\Filters\TextFilter');
    $filter->method('filter')->willReturn('d');
    $this->option->setFilter($filter);
    $this->assertInstanceOf('ResponsiveMenu\Filters\TextFilter', $this->option->getFilter());
  }

  public function testFilteredJsonAsString() {
    $option = new ResponsiveMenu\Models\Option('a', '{"a":"1","b":"2"}');
    $filter = new ResponsiveMenu\Filters\JsonFilter;
    $option->setFilter($filter);
    $this->assertEquals('{"a":"1","b":"2"}', $option->getFiltered());
  }

  public function testFilteredJsonAsArray() {
    $option = new ResponsiveMenu\Models\Option('a', ['a' => 1,'b' => 2]);
    $filter = new ResponsiveMenu\Filters\JsonFilter;
    $option->setFilter($filter);
    $this->assertEquals('{"a":1,"b":2}', $option->getFiltered());
  }

}
