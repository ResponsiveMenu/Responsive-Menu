
<?php
echo $ui_manager->start_accordion_item( [
    'item_header' => [
        'item_title' => __('Search','responsive-menu-pro'),
        'title_class' => 'rmp-order-item-title rmp-item-placeholder',
        'title_span_class' => 'item-title',
        'item_control' => [
            'switcher' => true,
            'id'  => 'rmp-header-order-item-search',
            'class' => 'item-type',
            'name' => 'menu[header_bar_items_order][search]',
            'is_checked'   => ( ! empty( $options['header_bar_items_order']['search'] ) && $options['header_bar_items_order']['search'] == 'on' ? 'checked' : '' ),
        ]
    ],
    'item_class' => 'rmp-order-item',
    'self_close_item' => true,
] );
