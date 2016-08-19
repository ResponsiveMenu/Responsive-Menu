<?php

namespace ResponsiveMenu\ViewModels\Components\Menu;
use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Models\Option;

class MenuTest extends TestCase {

  public function setUp() {

    function wp_nav_menu($args) {
      return $args;
    }

    $this->translator = $this->createMock('ResponsiveMenu\Translation\Translator');
    $this->component = new Menu($this->translator);
  }

  public function testRender() {
    $collection = new \ResponsiveMenu\Collections\OptionsCollection;
    $collection->add(new Option('menu_to_use', 'b'));
    $collection->add(new Option('theme_location_menu', 'b'));
    $collection->add(new Option('menu_depth', 'b'));
    $collection->add(new Option('custom_walker', '\StdClass'));
    $this->translator->method('translate')->willReturn('b');
    $args = $this->component->render($collection);
    $this->assertEquals('', $args['container']);
    $this->assertEquals('responsive-menu', $args['menu_id']);
    $this->assertNull($args['menu_class']);
    $this->assertNull($args['menu']);
    $this->assertEquals('b', $args['depth']);
    $this->assertEquals('b', $args['theme_location']);
    $this->assertFalse($args['echo']);
  }

}
