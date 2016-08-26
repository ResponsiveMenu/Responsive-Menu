<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Form\FormComponent;

class MenuOrdering implements FormComponent {

	public function render(Option $option) {

    $required = ['title' => '', 'menu' => '', 'search' => '', 'additional content' => ''];
    $current_options = (array) json_decode($option->getValue());
    $all_options = array_merge($current_options, $required);

    $output = '<ul id="menu-sortable">';
    foreach($all_options as $name => $val):
      $current_value = isset($current_options[$name]) ? $current_options[$name] : '';
      $on_class = $current_value == 'on' ? 'menu-order-option-switch-on' : '';

      $output .= '<li class="draggable">'
              . ucwords($name)
              . '<input type="text" class="orderable-item" value="'.$current_value.'" name="menu['.$option->getName().']['.$name.']" />'
              . '<div class="menu-order-option-switch ' . $on_class . '"></div>'
            . '</li>';
    endforeach;
    $output .= '</ul>';

    $output .= '<script>
      jQuery(document).ready(function($) {
        $(document).on("click", ".menu-order-option-switch", function() {
          if($(this).siblings("input.orderable-item").val() != "on") {
            $(this).siblings("input.orderable-item").val("on");
            $(this).addClass("menu-order-option-switch-on");
          } else {
            $(this).siblings("input.orderable-item").val("");
            $(this).removeClass("menu-order-option-switch-on");
          }
        });
        $( "#menu-sortable" ).sortable({
          revert: true
        });
        $( "#sortable, .draggable" ).disableSelection();
      });
    </script>';
    return $output;
	}

}
