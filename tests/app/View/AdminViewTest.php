<?php

namespace ResponsiveMenu\View;
use PHPUnit\Framework\Testcase;

class AdminViewTest extends TestCase {

  public function setUp() {
    function is_admin() {
      return false;
    }
  }
  
  public function testSetup() {
    $admin_view = new AdminView;
    $this->assertInstanceOf('ResponsiveMenu\View\AdminView', $admin_view);
  }

}
