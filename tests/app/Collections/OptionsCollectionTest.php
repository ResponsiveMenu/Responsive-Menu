<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Collections\OptionsCollection;

class OptionsCollectionTest extends TestCase {

    private $options = [
        'menu_theme' => false,
        'foo' => 'bar',
        'baz' => 'moo'
    ];

    public function testCreationFromConstructor() {
        $collection = new OptionsCollection($this->options);
        $this->assertCount(3, $collection);
    }

    public function testAddingOptions() {
        $collection = new OptionsCollection($this->options);
        $this->assertCount(3, $collection);

        $collection->add(['moon' => 'rise']);
        $this->assertCount(4, $collection);
    }

    public function testAccessViaArray() {
        $collection = new OptionsCollection($this->options);
        $this->assertEquals('bar', $collection['foo']);
        $this->assertEquals('moo', $collection['baz']);
    }

    public function testRemoveViaArray() {
        $collection = new OptionsCollection($this->options);
        $this->assertCount(3, $collection);

        unset($collection['foo']);

        $this->assertCount(2, $collection);
        $this->assertNull($collection['foo']);
    }

    public function testSetViaArray() {
        $collection = new OptionsCollection($this->options);
        $this->assertCount(3, $collection);

        $collection['moon'] = 'rise';

        $this->assertCount(4, $collection);
        $this->assertEquals('rise', $collection['moon']);
    }

    public function testReturnArrayWhenAsked() {
        $collection = new OptionsCollection($this->options);
        $this->assertInternalType('array', $collection->toArray());
        $this->assertEquals($this->options, $collection->toArray());
    }

    public function testStringIsAlwaysReturnedFromConstructor() {
        $array = ['array' => ['moon' => 'rise']];
        $collection = new OptionsCollection($array);

        $this->assertEquals(json_encode($array['array']), $collection['array']);
    }

    public function testStringIsAlwaysReturned() {
        $collection = new OptionsCollection($this->options);
        $array = ['array' => ['moon' => 'rise']];
        $collection->add($array);

        $this->assertEquals(json_encode($array['array']), $collection['array']);
        $this->assertEquals('bar', $collection['foo']);
    }

    public function testCorrectActiveArrowIsReturned() {
        $collection = new OptionsCollection($this->options);
        $collection->add(['active_arrow_image' => '']);
        $collection->add(['active_arrow_image_alt' => '']);
        $collection->add(['active_arrow_shape' => 'foo']);

        $this->assertEquals('foo', $collection->getActiveArrow());

        $collection->add(['active_arrow_image' => 'bar']);
        $collection->add(['active_arrow_image_alt' => 'baz']);
        $this->assertEquals('<img alt="baz" src="bar" />', $collection->getActiveArrow());
    }

    public function testCorrectInActiveArrowIsReturned() {
        $collection = new OptionsCollection($this->options);
        $collection->add(['inactive_arrow_image' => '']);
        $collection->add(['inactive_arrow_image_alt' => '']);
        $collection->add(['inactive_arrow_shape' => 'foo']);

        $this->assertEquals('foo', $collection->getInActiveArrow());

        $collection->add(['inactive_arrow_image' => 'bar']);
        $collection->add(['inactive_arrow_image_alt' => 'baz']);
        $this->assertEquals('<img alt="baz" src="bar" />', $collection->getInActiveArrow());
    }

    public function testCorrectTitleImageReturned() {
        $collection = new OptionsCollection($this->options);
        $collection->add(['menu_title_image' => '']);

        $this->assertNull($collection->getTitleImage());

        $collection->add(['menu_title_image' => 'bar']);
        $collection->add(['menu_title_image_alt' => 'baz']);
        $this->assertEquals('<img alt="baz" src="bar" />', $collection->getTitleImage());
    }

    public function testCorrectButtonIconReturned() {
        $collection = new OptionsCollection($this->options);
        $collection->add(['button_image' => '']);

        $this->assertEquals('<span class="responsive-menu-inner"></span>', $collection->getButtonIcon());

        $collection->add(['button_image' => 'foo']);
        $collection->add(['button_image_alt' => 'bar']);
        $this->assertEquals('<img alt="bar" src="foo" class="responsive-menu-button-icon responsive-menu-button-icon-active" />', $collection->getButtonIcon());
    }

    public function testCorrectActiveButtonIconReturned() {
        $collection = new OptionsCollection($this->options);
        $collection->add(['button_image' => '']);

        $this->assertNull($collection->getButtonIconActive());

        $collection->add(['button_image' => 'foo']);
        $collection->add(['button_image_when_clicked' => 'bar']);
        $collection->add(['button_image_alt_when_clicked' => 'baz']);
        $this->assertEquals('<img alt="baz" src="bar" class="responsive-menu-button-icon responsive-menu-button-icon-inactive" />', $collection->getButtonIconActive());
    }

}