<?php

use PHPUnit\Framework\TestCase;

class OptionRepositoryTest extends TestCase {

  public function setUp() {
      $this->db = $this->createMock('ResponsiveMenu\Database\WpDatabase');
      $this->factory = $this->createMock('ResponsiveMenu\Factories\OptionFactory');
      $this->defaults = ['a' => 1, 'b' => 2, 'c' => 3];

      $this->repo = new ResponsiveMenu\Repositories\OptionRepository($this->db, $this->factory, $this->defaults);
  }

  public function testCollectionReturned() {
    $obj_1 = new \StdClass;
    $obj_1->name = 'a';
    $obj_1->value = 1;

    $obj_2 = new \StdClass;
    $obj_2->name = 'b';
    $obj_2->value = 2;

    $this->db->method('all')->willReturn([$obj_1, $obj_2]);

    $this->factory->method('build')->will($this->onConsecutiveCalls(
      new ResponsiveMenu\Models\Option('a', 1),
      new ResponsiveMenu\Models\Option('b', 2)
    ));

    $this->assertInstanceOf('ResponsiveMenu\Collections\OptionsCollection', $this->repo->all());
  }

  public function testCollectionCountIsReturned() {
    $obj_1 = new \StdClass;
    $obj_1->name = 'a';
    $obj_1->value = 1;

    $obj_2 = new \StdClass;
    $obj_2->name = 'b';
    $obj_2->value = 2;

    $this->db->method('all')->willReturn([$obj_1, $obj_2]);

    $this->factory->method('build')->will($this->onConsecutiveCalls(
      new ResponsiveMenu\Models\Option('a', 1),
      new ResponsiveMenu\Models\Option('b', 2)
    ));

    $this->assertEquals(2, count($this->repo->all()->all()));
  }

  public function testDbUpdateIsCalled() {
    $option = $this->createMock('ResponsiveMenu\Models\Option');
    $option->method('getFiltered')->willReturn(1);
    $option->method('getName')->willReturn('a');
    $this->db->method('update')->willReturn(true);
    $this->assertTrue($this->repo->update($option));
  }

  public function testDbCreateIsCalled() {
    $option = $this->createMock('ResponsiveMenu\Models\Option');
    $option->method('getFiltered')->willReturn(1);
    $option->method('getName')->willReturn('a');
    $this->db->method('insert')->willReturn(true);
    $this->db->method('mySqlTime')->willReturn('123');
    $this->assertTrue($this->repo->create($option));
  }

  public function testDbRemoveIsCalled() {
    $this->db->method('delete')->willReturn(true);
    $this->assertTrue($this->repo->remove('a'));
  }

  public function testBuildFromArray() {
    $opt_1 = new ResponsiveMenu\Models\Option('a', 4);
    $opt_1->setFilter($this->createMock('ResponsiveMenu\Filters\TextFilter'));

    $opt_2 = new ResponsiveMenu\Models\Option('d', 6);
    $opt_2->setFilter($this->createMock('ResponsiveMenu\Filters\TextFilter'));

    $this->factory->method('build')->will($this->onConsecutiveCalls($opt_1, $opt_2, $opt_1, $opt_2));

    $this->assertInstanceOf('ResponsiveMenu\Collections\OptionsCollection', $this->repo->buildFromArray(['a' => 4, 'd' => 6]));
  }

}
