<?php

use PHPUnit\Framework\TestCase;

class ScssBaseMapperTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->collection->add(new ResponsiveMenu\Models\Option('breakpoint', 6000));
    $this->mapper = new ResponsiveMenu\Mappers\ScssBaseMapper($this->collection);
  }

  public function testThis() {
    $this->assertContains('max-width: 6000px)', $this->mapper->map());
  }

}
