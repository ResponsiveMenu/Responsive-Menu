<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Collections\OptionsCollection;

class TitleTest extends TestCase {

  public function setUp() {
    $this->translator = $this->createMock('ResponsiveMenu\Translation\Translator');
    $this->component = new Title($this->translator);
  }

  public function testRender() {
    $collection = new OptionsCollection;
    $collection->add(new Option('menu_title', 'a'));
    $collection->add(new Option('menu_title_link', 'b'));
    $collection->add(new Option('menu_title_link_location', 'c'));
    $collection->add(new Option('menu_title_image', 'd'));
    $collection->add(new Option('menu_title_image_alt', 'e'));
    $this->translator->method('translate')->will($this->onConsecutiveCalls('a', 'b'));
    $rendered = $this->component->render($collection);
    $this->assertContains('target="c"', $rendered);
    $this->assertContains('href="b"', $rendered);
    $this->assertContains('alt="e"', $rendered);
    $this->assertContains('src="d"', $rendered);
    $this->assertContains('>a<', $rendered);
  }

}
