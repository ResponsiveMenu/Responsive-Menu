<?php

use PHPUnit\Framework\TestCase;

class WpWalkerTest extends TestCase {

  public function setUp() {
    $this->getMockBuilder('\Walker_Nav_Menu')->getMock();
    $this->collection = new ResponsiveMenu\Collections\OptionsCollection;
    $this->walker = new ResponsiveMenu\Walkers\WpWalker($this->collection);
  }

  public function testEndEl() {
    $output = 'output';
    $this->walker->end_el($output, null);
    $this->assertEquals('output</li>', $output);
  }

  public function testEndLvl() {
    $output = 'output';
    $this->walker->end_lvl($output, null);
    $this->assertEquals('output</ul>', $output);
  }

  public function testStartLvlNoOptions() {
    $output = 'output';
    $this->walker->start_lvl($output, 1);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-2'>", $output);
  }

  public function testStartLvlWithExpandSubMenusOff() {
    $this->collection['auto_expand_all_submenus'] = 'off';
    $output = 'output';
    $this->walker->start_lvl($output, 1);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-2'>", $output);
  }

  public function testStartLvlWithExpandSubMenusOn() {
    $this->collection['auto_expand_all_submenus'] = 'on';
    $output = 'output';
    $this->walker->start_lvl($output, 2);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-3 responsive-menu-submenu-open'>", $output);
  }

  public function testStartLvlWithExpandCurrentSubMenusOff() {
    $current_item = new \StdClass;
    $current_item->current_item_ancestor = true;
    $current_item->current_item_parent = true;
    $this->walker->setCurrentItem($current_item);

    $this->collection['auto_expand_current_submenus'] = 'off';
    $output = 'output';
    $this->walker->start_lvl($output, 4);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-5'>", $output);
  }

  public function testStartLvlWithExpandCurrentSubMenusOn() {
    $current_item = new \StdClass;
    $current_item->current_item_ancestor = true;
    $current_item->current_item_parent = true;
    $this->walker->setCurrentItem($current_item);

    $this->collection['auto_expand_current_submenus'] = 'on';
    $output = 'output';
    $this->walker->start_lvl($output, 3);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-4 responsive-menu-submenu-open'>", $output);
  }

  public function testStartLvlWithExpandCurrentSubMenusOnCurrentItemFalse() {
    $current_item = new \StdClass;
    $current_item->current_item_ancestor = false;
    $current_item->current_item_parent = false;
    $this->walker->setCurrentItem($current_item);

    $this->collection['auto_expand_current_submenus'] = 'on';
    $output = 'output';
    $this->walker->start_lvl($output, 3);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-4'>", $output);
  }

  public function testStartLvlWithExpandCurrentSubMenusOnCurrentItemFalseAndTrue() {
    $current_item = new \StdClass;
    $current_item->current_item_ancestor = false;
    $current_item->current_item_parent = true;
    $this->walker->setCurrentItem($current_item);

    $this->collection['auto_expand_current_submenus'] = 'on';
    $output = 'output';
    $this->walker->start_lvl($output, 3);
    $this->assertEquals("output<ul class='responsive-menu-submenu responsive-menu-submenu-depth-4 responsive-menu-submenu-open'>", $output);
  }

}
