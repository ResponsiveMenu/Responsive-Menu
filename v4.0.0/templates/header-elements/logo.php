<?php
    echo $ui_manager->start_accordion_item( [
        'item_header' => [
            'item_title' => __('Logo','responsive-menu-pro'),
            'title_class' => 'rmp-order-item-title ',
            'title_span_class' => 'item-title has-child-elements ',
            'item_control' => [
                'switcher' => true,
                'id'  => 'rmp-header-order-item-logo',
                'class' => 'item-type',
                'name' => 'menu[header_bar_items_order][logo]',
                'is_checked'   => ( ! empty( $options['header_bar_items_order']['logo'] ) && $options['header_bar_items_order']['logo'] == 'on' ? 'checked' : '' ),
                ]
        ],
        'item_class' => 'rmp-order-item',
    ] );
    
   
    echo $control_manager->add_image_control( [
        'label'  => __('Image','responsive-menu-pro'),
        'group_classes' => 'full-size',
        'id'     => 'rmp-header-bar-logo',
        'picker_class'  => 'rmp-header-bar-logo-selector',
        'picker_id' => "rmp-header-bar-logo-selector",
        'name'    => 'menu[header_bar_logo]',
        'value'    => rmp_get_value($options,'header_bar_logo')
    ] );


    echo $control_manager->add_text_input_control( [
        'label'  => __('Target Link','responsive-menu-pro'),
        'group_classes' => 'full-size',
        'type' => 'text',
        'id'     => 'rmp-header-bar-logo-link',
        'name'   => 'menu[header_bar_logo_link]',
        'value'    => rmp_get_value($options,'header_bar_logo_link'),
        'placeholder' => __('Enter link','responsive-menu-pro')
    ] );

    echo $ui_manager->start_group_controls();
    echo $control_manager->add_text_input_control( [
        'label'  => __('Image Width','responsive-menu-pro'),
        'type'   => 'number',
        'id'     => 'rmp-menu-header-bar-logo-width',
        'name'   => 'menu[header_bar_logo_width]',
        'value'    => rmp_get_value($options,'header_bar_logo_width'),
        'placeholder' => __('Enter value','responsive-menu-pro'),
        'has_unit' => [
            'unit_type' => 'all',
            'id' => 'rmp-menu-header-bar-logo-width-unit',
            'name' => 'menu[header_bar_logo_width_unit]',
            'classes' => 'is-unit',
            'value' => rmp_get_value($options,'header_bar_logo_width_unit'),
        ],
    ] );

    echo $control_manager->add_text_input_control( [
        'label'  => __('Image Height','responsive-menu-pro'),
        'type'   => 'number',
        'id'     => 'rmp-menu-header-bar-logo-height',
        'name'   => 'menu[header_bar_logo_height]',
        'value'    => rmp_get_value($options,'header_bar_logo_height'),
        'placeholder' => __('Enter value','responsive-menu-pro'),
        'has_unit' => [
            'unit_type' => 'all',
            'id' => 'rmp-menu-header-bar-logo-height-unit',
            'name' => 'menu[header_bar_logo_height_unit]',
            'classes' => 'is-unit',
            'value' => rmp_get_value($options,'header_bar_logo_height_unit'),
        ],
    ] );
    echo $ui_manager->end_group_controls();

    echo $ui_manager->end_accordion_item();
