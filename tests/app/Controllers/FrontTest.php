<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Models\Option;

class FrontTest extends TestCase {

  public function setUp() {
    $this->view = $this->createMock('ResponsiveMenu\View\FrontView');
    $this->service = $this->createMock('ResponsiveMenu\Services\OptionService');
    $this->menu = $this->createMock('ResponsiveMenu\ViewModels\Menu');
    $this->button = $this->createMock('ResponsiveMenu\ViewModels\Button');
    $this->collection = new OptionsCollection;

    $this->view->method('render')->willReturn('rendered');
    $this->controller = new ResponsiveMenu\Controllers\Front($this->service, $this->view, $this->menu, $this->button);

  }

  public function testPreview() {
    $this->assertEquals('rendered', $this->controller->preview());
  }

  public function testIndexShortcodeIsNotCalled() {
    $this->collection->add(new Option('shortcode', 'off'));
    $this->service->method('all')->willReturn($this->collection);
    $this->assertEquals('rendered', $this->controller->index());
  }

  public function testIndexShortcodeIsCalled() {
    $this->service->method('all')->willReturn($this->collection);
    $this->view->method('addShortcode')->willReturn('shortcode added');
    $this->assertEquals('shortcode added', $this->controller->index());
  }

}
