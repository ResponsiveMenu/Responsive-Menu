<?php

use PHPUnit\Framework\TestCase;

class ScssBaseMapperTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->scss = new scssc_free;
    $this->mapper = new ResponsiveMenu\Mappers\ScssBaseMapper($this->scss);
  }

  public function testThis() {
    $this->collection->add(new ResponsiveMenu\Models\Option('breakpoint', 6000));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_text_alignment', 'right'));
    $mapped = $this->mapper->map($this->collection);
    $mapped = $this->mapper->map($this->collection);
    $this->assertContains('max-width: 6000px)', $mapped);
    $this->assertContains('padding-right: 10%', $mapped);
    $this->assertContains('padding-right: 15%', $mapped);
  }

  public function testThat() {
    $this->collection->add(new ResponsiveMenu\Models\Option('breakpoint', 3000));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_text_alignment', 'left'));
    $mapped = $this->mapper->map($this->collection);
    $this->assertContains('max-width: 3000px)', $mapped);
    $this->assertContains('padding-left: 10%', $mapped);
    $this->assertContains('padding-left: 15%', $mapped);
  }

}
