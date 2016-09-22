<?php

use PHPUnit\Framework\TestCase;

class OptionsCollectionTest extends TestCase {

  public function setUp() {
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
  }

  public function testAddingOptionReturnTypes() {
    $this->collection->add(new ResponsiveMenu\Models\Option('a', 'a'));
    $all_options = $this->collection->all();
    $this->assertInternalType('array', $all_options);
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $all_options['a']);
  }

  public function testAddingMultipleOptionReturnTypes() {
    $this->collection->add(new ResponsiveMenu\Models\Option('a', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('b', 'b'));
    $all_options = $this->collection->all();
    $this->assertInternalType('array', $all_options);
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $all_options['a']);
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $all_options['b']);
  }

  public function testAddingOptionGetOptionReturnTypes() {
    $this->collection->add(new ResponsiveMenu\Models\Option('a', 'a'));
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection->get('a'));
  }

  public function testAddingMultipleOptionGetOptionReturnTypes() {
    $this->collection->add(new ResponsiveMenu\Models\Option('a', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('b', 'b'));

    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection->get('a'));
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection->get('b'));
  }

  public function testUsesFontAwesomeIcons() {
    $this->assertFalse($this->collection->usesFontIcons());
  }

  public function testGetActiveArrow() {
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_image', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_image_alt', 'test-alt'));
    $this->assertEquals('<img alt="test-alt" src="test.jpg" />', $this->collection->getActiveArrow());
  }

  public function testGetActiveArrowDoesntExist() {
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_image', ''));
    $this->collection->add(new ResponsiveMenu\Models\Option('active_arrow_shape', 'arrow'));
    $this->assertEquals('arrow', $this->collection->getActiveArrow());
  }

  public function testGetInactiveArrow() {
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image_alt', 'test-alt'));
    $this->assertEquals('<img alt="test-alt" src="test.jpg" />', $this->collection->getInActiveArrow());
  }

  public function testGetInactiveArrowDoesntExist() {
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_image', ''));
    $this->collection->add(new ResponsiveMenu\Models\Option('inactive_arrow_shape', 'arrow'));
    $this->assertEquals('arrow', $this->collection->getInActiveArrow());
  }

  public function testGetInactiveTitleImage() {
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_title_image', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_title_image_alt', 'test-alt'));
    $this->assertEquals('<img alt="test-alt" src="test.jpg" />', $this->collection->getTitleImage());
  }

  public function testGetInactiveTitleImageDoesntExist() {
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_title_image', ''));
    $this->collection->add(new ResponsiveMenu\Models\Option('menu_title_image_alt', ''));
    $this->assertEquals(null, $this->collection->getTitleImage());
  }

  public function testGetButtonIcon() {
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_alt', 'test-alt'));
    $this->assertEquals('<img alt="test-alt" src="test.jpg" class="responsive-menu-button-icon responsive-menu-button-icon-active" />', $this->collection->getButtonIcon());
  }

  public function testGetButtonIconDoesntExist() {
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image', ''));
    $this->assertEquals('<span class="responsive-menu-inner"></span>', $this->collection->getButtonIcon());
  }

  public function testGetButtonIconActive() {
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image', 'test2.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_alt', 'alt-a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_when_clicked', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_alt_when_clicked', 'alt-b'));
    $this->assertEquals('<img alt="alt-b" src="test.jpg" class="responsive-menu-button-icon responsive-menu-button-icon-inactive" />', $this->collection->getButtonIconActive());
  }

  public function testGetButtonIconActiveDoesntExist() {
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image', ''));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_alt', ''));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_when_clicked', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_alt_when_clicked', 'test.jpg'));
    $this->assertEquals(null, $this->collection->getButtonIconActive());
  }

  public function testIsCollectionEmpty() {
    $this->assertTrue($this->collection->isEmpty());
  }

  public function testIsCollectionEmptyNotEmpty() {
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image', 'test.jpg'));
    $this->collection->add(new ResponsiveMenu\Models\Option('button_image_alt', 'test.jpg'));
    $this->assertFalse($this->collection->isEmpty());
  }

  public function testArrayAccessGetFunctions() {
    $this->collection->add(new ResponsiveMenu\Models\Option('a', 'a'));
    $this->collection->add(new ResponsiveMenu\Models\Option('b', 'b'));
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection['a']);
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection['b']);
  }

  public function testArrayAccessSetFunctions() {
    $this->collection['a'] = new ResponsiveMenu\Models\Option('a', 'a');
    $this->collection['b'] = new ResponsiveMenu\Models\Option('b', 'b');
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection->get('a'));
    $this->assertInstanceOf('ResponsiveMenu\Models\Option', $this->collection->get('b'));
  }

  public function testArrayAccessUnSetFunctions() {
    $this->collection['a'] = new ResponsiveMenu\Models\Option('a', 'a');
    $this->collection['b'] = new ResponsiveMenu\Models\Option('b', 'b');
    unset($this->collection['b']);
    $this->assertArrayNotHasKey('b', $this->collection);
  }

}
