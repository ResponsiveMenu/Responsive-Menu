<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Database\Migration;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Management\OptionManager;

class MigrationTest extends TestCase {

    private $manager;
    private $defaults = [
        'foo' => 'bar',
        'baz' => 'qux'
    ];

    public function setUp() {
        $db = $this->createMock('ResponsiveMenu\Database\Database');
        $db->method('all')->willReturn(['moon' => 'rise']);
        $this->manager = new OptionManager($db, ['foo' => 'bar', 'river' => 'run']);
    }

    public function tableVersions() {
        return [

            // These don't require a table
            ['3.0.1', false],
            ['3.1.2', false],
            ['4.5.1', false],
            ['3.0.0', false],

            // These do require a table
            ['2.0.0', true],
            ['2.0.1', true],
            ['2.8.9', true],
            ['1.2.0', true],
            ['2.9.5', true],

        ];
    }
    /**
     * @dataProvider tableVersions
     */
    public function testNeedsTable($version, $expected) {
        $migration = new Migration($this->manager, $version, '3.0.1', $this->defaults);
        $this->assertEquals($expected, $migration->needsTable());
    }

    public function updateVersions() {
        return [
            ['3.0.1', '3.1.0', true],
            ['2.8.9', '3.0.0', true],
            ['1.6.4', '4.1.0', true],
            ['3.1.1', '4.5.3', true],
            ['3.0.1', '3.0.2', true],
            ['3.1.1', '3.1.2', true],

            ['3.3.1', '3.1.2', false],
            ['2.8.1', '1.3.2', false],
            ['1.4.1', '1.1.2', false],
            ['4.1.2', '3.1.2', false]
        ];
    }
    /**
     * @dataProvider updateVersions
     */
    public function testNeedsUpdate($old_version, $new_version, $expected) {
        $migration = new Migration($this->manager, $old_version, $new_version, $this->defaults);
        $this->assertEquals($expected, $migration->needsUpdate());
    }

    public function testAddingNewOptions() {
        $migration = new Migration($this->manager, '3.0.0', '3.0.0', $this->defaults);
        $collection = new OptionsCollection(['foo' => 'bar', 'river' => 'run', 'baz' => 'qux']);
        $this->assertEquals($collection, $migration->addNewOptions());
    }

    public function testRemoveOldOptions() {
        $migration = new Migration($this->manager, '3.0.0', '3.0.0', $this->defaults);
        $collection = new OptionsCollection(['foo' => 'bar', 'river' => 'run']);
        $this->assertEquals($collection, $migration->tidyUpOptions());
    }
}
