<?php

namespace ResponsiveMenu\Models;
use ResponsiveMenu\Helpers\OptionHelper as Helper;

class Option
{
	private $value;
	private $name;
	private $helper;
  private $default;

	public function __construct($name, $value, Helper $helper, $default)
	{
		$this->name = $name;
		$this->value = $value;
		$this->helper = $helper;
    $this->default = $default;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value)
	{
		return $this->value = $value;
	}

	public function getOption()
	{
		return $this->value;
	}

	public function getType()
	{
		return $this->helper->getType($this->name);
	}

	public function getPosition()
	{
		return $this->helper->getPosition($this->name);
	}

	public function getFilter()
	{
		return $this->helper->getFilter($this->name);
	}

	public function __toString()
	{
		return isset($this->value) ? $this->value : '';
	}

	public function getBasePosition()
	{
		$position = $this->getPosition();
		$positions = explode('.', $position);
		return isset($positions[1]) ? $positions[1] : null;
	}

	public function getData($data)
	{
		return $this->helper->getData($this->name, $data);
	}

	public function hasLabel()
	{
		return $this->getLabel() ? true : false;
	}

	public function getLabel()
	{
		return $this->helper->getLabel($this->name);
	}

  public function getDefault()
  {
    return $this->default;
  }

}
