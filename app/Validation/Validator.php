<?php

namespace ResponsiveMenu\Validation;

class Validator {

    private $errors;

    public function validate($options) {
        foreach($this->validation_map as $validator_name => $options_list):
            foreach($options_list as $option):
                if(isset($options[$option])):
                    $validator_obj = 'ResponsiveMenu\Validation\Validators\\' . $validator_name;
                    $validator = new $validator_obj($options[$option]);
                    if(!$validator->validate()):
                        $nice_name = str_replace('_', ' ', ucwords($option));
                        $this->errors[$option][] = 'Validation failed on <a class="validation-error scroll-to-option" href="#responsive-menu-' . str_replace('_', '-', $option) . '">'  . $nice_name . '</a>: ' . $validator->getErrorMessage();
                    endif;
                endif;
            endforeach;
        endforeach;

        if(!empty($this->errors))
            return false;

        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    private $validation_map = [

        // Numeric Validators
        'Number' => [
            'breakpoint',
            'button_line_width',
            'button_line_height',
            'button_line_margin',
            'button_width',
            'button_height',
            'button_top',
            'animation_speed',
            'transition_speed',
            'button_font_size',
            'button_title_line_height',
            'menu_width',
            'menu_title_font_size',
            'menu_border_width',
            'menu_font_size',
            'menu_links_height',
            'menu_links_line_height',
            'submenu_arrow_height',
            'submenu_arrow_width',
            'menu_depth_0',
            'menu_depth_1',
            'menu_depth_2',
            'menu_depth_3',
            'menu_depth_4',
            'menu_depth_5',
        ],

        // Positive Digits
        'Positive' => [
            'breakpoint',
        ],

        // Colour Validators
        'Colour' => [
            'button_background_colour',
            'button_background_colour_hover',
            'button_line_colour',
            'button_text_colour',
            'menu_background_colour',
            'menu_item_background_colour',
            'menu_item_background_hover_colour',
            'menu_item_border_colour',
            'menu_item_border_colour_hover',
            'menu_title_background_colour',
            'menu_title_background_hover_colour',
            'menu_current_item_background_colour',
            'menu_current_item_background_hover_colour',
            'menu_current_item_border_colour',
            'menu_current_item_border_hover_colour',
            'menu_title_colour',
            'menu_title_hover_colour',
            'menu_link_colour',
            'menu_link_hover_colour',
            'menu_current_link_colour',
            'menu_current_link_hover_colour',
            'menu_sub_arrow_border_colour',
            'menu_sub_arrow_border_hover_colour',
            'menu_sub_arrow_border_colour_active',
            'menu_sub_arrow_border_hover_colour_active',
            'menu_sub_arrow_background_colour',
            'menu_sub_arrow_background_hover_colour',
            'menu_sub_arrow_background_colour_active',
            'menu_sub_arrow_background_hover_colour_active',
            'menu_sub_arrow_shape_colour',
            'menu_sub_arrow_shape_hover_colour',
            'menu_sub_arrow_shape_colour_active',
            'menu_sub_arrow_shape_hover_colour_active',
            'menu_additional_content_colour',
            'menu_overlay_colour',
            'menu_search_box_text_colour',
            'menu_search_box_border_colour',
            'menu_search_box_background_colour',
            'menu_search_box_placeholder_colour',
            'button_background_colour_active',
            'button_line_colour_hover',
            'button_line_colour_active',
            'menu_container_background_colour'
        ]

    ];

}