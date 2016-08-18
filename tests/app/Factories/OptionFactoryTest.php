<?php

use PHPUnit\Framework\TestCase;

class OptionFactoryTest extends TestCase {

  public function setUp() {
    $defaults = ['a' => 1, 'b' => 2];
    $helpers = ['a' => ['filter' => 'ResponsiveMenu\Filters\HtmlFilter']];
    $this->factory = new ResponsiveMenu\Factories\OptionFactory($defaults, $helpers);
  }

  public function testSetFilterIsReturned() {
    $option = $this->factory->build('a', 4);
    $this->assertInstanceOf('ResponsiveMenu\Filters\HtmlFilter', $option->getFilter());
  }

  public function testDefaultFilterIsReturned() {
    $option = $this->factory->build('b', 4);
    $this->assertInstanceOf('ResponsiveMenu\Filters\TextFilter', $option->getFilter());
  }

  public function testOptionIsReturned() {
    $option = $this->factory->build('b', 4);
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $option);
  }

  public function testDefaultIsReturnedIfValueIsNull() {
    $option = $this->factory->build('b', null);
    $this->assertEquals(2, $option->getValue());
  }

  public function testZeroIsReturnedAndNotDefault() {
    $option = $this->factory->build('b', 0);
    $this->assertEquals(0, $option->getValue());
  }

  public function testUpdatedValueIsReturned() {
    $option = $this->factory->build('a', 'updated');
    $this->assertEquals('updated', $option->getValue());
  }

}
