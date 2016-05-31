<?php

namespace ResponsiveMenu\Helpers;

class OptionHelper
{
	private $mapping;

	public function __construct(array $mapping)
	{
		$this->mapping = $mapping;
	}

	public function getFilter($option_name)
	{
		return isset($this->mapping['filter']) ? new $this->mapping['filter'] : new \ResponsiveMenu\Filters\TextFilter;
	}

	public function getType($option_name)
	{
		return isset($this->mapping['type']) ? $this->mapping['type'] : 'ResponsiveMenu\Form\Text';
	}

	public function getLabel($option_name)
	{
		return isset($this->mapping['label']) ? $this->mapping['label'] : null;
	}

	public function isPro($option_name)
	{
		return isset($this->mapping['pro']) ? true : false;
	}

	public function getPosition($option_name)
	{
		return isset($this->mapping['position']) ? $this->mapping['position'] : null;
	}

	public function getData($option_name, $data)
	{
		return isset($this->mapping['custom'][$data]) ? $this->mapping['custom'][$data] : null;
	}

}
