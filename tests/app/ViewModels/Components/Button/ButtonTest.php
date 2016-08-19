<?php

namespace ResponsiveMenu\ViewModels\Components\Button;

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Collections\OptionsCollection;

class ButtonTest extends TestCase {

  public function setUp() {
    $this->translator = $this->createMock('ResponsiveMenu\Translation\Translator');
    $this->component = new Button($this->translator);
  }

  public function testRender() {
    $collection = new OptionsCollection;
    $collection->add(new Option('button_title', 'b'));
    $collection->add(new Option('button_title_position', 'left'));
    $collection->add(new Option('button_click_animation', 'd'));
    $collection->add(new Option('button_image', 'e'));
    $collection->add(new Option('button_image_when_clicked', 'f'));

    $this->translator->method('translate')->willReturn('a');

    $rendered = $this->component->render($collection);
    $this->assertContains('responsive-menu-label-left', $rendered);
    $this->assertContains('responsive-menu-accessible', $rendered);
    $this->assertContains('responsive-menu-d', $rendered);
  }

}
