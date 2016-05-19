<?php

namespace ResponsiveMenu\Formatters;

class FormElement
{
	public static function render($option)
	{
		$form_element = $option->getType();
		$form_element = new $form_element;
		$form_element->render($option);

	}

}
