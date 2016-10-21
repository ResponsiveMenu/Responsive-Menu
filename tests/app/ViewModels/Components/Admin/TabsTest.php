<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Collections\OptionsCollection;

class TabsTest extends TestCase {

  public function setUp() {
    $this->component = new Tabs(['a one' => '1', 'b two' => '2'], 'a_one');
  }

  public function testRender() {
    $rendered = $this->component->render();
    $this->assertContains('id="tab_a_one"', $rendered);
    $this->assertContains('id="tab_b_two"', $rendered);
    $this->assertContains('>a one<', $rendered);
    $this->assertContains('>b two<', $rendered);
    $this->assertContains('active_tab', $rendered);
  }

}
