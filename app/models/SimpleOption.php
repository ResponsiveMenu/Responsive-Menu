<?php

namespace ResponsiveMenu\Models;

class SimpleOption implements Option {

	private $value;
	private $name;

	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;
	}

  public function __toString()
	{
		return (string) $this->value;
	}
  
}
