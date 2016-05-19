<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class TextArea
{

	public function render(Option $option)
	{
		echo "<textarea
				class='textarea'
				id='{$option->getName()}'
				name='menu[{$option->getName()}]'>{$option->getValue()}</textarea>";
	}

}
