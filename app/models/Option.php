<?php

namespace ResponsiveMenu\Models;
use ResponsiveMenu\Helpers\OptionHelper as Helper;

class Option
{
	private $value;
	private $name;
	private $helper;

	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

  public function setHelper(Helper $helper)
  {
    $this->helper = $helper;
  }

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->helper->getFilter($this->name)->filter($this->value);
	}

	public function getRawValue()
	{
		return is_array($this->value) ? json_encode($this->value) : $this->value;
	}

	public function setValue($value)
	{
		return $this->value = $value;
	}

  public function isPro()
  {
    return $this->helper->isPro($this->name);
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
		return $this->getValue();
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

}
