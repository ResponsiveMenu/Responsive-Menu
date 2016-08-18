<?php

use PHPUnit\Framework\TestCase;

class FileCreatorTest extends TestCase {

  public function setUp() {
    $this->creator = new ResponsiveMenu\Filesystem\FileCreator;
  }

  public function testCreate() {
    $this->assertTrue($this->creator->create(dirname(__FILE__), 'test.txt','a'));
    $this->assertEquals('a', file_get_contents(dirname(__FILE__) . '/test.txt'));
  }

  public function testFailToCreate() {
    $this->assertFalse($this->creator->create('', 'test.txt','a'));
  }

  public function tearDown() {
    if(file_exists(dirname(__FILE__). '/test.txt'))
      unlink(dirname(__FILE__). '/test.txt');
  }
  
}
