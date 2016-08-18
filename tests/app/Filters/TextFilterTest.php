<?php

use PHPUnit\Framework\TestCase;

class TextFilterTest extends TestCase {

  public function setUp() {
      $this->filter = new ResponsiveMenu\Filters\TextFilter;
  }

  public function testFilteredWithString() {
    $this->assertEquals('a', $this->filter->filter('a'));
  }

  public function testFilteredWithHtml() {
    $this->assertEquals('a', $this->filter->filter('<span class="test">a</span>'));
  }

}
