<?php

use PHPUnit\Framework\TestCase;

class ImportTest extends TestCase {

  public function setUp() {
    $this->form_component = new ResponsiveMenu\Form\Import;
  }

  public function testRendering() {
    $output = $this->form_component->render();
    $this->assertEquals('<input type="file" name="responsive_menu_import_file" /><input type="submit" class="button submit" name="responsive_menu_import" value="Import Options" />', $output);
  }

}
