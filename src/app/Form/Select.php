<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class Select implements FormComponent
{

	public function render(Option $option)
	{
		$html = "<div class='select-style'><select class='select' name='menu[{$option->getName()}]' id='{$option->getName()}'>";
    foreach($option->getData('select') as $name => $val) :
      $selected = $option->getValue() == $name ? 'selected="selected"' : '';
      $html .= "<option value='{$name}' {$selected}>{$val}</option>";
    endforeach;
    $html .= "</select></div>";
    echo $html;
	}

}