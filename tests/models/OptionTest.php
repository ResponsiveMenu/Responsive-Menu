<?php

namespace ResponsiveMenu\Models;
use ResponsiveMenu\Helpers\OptionHelper as Helper;

class OptionTest extends \PHPUnit_Framework_TestCase
{

  public function setUp()
  {
    $mapping_one = array('model_one' => array(
      'position' => 'first.second',
      'type' => 'ResponsiveMenu\Form\Select',
      'label' => 'Test Label',
      'custom' => array('test' => 'result')
      ));

    $mapping_two = array('model_two' => array(
      'position' => 'test.test',
      'filter' => 'ResponsiveMenu\Filters\HtmlFilter'
    ));

    $this->option_model_one = new Option('model_one', 'test one', new Helper($mapping_one), 7);
    $this->option_model_two = new Option('model_two', 2, new Helper($mapping_two), 8);

  }

  public function testGetName()
  {
    $this->assertEquals($this->option_model_one->getName(), 'model_one');
    $this->assertEquals($this->option_model_two->getName(), 'model_two');
  }

  public function testGetPosition()
  {
    $this->assertEquals($this->option_model_one->getPosition(), 'first.second');
  }

  public function testGetBasePosition()
  {
    $this->assertEquals($this->option_model_one->getBasePosition(), 'second');
  }

  public function testGetType()
  {
    $this->assertEquals($this->option_model_one->getType(), 'ResponsiveMenu\Form\Select');
  }

  public function testGetVariousData()
  {
    $this->assertEquals($this->option_model_one->getData('test'), 'result');
  }

  public function testDefaultTypeIsReturned()
  {
    $this->assertEquals($this->option_model_two->getType(), 'ResponsiveMenu\Form\Text');
  }

  public function testIsLabelSet()
  {
    $this->assertTrue($this->option_model_one->hasLabel());
    $this->assertFalse($this->option_model_two->hasLabel());
  }

  public function testGetLabel()
  {
    $this->assertEquals($this->option_model_one->getLabel(), 'Test Label');
  }

  public function testGetValue()
  {
    $this->assertEquals($this->option_model_one->getValue(), 'test one');
    $this->assertEquals($this->option_model_two->getValue(), 2);

    $this->option_model_one->setValue('test changed');
    $this->assertEquals($this->option_model_one->getValue(), 'test changed');
  }

  public function testGetDefault()
  {
    $this->assertEquals($this->option_model_one->getDefault(), 7);
    $this->assertEquals($this->option_model_two->getDefault(), 8);
  }

  public function testGetFilter()
  {
    $this->assertInstanceOf('ResponsiveMenu\Filters\TextFilter', $this->option_model_one->getFilter());
    $this->assertInstanceOf('ResponsiveMenu\Filters\HtmlFilter', $this->option_model_two->getFilter());
  }

}
