<?php

use PHPUnit\Framework\TestCase;

class ScriptsBuilderTest extends TestCase {

  public function setUp() {
    $this->files = $this->createMock('ResponsiveMenu\Filesystem\FileCreator');
    $this->folders = $this->createMock('ResponsiveMenu\Filesystem\FolderCreator');
    $this->css = $this->createMock('ResponsiveMenu\Factories\CssFactory');
    $this->js = $this->createMock('ResponsiveMenu\Factories\JsFactory');
    $this->collection = $this->createMock('ResponsiveMenu\Collections\OptionsCollection');
    $this->id = 2;
    
    $this->builder = new ResponsiveMenu\Filesystem\ScriptsBuilder($this->css, $this->js, $this->files, $this->folders, $this->id);

  }

  public function testBuild() {
    $this->assertEquals(null, $this->builder->build($this->collection));
  }

}
