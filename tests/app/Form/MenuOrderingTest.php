<?php

use PHPUnit\Framework\TestCase;

class MenuOrderingTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\MenuOrdering;
  }

  public function testRendering() {
    $this->assertTrue(true);
  }

}
