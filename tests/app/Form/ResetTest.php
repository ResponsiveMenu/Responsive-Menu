<?php

use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Reset;
  }

  public function testRendering() {
    $output = $this->form_component->render();
    $this->assertEquals('<input type="submit" class="button submit" name="responsive_menu_reset" value="Reset Options" />', $output);
  }

}
