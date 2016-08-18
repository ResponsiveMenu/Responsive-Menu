<?php

use PHPUnit\Framework\TestCase;

class ColourTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Colour;
  }

  public function testRendering() {
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 1));
    $this->assertEquals("<input type='text' class='colour wp-color-picker' id='a' name='menu[a]' value='1' />", $output);
  }

}
