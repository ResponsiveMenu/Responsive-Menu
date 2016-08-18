<?php

namespace ResponsiveMenu\Models;
use ResponsiveMenu\Filters\Filter;

class Option {

	private $value;
	private $name;
  private $filter;

	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

  public function setFilter(Filter $filter) {
    $this->filter = $filter;
  }

	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

  public function getFiltered() {
    return $this->filter->filter($this->value);
  }

	public function getFilter() {
		return $this->filter;
	}

	public function __toString() {
		return (string) $this->value;
	}

}
