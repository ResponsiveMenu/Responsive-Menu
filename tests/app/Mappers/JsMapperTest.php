<?php

use PHPUnit\Framework\TestCase;

class JsMapperTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->collection->add(new ResponsiveMenu\Models\Option('animation_speed', 5));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_click_trigger', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_image', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_image_alt', 'n'));
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image', 'b'));
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image_alt', 'm'));
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

    $this->mapper = new ResponsiveMenu\Mappers\JsMapper;
  }

  public function testOutput() {
    $mapped = $this->mapper->map($this->collection);
    $this->assertContains('animationSpeed: 5000', $mapped);
    $this->assertContains("trigger: 'd'", $mapped);
    $this->assertContains('breakpoint: c', $mapped);
    $this->assertContains("pushButton: 'l'", $mapped);
    $this->assertContains("animationType: 'e'", $mapped);
    $this->assertContains("animationSide: 'f'", $mapped);
    $this->assertContains("pageWrapper: 'g'", $mapped);
    $this->assertContains("accordion: 'h'", $mapped);
    $this->assertContains("closeOnBodyClick: 'i'", $mapped);
    $this->assertContains("closeOnLinkClick: 'j'", $mapped);
    $this->assertContains("itemTriggerSubMenu: 'k'", $mapped);
    $this->assertContains('alt="m"', $mapped);
    $this->assertContains('alt="n"', $mapped);
  }

  public function testDefaultAnimationSpeed() {
    $collection = new ResponsiveMenu\Collections\OptionsCollection;
    $collection->add(new ResponsiveMenu\Models\Option('active_arrow_image', 'a'));
    $collection->add(new ResponsiveMenu\Models\Option('active_arrow_image_alt', 'c'));
    $collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image', 'b'));
    $collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image_alt', 'd'));
    $mapper = new ResponsiveMenu\Mappers\JsMapper;
    $mapped = $this->mapper->map($collection);
    $this->assertContains('animationSpeed: 500', $mapped);
    $this->assertContains('alt="c"', $mapped);
    $this->assertContains('alt="d"', $mapped);
  }

}
