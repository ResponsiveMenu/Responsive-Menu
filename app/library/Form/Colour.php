<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class Colour
{

	public function render(Option $option)
	{
		echo "<input type='text'
				class='colour wp-color-picker'
				id='{$option->getName()}'
				name='menu[{$option->getName()}]'
				value='{$option->getValue()}' />";
	}

}
