<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Form\FormComponent as FormComponent;

class MenuOrdering implements FormComponent
{

	public function render(Option $option)
	{
    echo '<ul id="sortable">';
    foreach($option->getValue() as $item => $order)
      echo '<li class="draggable">'.ucwords($item).'<input type="text" name="menu['.$option->getName().']['.$item.']" /></li>';
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
