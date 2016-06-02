<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class FontIconPageList
{

	public function render(Option $option)
	{
    var_dump($option);
		echo "
    <input
        type='text'
				id='{$option->getName()}'
				name='menu[{$option->getName()}][id][]'
				value='{$option->getValue()}' />
    <input
        type='text'
				id='{$option->getName()}'
				name='menu[{$option->getName()}][icon][]'
				value='{$option->getValue()}' />
    <input
        type='text'
				id='{$option->getName()}'
				name='menu[{$option->getName()}][id][]'
				value='{$option->getValue()}' />
    <input
        type='text'
				id='{$option->getName()}'
				name='menu[{$option->getName()}][icon][]'
				value='{$option->getValue()}' />
        ";
	}

}
