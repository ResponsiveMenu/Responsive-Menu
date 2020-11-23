<?php
echo $ui_manager->start_accordion_item( [
    'item_header' => [
        'item_title' => __('HTML Content','responsive-menu-pro'),
        'title_class' => 'rmp-order-item-title ',
        'title_span_class' => 'item-title has-child-elements ',
        'item_control' => [
            'switcher' => true,
            'id'  => 'rmp-header-order-item-additional-content',
            'class' => 'item-type',
            'name' => 'menu[header_bar_items_order][additional content]',
            'is_checked'   => ( ! empty( $options['header_bar_items_order']['additional content'] ) && $options['header_bar_items_order']['additional content'] == 'on' ? 'checked' : '' ),
            ]
    ],
    'item_class' => 'rmp-order-item',
] );
?>
<div id="additions-contents" class="title">
    <div class="rmp-input-control-wrapper full-size">
        <label class="rmp-input-control-label"> <?php esc_html_e('Additional Contents','responsive-menu-pro'); ?> </label>
        <div class="rmp-input-control">
            <textarea id="rmp-menu-header-html-content" name="menu[header_bar_html_content]" class="large-text"><?php echo esc_html( rmp_get_value( $options,'header_bar_html_content' ) ); ?></textarea>
        </div>
    </div>
</div>

<?php
echo $ui_manager->end_accordion_item();
