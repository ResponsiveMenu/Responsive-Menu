<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Services\OptionService;

class OptionsServiceTest extends TestCase {

    public function setUp() {
      $this->repository = $this->createMock('ResponsiveMenu\Repositories\OptionRepository');
      $this->factory = $this->createMock('ResponsiveMenu\Factories\OptionFactory');
      $this->wpml = $this->createMock('ResponsiveMenu\WPML\WPML');

      $this->service = new OptionService($this->repository, $this->factory, $this->wpml);
    }

    public function testCombiningBasicOptions() {
      $this->assertSame(['one' => 1, 'two' => 'two'], $this->service->combineOptions(['one' => 1],['two' => 'two']));
    }

    public function testCombiningStringZeroOptions() {
      $this->assertSame(['one' => '0'], $this->service->combineOptions(['one' => 'default'],['one' => '0']));
    }

    public function testCombiningIntegerZeroOptions() {
      $this->assertSame(['one' => 0], $this->service->combineOptions(['one' => 'default'],['one' => 0]));
    }

    public function testOverwritingDefaultOptionValue() {
      $this->assertSame(['one' => 'updated'], $this->service->combineOptions(['one' => 'default'],['one' => 'updated']));
    }

}
