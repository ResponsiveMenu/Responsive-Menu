<?php

use PHPUnit\Framework\TestCase;

class CheckboxTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Checkbox;
  }

  public function testRendering() {
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 1));
    $this->assertEquals("<div class='onoffswitch'>
            <input type='checkbox' class='checkbox onoffswitch-checkbox' id='a' name='menu[a]' value='on' />
            <label class='onoffswitch-label' for='a'>
                <span class='onoffswitch-inner'></span>
                <span class='onoffswitch-switch'></span>
            </label>
          </div>", $output);
  }

  public function testRenderingTurnedOn() {
    $output = $this->form_component->render(new ResponsiveMenu\Models\Option('a', 'on'));
    $this->assertEquals("<div class='onoffswitch'>
            <input type='checkbox' class='checkbox onoffswitch-checkbox' id='a' checked='checked' name='menu[a]' value='on' />
            <label class='onoffswitch-label' for='a'>
                <span class='onoffswitch-inner'></span>
                <span class='onoffswitch-switch'></span>
            </label>
          </div>", $output);
  }

}
