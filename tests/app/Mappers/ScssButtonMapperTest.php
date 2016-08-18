<?php

use PHPUnit\Framework\TestCase;

class ScssButtonMapperTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->collection->add(new ResponsiveMenu\Models\Option('breakpoint', 444));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_line_height', 555));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_line_margin', 50));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_line_colour', '#fff'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_line_width', 50));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_width', 777));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_height', 888));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_click_animation', 50));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_transparent_background', 'on'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_background_colour', '#fff'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_background_colour_hover', '#fff'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_position_type', 'left'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_top', 50));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_left_or_right', 'left'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_distance_from_side', 5));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_line_colour', '#fff'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_text_colour', '#fff'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_font_size', 5));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_title_line_height', 5));
    $this->collection->add(new ResponsiveMenu\Models\Option('animation_speed', 5));
    $this->collection->add(new ResponsiveMenu\Models\Option('transition_speed', 5));
    $this->mapper = new ResponsiveMenu\Mappers\ScssButtonMapper($this->collection);
  }

  public function testThis() {
    $this->assertContains('height: 555px;', $this->mapper->map());
    $this->assertContains('height: 888px;', $this->mapper->map());
    $this->assertContains('width: 777px;', $this->mapper->map());
    $this->assertContains('max-width: 444px', $this->mapper->map());
  }

}
