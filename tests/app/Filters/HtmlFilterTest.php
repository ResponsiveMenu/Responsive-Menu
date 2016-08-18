<?php

use PHPUnit\Framework\TestCase;

class HtmlFilterTest extends TestCase {

  public function setUp() {
      $this->filter = new ResponsiveMenu\Filters\HtmlFilter;
  }

  public function testFiltered() {
    $this->assertEquals('a', $this->filter->filter('a'));
  }

}
