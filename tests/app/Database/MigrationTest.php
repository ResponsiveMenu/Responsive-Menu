<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Database\Migration;
use ResponsiveMenu\Models\Option;

class MigrationTest extends TestCase {

    public function setUp() {
      $this->database = $this->createMock('ResponsiveMenu\Database\WpDatabase');
      $this->service = $this->createMock('ResponsiveMenu\Services\OptionService');
      $this->defaults = ['default_one' => 1, 'default_two' => 'string', 'default_three' => 4.5, 'default_four' => 'new'];
      $this->current_version = '3.0.8';
      $this->old_version = '3.0.7';
      $this->old_options = ['default_one' => 4, 'default_two' => 'old string', 'default_three' => 4.5, 'RM' => 'old RM value', 'RMDepth' => 'old RMDepth value'];

      $this->base_migration = new Migration($this->database, $this->service, $this->defaults, $this->current_version, $this->old_version, $this->old_options);

      $this->options_collection = new OptionsCollection;
      $this->options_collection->add(new Option('default_one', 5));
      $this->options_collection->add(new Option('default_two', 'string'));
      $this->options_collection->add(new Option('default_three', 7.5));

      /*
      * Mock the repository all() function to return controlled options collection
      */
      $service_options = new OptionsCollection;
      $service_options->add(new Option('default_one', 5));
      $service_options->add(new Option('default_two', 'new option'));
      $service_options->add(new Option('to_delete', 'delete me!'));
      $service_options->add(new Option('to_delete_also', 'delete me too!'));
      $this->service->method('all')->willReturn($service_options);

    }

    public function testVersionCompareNeedsUpdate() {
      $this->assertTrue($this->base_migration->needsUpdate());
    }

    public function testVersionCompareDoesntNeedUpdate() {
      $migration = new Migration($this->database, $this->service, $this->defaults, $this->current_version, '3.0.9', $this->old_options);
      $this->assertFalse($migration->needsUpdate());
    }

    public function testVersionCompareNeedUpdateWithDoubleEndPoint() {
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.0.10', $this->old_version, $this->old_options);
      $this->assertTrue($migration->needsUpdate());
    }

    public function testVersionCompareDoesntNeedUpdateWithDoubleEndPoint() {
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.0.10', '3.1.0', $this->old_options);
      $this->assertFalse($migration->needsUpdate());
    }

    public function testVersionCompareDoesNeedUpdateWithTenComparedToOne() {
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.0.10', '3.0.1', $this->old_options);
      $this->assertTrue($migration->needsUpdate());
    }

    public function testVersionCompareDoesntNeedUpdateWithOneComparedToTen() {
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.0.1', '3.0.10', $this->old_options);
      $this->assertFalse($migration->needsUpdate());
    }

    public function testVersionCompareDoesntNeedUpdateWithDoubleEndPointWithVersionHigher() {
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.0.10', '3.2.0', $this->old_options);
      $this->assertFalse($migration->needsUpdate());
    }

    public function testVersionCompareDoesNeedUpdateWithDoubleEndPointWithVersionHigher() {
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.2.0', '3.0.10', $this->old_options);
      $this->assertTrue($migration->needsUpdate());
    }

    public function testNewOptionsReturnedAreCorrect() {
      $this->assertSame(['default_four' => 'new'], $this->base_migration->getNewOptions($this->options_collection));
    }

    public function testIsVersion3CheckReturnsFalse() {
      $migration = new Migration($this->database, $this->service, $this->defaults, $this->current_version, '2.8.0', $this->old_options);
      $this->assertFalse($migration->isVersion3());
    }

    public function testIsVersion3CheckReturnsTrue() {
      $this->assertTrue($this->base_migration->isVersion3());
    }

    public function testDeletableOptions() {
      $this->assertSame(['to_delete' => 'to_delete', 'to_delete_also' => 'to_delete_also'], $this->base_migration->getOptionsToDelete());
    }

    public function testOptionsToMigrate() {
      $this->assertSame(['menu_to_use' => 'old RM value', 'menu_depth' => 'old RMDepth value'], $this->base_migration->getMigratedOptions());
    }

    public function testSetup() {
      $this->database->method('createTable')->willReturn(true);
      $migration = new Migration($this->database, $this->service, $this->defaults, '3.0.10', '2.8.9', $this->old_options);
      $this->assertEquals(null, $migration->setUp());
    }

    public function testSynchronise() {
      $this->assertEquals(null, $this->base_migration->synchronise());
    }

    public function testAddNewOptionsEmpty() {
      $service = $this->createMock('ResponsiveMenu\Services\OptionService');
      $service->method('all')->willReturn(new ResponsiveMenu\Collections\OptionsCollection);
      $migration = new Migration($this->database, $service, $this->defaults, $this->current_version, $this->old_version, $this->old_options);
      $this->assertEquals(null, $migration->addNewOptions());
    }

}
