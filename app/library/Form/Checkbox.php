<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class Checkbox implements FormComponent
{

	public function render(Option $option)
	{
    $checked = $option->getValue() == 'on' ? 'checked="checked"' : '';

		echo "<div class='onoffswitch'>
            <input type='checkbox'
            				class='checkbox onoffswitch-checkbox'
            				id='{$option->getName()}'
                    $checked
            				name='menu[{$option->getName()}]'
            				value='on' />
            <label class='onoffswitch-label' for='{$option->getName()}'>
                <span class='onoffswitch-inner'></span>
                <span class='onoffswitch-switch'></span>
            </label>
          </div>";
	}

}
