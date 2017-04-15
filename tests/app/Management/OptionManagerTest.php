<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Management\OptionManager;
use ResponsiveMenu\Collections\OptionsCollection;

class OptionManagerTest extends TestCase {

    private $db;
    private $default_options = [
        'foo' => 'bar',
        'baz' => 'qux'
    ];

    public function setUp() {
        $this->db = $this->createMock('ResponsiveMenu\Database\Database');
    }

    public function testUpdateOptionsReturnsCorrectly() {
        $manager = new OptionManager($this->db, $this->default_options);
        $to_update = ['foo' => 'new', 'moon' => 'rise'];

        $updated = $manager->updateOptions($to_update);

        $this->assertInstanceOf('ResponsiveMenu\Collections\OptionsCollection', $updated);

        $expected = new OptionsCollection([
            'foo' => 'new',
            'baz' => 'qux',
            'moon' => 'rise'
        ]);

        $this->assertEquals($expected, $updated);
    }

    public function testCreateOptionsReturnsCorrectly() {
        $manager = new OptionManager($this->db, $this->default_options);
        $to_create = ['moon' => 'rise'];

        $updated = $manager->createOptions($to_create);

        $this->assertInstanceOf('ResponsiveMenu\Collections\OptionsCollection', $updated);

        $expected = new OptionsCollection([
            'foo' => 'bar',
            'baz' => 'qux',
            'moon' => 'rise'
        ]);

        $this->assertEquals($expected, $updated);
    }

    public function testRemoveOptionsReturnsCorrectly() {
        $manager = new OptionManager($this->db, $this->default_options);
        $to_remove = ['foo' => 'new', 'moon' => 'rise'];

        $updated = $manager->removeOptions($to_remove);

        $this->assertInstanceOf('ResponsiveMenu\Collections\OptionsCollection', $updated);

        $expected = new OptionsCollection([
            'baz' => 'qux'
        ]);

        $this->assertEquals($expected, $updated);
    }

    public function testBuildFromArrayReturnsCorrectly() {
        $manager = new OptionManager($this->db, $this->default_options);
        $to_remove = ['foo' => 'new', 'moon' => 'rise'];

        $updated = $manager->buildFromArray($to_remove);

        $this->assertInstanceOf('ResponsiveMenu\Collections\OptionsCollection', $updated);

        $expected = new OptionsCollection([
            'foo' => 'new',
            'baz' => 'qux',
            'moon' => 'rise'
        ]);

        $this->assertEquals($expected, $updated);
    }

}