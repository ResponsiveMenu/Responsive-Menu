<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option;
use ResponsiveMenu\Form\FormComponent;

class HeaderBarOrdering implements FormComponent {

	public function render(Option $option) {

    $required = ['logo' => '', 'title' => '', 'search' => '', 'html content' => '', 'button' => ''];
    $current_options = (array) json_decode($option->getValue());
    $all_options = array_merge($current_options, $required);
    unset($all_options['button']);
    
    $output = '<ul id="header-bar-sortable">';
    foreach($all_options as $name => $val):
      $current_value = isset($current_options[$name]) ? $current_options[$name] : '';
      $on_class = $current_value == 'on' ? 'order-option-switch-on' : '';
      $output .= '<li class="draggable">'
              . ucwords($name)
              . '<input type="text" class="orderable-item" value="'.$current_value.'" name="menu['.$option->getName().']['.$name.']" />'
              . '<div class="order-option-switch ' . $on_class . '"></div>'
            . '</li>';
    endforeach;
    $output .= '</ul>';

    $output .= '<script>
      jQuery(document).ready(function($) {
        $(document).on("click", ".order-option-switch", function() {
          if($(this).siblings("input.orderable-item").val() != "on") {
            console.log($(this));
            $(this).siblings("input.orderable-item").val("on");
            $(this).addClass("order-option-switch-on");
          } else {
            $(this).siblings("input.orderable-item").val("");
            $(this).removeClass("order-option-switch-on");
          }
        });
        $( "#header-bar-sortable" ).sortable({
          revert: true
        });
        $( "#sortable, .draggable" ).disableSelection();
      });
    </script>';
    return $output;
	}

}
