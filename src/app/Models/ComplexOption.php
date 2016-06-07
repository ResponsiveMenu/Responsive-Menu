<?php

namespace ResponsiveMenu\Models;
use ResponsiveMenu\Filters\Filter as Filter;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class ComplexOption implements Option {

	private $value;
	private $name;
  private $filter;
  private $form_component;
  private $is_pro;
  private $position;
  private $data;
  private $label;

	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

  public function setFilter(Filter $filter)
  {
    $this->filter = $filter;
  }

  public function setFormComponent(FormComponent $form_component)
  {
    $this->form_component = $form_component;
  }

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;
	}

  public function getFiltered() {
    return $this->filter->filter($this->value);
  }

	public function setValue($value)
	{
		return $this->value = $value;
	}

	public function setIsPro($is_pro = false)
	{
		$this->is_pro = $is_pro;
	}

  public function isPro()
  {
    return $this->is_pro;
  }

	public function getFormComponent()
	{
		return $this->form_component;
	}

	public function setPosition($position = null)
	{
		return $this->position = $position;
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function getFilter()
	{
		return $this->filter;
	}

	public function __toString()
	{
		return (string) $this->value;
	}

	public function getBasePosition()
	{
		$position = $this->position;
		$positions = explode('.', $position);
		return isset($positions[1]) ? $positions[1] : null;
	}

	public function getData($data)
	{
		return $this->data[$data];
	}

  public function setData($data = null) {
    $this->data = $data;
  }

	public function hasLabel()
	{
		return $this->label ? true : false;
	}

	public function getLabel()
	{
		return $this->label;
	}

	public function setLabel($label = null)
	{
		$this->label = $label;
	}

}
