<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class Colour
{

	public function render(Option $option)
	{
		echo "<input type='text'
				class='colour'
				id='{$option->getName()}'
				name='menu[{$option->getName()}]'
				value='{$option->getValue()}' />";
	}

}
