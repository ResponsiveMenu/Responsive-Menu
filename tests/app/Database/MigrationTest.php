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

    public function testMinorPointUpgradeMigrationScriptsAreReturned() {
        $migration = new Migration($this->manager, '0.0.1', '0.0.2', $this->defaults);
        $classes = $migration->getMigrationClasses();

        $this->assertCount(1, $classes);
        $this->assertArrayHasKey('0.0.1', $classes);
    }

    public function testMajorPointUpgradeMigrationScriptsAreReturned() {
        $migration = new Migration($this->manager, '0.8.9', '1.1.1', $this->defaults);
        $classes = $migration->getMigrationClasses();

        $this->assertCount(1, $classes);
        $this->assertArrayHasKey('1.1.0', $classes);
    }

    public function testNotUpgradeMigrationScriptsAreReturnedIfNewInstall() {
        $migration = new Migration($this->manager, '', '1.1.1', $this->defaults);
        $classes = $migration->getMigrationClasses();

        $this->assertCount(0, $classes);
        $this->assertArrayNotHasKey('0.0.1', $classes);
    }

    public function testMultipleUpgradeMigrationScriptsAreReturned() {
        $migration = new Migration($this->manager, '0.0.1', '1.1.1', $this->defaults);
        $classes = $migration->getMigrationClasses();

        $this->assertCount(4, $classes);
        $this->assertArrayHasKey('0.0.1', $classes);
        $this->assertArrayHasKey('0.0.2', $classes);
        $this->assertArrayHasKey('0.0.5', $classes);
        $this->assertArrayHasKey('1.1.0', $classes);
    }

    public function testMigrationScriptUpdatesOptions() {
        $migration = new Migration($this->manager, '0.0.1', '0.0.2', $this->defaults);

        $options = new OptionsCollection([
            'foo' => 'bar',
            'baz' => 'qux',
            'moon' => 'rise'
        ]);

        foreach($migration->getMigrationClasses() as $migration)
            $migration->migrate($options);

        $this->assertEquals('qux', $options['foo']);
        $this->assertEquals('qux', $options['baz']);
        $this->assertEquals('rise', $options['moon']);
    }

    public function testMigrationScriptChainsUpdatesOptions() {
        $migration = new Migration($this->manager, '0.0.1', '1.1.1', $this->defaults);

        $options = new OptionsCollection([
            'foo' => 'bar',
            'baz' => 'qux',
            'moon' => 'rise'
        ]);

        foreach($migration->getMigrationClasses() as $migration)
            $migration->migrate($options);

        $this->assertEquals('qux', $options['foo']);
        $this->assertEquals('qux', $options['baz']);
        $this->assertEquals('qux', $options['moon']);
    }

    public function testMigrationScriptFunctionsAreCalled() {
        $migration = new Migration($this->manager, '0.0.1', '0.0.2', $this->defaults);

        $options = new OptionsCollection([
            'foo' => 'bar',
            'baz' => 'qux',
            'moon' => 'rise',
            'sun' => [
                [5, 'foo', 'bar'],
                [6, 'baz', 'qux'],
                [7, 'moon', 'rise']
            ]
        ]);

        foreach($migration->getMigrationClasses() as $migration)
            $migration->migrate($options);

        $this->assertEquals('qux', $options['foo']);
        $this->assertEquals('qux', $options['baz']);
        $this->assertEquals('rise', $options['moon']);

        $expected_sun = [
            [5, 'foo'],
            [6, 'baz'],
            [7, 'moon']
        ];
        $this->assertEquals(json_encode($expected_sun), $options['sun']);
    }

    public function testMigrationScriptFunctionsAreChained() {
        $migration = new Migration($this->manager, '0.0.1', '1.1.1', $this->defaults);

        $options = new OptionsCollection([
            'foo' => 'bar',
            'baz' => 'qux',
            'moon' => 'rise',
            'sun' => [
                [5, 'foo', 'bar'],
                [6, 'baz', 'qux'],
                [7, 'moon', 'rise']
            ]
        ]);

        foreach($migration->getMigrationClasses() as $migration)
            $migration->migrate($options);

        $this->assertEquals('qux', $options['foo']);
        $this->assertEquals('qux', $options['baz']);
        $this->assertEquals('qux', $options['moon']);

        $expected_sun = [
            [5],
            [6],
            [7]
        ];
        $this->assertEquals(json_encode($expected_sun), $options['sun']);
    }

    public function testNoMigrationScriptsAreNotRunIfNewInstall() {
        $migration = new Migration($this->manager, '', '1.1.1', $this->defaults);

        $options = new OptionsCollection([
            'foo' => 'bar',
            'baz' => 'qux',
            'moon' => 'rise',
        ]);

        foreach($migration->getMigrationClasses() as $migration)
            $migration->migrate($options);

        $this->assertEquals('bar', $options['foo']);
        $this->assertEquals('qux', $options['baz']);
        $this->assertEquals('rise', $options['moon']);
    }

}
