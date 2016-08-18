<?php

use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Select;
  }

  public function testRendering() {
    $options = [['value' => 1, 'display' => 'a'],['value' => 2, 'display' => 'b']];
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 4), $options);

    $expected = "<div class='select-style'><select class='select' name='menu[a]' id='a'>";
    $expected .= "<option value='1'>a</option>";
    $expected .= "<option value='2'>b</option>";
        $expected .= "</select></div>";

    $this->assertEquals($expected, $output);
  }

  public function testRenderingWithSelected() {
    $options = [['value' => 1, 'display' => 'a'],['value' => 2, 'display' => 'b']];
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 2), $options);

    $expected = "<div class='select-style'><select class='select' name='menu[a]' id='a'>";
    $expected .= "<option value='1'>a</option>";
    $expected .= "<option value='2' selected='selected'>b</option>";
        $expected .= "</select></div>";

    $this->assertEquals($expected, $output);
  }

  public function testRenderingWithDisabled() {
    $options = [['value' => 1, 'display' => 'a', 'disabled' => true],['value' => 2, 'display' => 'b']];
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 2), $options);

    $expected = "<div class='select-style'><select class='select' name='menu[a]' id='a'>";
    $expected .= "<option value='1' disabled='disabled'>a [PRO]</option>";
    $expected .= "<option value='2' selected='selected'>b</option>";
        $expected .= "</select></div>";

    $this->assertEquals($expected, $output);
  }

}
