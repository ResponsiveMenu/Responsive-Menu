<?php

use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Image;
  }

  public function testRendering() {
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 1));
    $this->assertEquals("<input type='text' class='image' id='a' name='menu[a]' value='1' />"
    .      "<button type='button' class='button image_button' for='a' /><i class='fa fa-upload'></i></button>", $output);
  }

}
