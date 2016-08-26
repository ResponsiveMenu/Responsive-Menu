<?php

use PHPUnit\Framework\TestCase;

class AdditionalContentTest extends TestCase {

  public function setUp() {
    $this->translator = $this->createMock('ResponsiveMenu\Translation\Translator');
    $this->component = new ResponsiveMenu\ViewModels\Components\Menu\AdditionalContent($this->translator);
  }

  public function testRender() {
    $collection = new ResponsiveMenu\Collections\OptionsCollection;
    $collection->add(new ResponsiveMenu\Models\Option('menu_additional_content', 'b'));
    $this->translator->method('translate')->willReturn('b');
    $this->translator->method('allowShortcode')->willReturn('b');
    $this->assertEquals('<div id="responsive-menu-additional-content">b</div>', $this->component->render($collection));
  }

  public function testRenderEmpty() {
    $collection = new ResponsiveMenu\Collections\OptionsCollection;
    $collection->add(new ResponsiveMenu\Models\Option('menu_additional_content', ''));
    $this->translator->method('translate')->willReturn('');
    $this->translator->method('allowShortcode')->willReturn('');
    $this->assertNull($this->component->render($collection));
  }

}
