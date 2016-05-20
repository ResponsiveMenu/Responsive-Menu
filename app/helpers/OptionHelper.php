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
		return isset($this->mapping[$option_name]['filter']) ?
    new $this->mapping[$option_name]['filter'] :
    new \ResponsiveMenu\Filters\HtmlFilter;
	}

	public function getType($option_name)
	{
		return isset($this->mapping[$option_name]['type']) ?
    $this->mapping[$option_name]['type'] :
    'ResponsiveMenu\Form\Text';
	}

	public function getLabel($option_name)
	{
		return isset($this->mapping[$option_name]['label']) ? $this->mapping[$option_name]['label'] : null;
	}

	public function getPosition($option_name)
	{
		return isset($this->mapping[$option_name]['position']) ? $this->mapping[$option_name]['position'] : null;
	}

	public function getData($option_name, $data)
	{
		return isset($this->mapping[$option_name]['custom'][$data]) ? $this->mapping[$option_name]['custom'][$data] : null;
	}

}
