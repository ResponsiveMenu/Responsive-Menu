<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class Image
{

	public function render(Option $option)
	{
		echo "<input type='text'
				class='image'
				id='{$option->getName()}'
				name='menu[{$option->getName()}]'
				value='{$option->getValue()}' />";
	}

}
