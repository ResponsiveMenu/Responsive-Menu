<?php

namespace ResponsiveMenuTest\Validation;

class Validator {

    private $errors;

    public function validate($options) {
        foreach($options as $key => $value):
            if(isset($this->validation_map[$key])):
                $validator_name = 'ResponsiveMenuTest\Validation\Validators\\' . $this->validation_map[$key]['validator'];
                $validator = new $validator_name($value);
                if(!$validator->validate())
                    $this->errors[$key] = 'Validation failed on ' . $this->validation_map[$key]['nice_name'] . ': ' . $validator->getErrorMessage();
            endif;
        endforeach;

        if(!empty($this->errors))
            return false;

        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    private $validation_map = [

        // Colour Validators
        'button_background_colour' => [
            'validator' => 'Colour',
            'nice_name' => 'Button Background Colour'
        ],
        'button_background_colour_hover' => ['validator' => 'Colour', 'nice_name' => ''],
        'button_line_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'button_text_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_background_image' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_item_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_item_background_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_item_border_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_item_border_colour_hover' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_title_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_title_background_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_current_item_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_current_item_background_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_current_item_border_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_current_item_border_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_title_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_title_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_link_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_link_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_current_link_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_current_link_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_border_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_border_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_border_colour_active' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_border_hover_colour_active' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_background_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_background_colour_active' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_background_hover_colour_active' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_shape_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_shape_hover_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_shape_colour_active' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_sub_arrow_shape_hover_colour_active' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_additional_content_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_overlay_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_search_box_text_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_search_box_border_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_search_box_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'menu_search_box_placholder_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_link_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_link_colour_hover' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_background_colour_hover' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_submenu_link_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_submenu_link_colour_hover' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_submenu_background_colour' => ['validator' => 'Colour', 'nice_name' => ''],
        'single_menu_item_submenu_background_colour_hover' => ['validator' => 'Colour', 'nice_name' => ''],
        'header_bar_background_color' => ['validator' => 'Colour', 'nice_name' => ''],
        'header_bar_text_color' => ['validator' => 'Colour', 'nice_name' => '']
    ];
    
}