<?php

use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase {

  public function setUp() {
    // Yuck - Needed for WordPress Internationalisation function
    function __($a, $b) {
      return $a;
    }
    $this->form_component = new ResponsiveMenu\Form\Export;
  }

  public function testRendering() {
    $output = $this->form_component->render();
    $this->assertEquals('<input type="submit" class="button submit" name="responsive_menu_export" value="Export Options" />', $output);
  }

}
