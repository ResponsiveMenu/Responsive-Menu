<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class Image implements FormComponent
{

	public function render(Option $option)
	{
		echo "<input
        type='text'
				class='image'
				id='{$option->getName()}'
				name='menu[{$option->getName()}]'
				value='{$option->getValue()}' />
        <input
            type='button'
            value='Upload Image'
            class='button image_button'
            for='{$option->getName()}'
          />";
	}

}
