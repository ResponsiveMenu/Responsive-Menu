<?php

use PHPUnit\Framework\TestCase;

class TextTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Text;
  }

  public function testRendering() {
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 1));
    $this->assertEquals("<input type='text' class='text' id='a' name='menu[a]' value='1' />", $output);
  }

}
