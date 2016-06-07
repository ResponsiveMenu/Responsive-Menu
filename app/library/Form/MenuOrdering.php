<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class MenuOrdering implements FormComponent
{

	public function render(Option $option)
	{
    $required = ['title', 'menu', 'search', 'additional content'];
    $current = array_keys((array) json_decode($option->getValue()));

    $all_options = array_filter(array_unique(array_merge($current, $required)));

    echo '<ul id="sortable">';
    foreach($all_options as $name)
      echo '<li class="draggable">'.ucwords($name).'<input type="text" name="menu['.$option->getName().']['.$name.']" /></li>';
    echo '</ul>';

    echo '<script>
      jQuery(document).ready(function($) {
        $( "#sortable" ).sortable({
          revert: true
        });
        $( "#sortable, .draggable" ).disableSelection();
      });
    </script>';

	}

}
