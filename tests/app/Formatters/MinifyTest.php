<?php

use PHPUnit\Framework\TestCase;

class MinifyTest extends TestCase {

  public function setUp() {
    $this->minify = new ResponsiveMenu\Formatters\Minify;
  }

  public function testSimpleCSSWhitespaceRemovalTest() {
    $this->assertEquals('.class{}', $this->minify->minify('.class {  }'));
  }

  public function testSimpleCSSCommentRemovalTest() {
    $this->assertEquals('.class{}', $this->minify->minify('.class{} /*comment */'));
  }

  public function testSimpleCSSTabRemovalTest() {
    $this->assertEquals('.class{}', $this->minify->minify('.class{  }'));
  }

  public function testSimpleCSSWhitespaceAfterRemovalTest() {
    $this->assertEquals('.class{}', $this->minify->minify('.class{}                '));
  }

}
