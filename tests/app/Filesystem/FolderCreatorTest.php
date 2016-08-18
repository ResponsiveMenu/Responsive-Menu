<?php

use PHPUnit\Framework\TestCase;

class FolderCreatorTest extends TestCase {

  public function setUp() {
    $this->creator = new ResponsiveMenu\Filesystem\FolderCreator;
    $this->dir = dirname(__FILE__) . '/tmp';
  }

  public function testCreate() {
    $this->creator->create($this->dir);
    $this->assertTrue($this->creator->exists($this->dir));
  }

  public function tearDown() {
    rmdir($this->dir);
  }
  
}
