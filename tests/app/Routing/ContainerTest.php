<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Routing\Container;

class ContainerTest extends TestCase {

  public function testCreatedObjectIsReturn() {
    $container = new Container;
    $container['std'] = function($c) {
      return new \StdClass;
    };
    $this->assertInstanceOf('StdClass', $container['std']);
  }

  public function testCreatedObjectIsReturnedWithDependencies() {
    $container = new Container;
    $container['std'] = function($c) {
      return new \StdClass;
    };
    $container['std_two'] = function($c) {
      return new \StdClass($c['std']);
    };
    $this->assertInstanceOf('StdClass', $container['std']);
    $this->assertInstanceOf('StdClass', $container['std_two']);
  }

  public function testVariableIsReturned() {
    $container = new Container;
    $container['var'] = 5;
    $this->assertEquals(5, $container['var']);
  }

  public function testVariableIsset() {
    $container = new Container;
    $container['var'] = 5;
    $this->assertTrue(isset($container['var']));
    $this->assertFalse(isset($container['not_set']));
  }

  public function testVariableKeys() {
    $container = new Container;
    $container['var'] = 5;
    $container['var_two'] = 6;
    $this->assertEquals(['var', 'var_two'], $container->keys());
  }

  /**
  * @expectedException \InvalidArgumentException
  */
  public function testExceptionThrownIfDoesntExist() {
    $container = new Container;
    $container['doesnt_exist'];
  }

  /**
  * @expectedException \InvalidArgumentException
  */
  public function testExceptionThrownIfDoesntExistAfterUnsetting() {
    $container = new Container;
    $container['to_delete'] = 5;
    unset($container['to_delete']);
    $container['to_delete'];
  }

}
