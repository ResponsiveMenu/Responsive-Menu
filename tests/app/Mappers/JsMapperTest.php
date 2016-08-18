<?php

use PHPUnit\Framework\TestCase;

class JsMapperTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->collection->add(new ResponsiveMenu\Models\Option('animation_speed', 5));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_click_trigger', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_image', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image', 'b'));
    $this->collection->add(new ResponsiveMenu\Models\Option('breakpoint', 'c'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_click_trigger', 'd'));
    $this->collection->add(new ResponsiveMenu\Models\Option('animation_type', 'e'));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_appear_from', 'f'));
    $this->collection->add(new ResponsiveMenu\Models\Option('page_wrapper', 'g'));
    $this->collection->add(new ResponsiveMenu\Models\Option('accordion_animation', 'h'));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_close_on_body_click', 'i'));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_close_on_link_click', 'j'));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_item_click_to_trigger_submenu', 'k'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_push_with_animation', 'l'));

    $this->mapper = new ResponsiveMenu\Mappers\JsMapper($this->collection);
  }

  public function testOutput() {
    $this->assertContains('animationSpeed: 5000', $this->mapper->map());
    $this->assertContains("trigger: 'd'", $this->mapper->map());
    $this->assertContains('breakpoint: c', $this->mapper->map());
    $this->assertContains("pushButton: 'l'", $this->mapper->map());
    $this->assertContains("animationType: 'e'", $this->mapper->map());
    $this->assertContains("animationSide: 'f'", $this->mapper->map());
    $this->assertContains("pageWrapper: 'g'", $this->mapper->map());
    $this->assertContains("accordion: 'h'", $this->mapper->map());
    $this->assertContains("closeOnBodyClick: 'i'", $this->mapper->map());
    $this->assertContains("closeOnLinkClick: 'j'", $this->mapper->map());
    $this->assertContains("itemTriggerSubMenu: 'k'", $this->mapper->map());
  }

  public function testDefaultAnimationSpeed() {
    $collection = new ResponsiveMenu\Collections\OptionsCollection;
    $collection->add(new ResponsiveMenu\Models\Option('active_arrow_image', 'a'));
    $collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image', 'b'));
    $mapper = new ResponsiveMenu\Mappers\JsMapper($collection);
    $this->assertContains('animationSpeed: 500', $this->mapper->map());
  }

}
