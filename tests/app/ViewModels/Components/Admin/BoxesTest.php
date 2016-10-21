<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Collections\OptionsCollection;

class BoxesTest extends TestCase {

  public function setUp() {
    $this->collection = new OptionsCollection;
    $this->collection->add(new Option('a_three', 'a value'));
    $this->collection->add(new Option('b_three', 'b value'));

    $this->component = new Boxes(
    [
      'a one' => [
        'a two' => [
          [
            'type' => 'text',
            'option' => 'a_three',
            'title' => 'a three title',
            'label' => 'a three label',
          ]
        ]
      ],
      'b one' => [
        'b two' => [
          [
            'type' => 'colour',
            'option' => 'b_three',
            'title' => 'b three title',
            'label' => 'b three label',
          ]
        ]
      ]
    ], $this->collection, 'a_value');
  }

  public function testRender() {

    $rendered = $this->component->render();
    $this->assertContains('tab_container_a_one"', $rendered);
    $this->assertContains('id="b_three_container"', $rendered);
    $this->assertContains('<div class="label">a three title</div>', $rendered);
    $this->assertContains('<div class="label">b three title</div>', $rendered);
  }

}
