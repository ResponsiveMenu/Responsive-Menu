<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class FontIconPageList
{

	public function render(Option $option)
	{
    $decoded = json_decode($option);

    foreach(array_combine($decoded->id, $decoded->icon) as $id => $icon):
  		echo "
      <input
          type='text'
  				id='{$option->getName()}'
  				name='menu[{$option->getName()}][id][]'
  				value='{$id}' />
      <input
          type='text'
  				id='{$option->getName()}'
  				name='menu[{$option->getName()}][icon][]'
  				value='{$icon}' />
          ";
      endforeach;

	}

}
