<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class Select
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
