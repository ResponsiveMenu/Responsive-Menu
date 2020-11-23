<?php
echo $ui_manager->start_accordion_item( [
    'item_header' => [
        'item_title' => __('Title','responsive-menu-pro'),
        'title_class' => 'rmp-order-item-title ',
        'title_span_class' => 'item-title has-child-elements ',
        'item_control' => [
            'switcher' => true,
            'id'  => 'rmp-header-order-item-title',
            'class' => 'item-type',
            'name' => 'menu[header_bar_items_order][title]',
            'is_checked'   => ( ! empty( $options['header_bar_items_order']['title'] ) && $options['header_bar_items_order']['title'] == 'on' ? 'checked' : '' ),
            ]
    ],
    'item_class' => 'rmp-order-item',
] );

echo $control_manager->add_text_input_control( [
    'label'  => __('Title Text ','responsive-menu-pro'),
    'group_classes' => 'full-size',
    'type' => 'text',
    'id'     => 'rmp-menu-header-title',
    'name'   => 'menu[header_bar_title]',
    'value'    => rmp_get_value($options,'header_bar_title'),
] );

echo $ui_manager->end_accordion_item();
