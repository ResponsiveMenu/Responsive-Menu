<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Form\FormComponent;

class Select {

	public function render(Option $option, array $select_data) {

    $html = "<div class='select-style'><select class='select' name='menu[{$option->getName()}]' id='{$option->getName()}'>";
    foreach($select_data as $data) :
      $selected = $option->getValue() == $data['value'] ? " selected='selected'" : "";
      $disabled = isset($data['disabled']) ? " disabled='disabled'" : "";
      $pro = isset($data['disabled']) ? ' [PRO]' : '';
      $html .= "<option value='{$data['value']}'{$selected}{$disabled}>{$data['display']}{$pro}</option>";
    endforeach;
    $html .= "</select></div>";
    return $html;
	}

}
