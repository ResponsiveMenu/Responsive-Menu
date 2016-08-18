<?php

use PHPUnit\Framework\TestCase;

class WpWalkerTest extends TestCase {

  public function setUp() {
    $this->getMockBuilder('\Walker_Nav_Menu')->getMock();
    $this->walker = new ResponsiveMenu\Walkers\WpWalker($this->createMock('ResponsiveMenu\Collections\OptionsCollection'));
  }

  public function testEndEl() {
    $output = 'output';
    $this->walker->end_el($output, null);
    $this->assertEquals('output</li>', $output);
  }

  public function testEndLvl() {
    $output = 'output';
    $this->walker->end_lvl($output, null);
    $this->assertEquals('output</ul>', $output);
  }

}
