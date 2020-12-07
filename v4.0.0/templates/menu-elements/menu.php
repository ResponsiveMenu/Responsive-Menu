<li class="rmp-accordion-item rmp-order-item">		
    <div class="rmp-accordion-title rmp-order-item-title">
        <span class="item-title"><?php esc_html_e( 'Menu', 'responsive-menu-pro' ); ?></span>
        <span class="item-controls">
            <input type='hidden' value='' name='menu[items_order][menu]'/>
            <input type="checkbox" data-toggle="menu" value="on" id="rmp-item-order-menu"  class="no-updates toggle item-type" name="menu[items_order][menu]" <?php if ( ! empty( $options['items_order']['menu'] ) ) { echo "checked"; } ?>>
        </span>
    </div>

    <div class="rmp-accordion-content rmp-menu-controls">
        <?php
              echo $control_manager->add_group_text_control( [
                'label'  => __('Padding','responsive-menu-pro'),
                'type'   =>   'text',
                'class'  =>  'rmp-menu-section-padding',
                'name'    => 'menu[menu_section_padding]',
                'input_options' => [  'top', 'right', 'bottom', 'left' ],
                'value_options' => ! empty( $options['menu_section_padding'] ) ? $options['menu_section_padding'] : ''
            ] );
        ?>
    </div>
</li>
