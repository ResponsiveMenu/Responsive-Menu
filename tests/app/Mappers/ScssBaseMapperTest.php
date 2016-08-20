<?php

use PHPUnit\Framework\TestCase;

class ScssBaseMapperTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->collection->add(new ResponsiveMenu\Models\Option('breakpoint', 6000));
    $this->scss = new scssc;
    $this->mapper = new ResponsiveMenu\Mappers\ScssBaseMapper($this->scss);
  }

  public function testThis() {
    $mapped = $this->mapper->map($this->collection);
    $this->assertContains('max-width: 6000px)', $mapped);
  }

}
