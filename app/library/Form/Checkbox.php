<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class Checkbox
{

	public function render(Option $option)
	{
    $checked = $option->getValue() == 'true' ? 'checked="checked"' : '';
		echo "<input type='checkbox'
				class='checkbox'
				id='{$option->getName()}'
        $checked
				name='menu[{$option->getName()}]'
				value='true' />";
	}

}
