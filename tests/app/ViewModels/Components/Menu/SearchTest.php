<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Collections\OptionsCollection;

class SearchTest extends TestCase {

  public function setUp() {
    $this->translator = $this->createMock('ResponsiveMenu\Translation\Translator');
    $this->component = new Search($this->translator);
  }

  public function testRender() {
    $collection = new OptionsCollection;
    $this->translator->method('searchUrl')->willReturn('a');
    $this->assertContains('action="a" ', $this->component->render($collection));
  }

}
