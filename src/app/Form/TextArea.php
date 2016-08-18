<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Form\FormComponent;

class TextArea implements FormComponent {

	public function render(Option $option) {
		return "<textarea class='textarea' id='{$option->getName()}' name='menu[{$option->getName()}]'>{$option->getValue()}</textarea>";
	}

}
