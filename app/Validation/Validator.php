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
                        $this->errors[$option][] = 'Validation failed on <a class="validation-error" href="#responsive-menu-' . str_replace('_', '-', $option) . '">'  . $nice_name . '</a>: ' . $validator->getErrorMessage();
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
        'Numeric' => [
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
            'submenu_arrow_height',
            'submenu_arrow_width',
            'header_bar_height',
            'header_bar_font_size',
            'single_menu_height',
            'single_menu_font_size',
            'single_menu_submenu_font_size',
            'single_menu_submenu_height',
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
            'single_menu_item_link_colour',
            'single_menu_item_link_colour_hover',
            'single_menu_item_background_colour',
            'single_menu_item_background_colour_hover',
            'single_menu_item_submenu_link_colour',
            'single_menu_item_submenu_link_colour_hover',
            'single_menu_item_submenu_background_colour',
            'single_menu_item_submenu_background_colour_hover',
            'header_bar_background_color',
            'header_bar_text_color',
        ]

    ];

}