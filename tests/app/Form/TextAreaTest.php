<?php

use PHPUnit\Framework\TestCase;

class TextAreaTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\TextArea;
  }

  public function testRendering() {
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 1));
    $this->assertEquals("<textarea class='textarea' id='a' name='menu[a]'>1</textarea>", $output);
  }

}
