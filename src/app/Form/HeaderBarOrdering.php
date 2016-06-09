<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class HeaderBarOrdering implements FormComponent
{

	public function render(Option $option)
	{
    $required = ['logo', 'title', 'search', 'html content', 'button'];
    $current = array_keys((array) json_decode($option->getValue()));

    $all_options = array_filter(array_unique(array_merge($current, $required)));

    echo '<ul id="header-bar-sortable">';
    foreach($all_options as $name)
      echo '<li class="draggable">'.ucwords($name).'<input type="text" name="menu['.$option->getName().']['.$name.']" /></li>';
    echo '</ul>';

    echo '<script>
      jQuery(document).ready(function($) {
        $( "#header-bar-sortable" ).sortable({
          revert: true
        });
        $( "#sortable, .draggable" ).disableSelection();
      });
    </script>';

	}

}
