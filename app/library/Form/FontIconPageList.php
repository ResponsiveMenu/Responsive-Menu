<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

class FontIconPageList
{

	public function render(Option $option)
	{

    if($decoded = json_decode(json_decode($option->getValue())))
      $final = array_filter(array_combine($decoded->id, $decoded->icon));
    else
      $final = null;

    echo "<div class='font-icon-container'><div class='font-icon-row'><div class='font-icon-cell-id'>Item Id</div><div class='font-icon-cell-icon'>Icon</div></div>";

    if(is_array($final) && !empty($final)):
      foreach( $final as $id => $icon):
      		echo "
          <div class='font-icon-row'>
            <div class='font-icon-cell-id'>
                <input
                  type='text'
          				class='{$option->getName()}_id'
          				name='menu[{$option->getName()}][id][]'
          				value='{$id}' />
              </div>
            <div class='font-icon-cell-icon'>
                <input
                    type='text'
            				class='{$option->getName()}_icon'
            				name='menu[{$option->getName()}][icon][]'
            				value='{$icon}' />
                </div>
            </div>";
          endforeach;
    else:
      echo "
      <div class='font-icon-row'>
        <div class='font-icon-cell-id'>
            <input
              type='text'
              class='{$option->getName()}_id'
              name='menu[{$option->getName()}][id][]'
              value='' />
          </div>
        <div class='font-icon-cell-icon'>
            <input
                type='text'
                class='{$option->getName()}_icon'
                name='menu[{$option->getName()}][icon][]'
                value='' />
            </div>
        </div>";
    endif;

      echo "</div><div class='add-font-icon'>Add New Font Icon</div>";

      echo "<script>
        jQuery(document).ready(function($) {
          $(document).on('click', '.add-font-icon', function(e) {
            var lastRow = $('#{$option->getName()} .font-icon-row').last();
            var nextRow = lastRow.clone();
            nextRow.find(':text').val('')
            lastRow.after(nextRow);
          });
        });
      </script>";

	}

}
