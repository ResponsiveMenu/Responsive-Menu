<li class="rmp-accordion-item rmp-order-item">
    <div class="rmp-accordion-title rmp-order-item-title">
        <span class="item-title"><?php esc_html_e( 'Additional Content', 'responsive-menu-pro' ); ?></span>
        <span class="item-controls">
            <input type='hidden' value='' name='menu[items_order][additional content]'/>
            <input type="checkbox" data-toggle="additional-content" value="on" id="rmp-item-order-additional-content"  class="no-updates toggle item-type" name="menu[items_order][additional content]" <?php if ( ! empty( $options['items_order']['additional content'] ) ) { echo "checked"; } ?>>
            <a class="item-edit open-item" aria-label="open Addition Contents options">
                <span class="screen-reader-text">Open</span>
            </a>
        </span>
    </div>
    <div class="rmp-accordion-content tabs rmp-menu-controls">
            <ul class="nav-tab-wrapper">
                <li><a class="nav-tab nav-tab-active" href="#additions-contents"><?php esc_html_e('Contents', 'responsive-menu-pro'); ?></a></li>
                <li><a class="nav-tab" href="#additions-contents-styles"><?php esc_html_e('Styles', 'responsive-menu-pro'); ?></a></li>
            </ul>

            <div id="additions-contents" class="title">
                <div class="rmp-input-control-wrapper full-size">
                    <label class="rmp-input-control-label"> <?php esc_html_e('Content', 'responsive-menu-pro'); ?> </label>
                    <div class="rmp-input-control">
                        <textarea id="rmp-menu-additional-content" name="menu[menu_additional_content]" class="no-updates"><?php echo esc_html( rmp_get_value( $options,'menu_additional_content' ) ); ?></textarea>
                    </div>
                </div>
            </div>

            <div id="additions-contents-styles" class="title">    
                    <?php

                    echo $control_manager->add_group_text_control( [
                        'label'  => __('Padding','responsive-menu-pro'),
                        'type'   =>   'text',
                        'class'  =>  'rmp-menu-additional-section-padding',
                        'name'    => 'menu[menu_additional_section_padding]',
                        'input_options' => [  'top', 'right', 'bottom', 'left' ],
                        'value_options' => ! empty( $options['menu_additional_section_padding'] ) ? $options['menu_additional_section_padding'] : ''
                    ] );

                    echo $ui_manager->start_group_controls();
                    echo $control_manager->add_text_input_control( [
                        'label'  => __('Font Size','responsive-menu-pro'),
                        'type'   => 'number',
                        'class' => 'no-updates',
                        'id'     => 'rmp-menu-additional-content-font-size',
                        'name'   => 'menu[menu_additional_content_font_size]',
                        'value'    => rmp_get_value($options,'menu_additional_content_font_size'),
                        'has_unit' => [
                            'unit_type' => 'all',
                            'id' => 'rmp-menu-additional-content-font-size-unit',
                            'name' => 'menu[menu_additional_content_font_size_unit]',
                            'classes' => 'is-unit no-updates',
                            'default' => 'px',
                            'value' => rmp_get_value($options,'menu_additional_content_font_size_unit'),
                        ],
                    ] );

                    echo $control_manager->add_text_alignment_control( [
                        'label'  => __('Text Alignment','responsive-menu-pro'),
                        'class'   => 'rmp-menu-additional-content-alignment',
                        'name'    => 'menu[menu_additional_content_alignment]',
                        'options' => ['left','center','right','justify'],
                        'value'    => rmp_get_value($options,'menu_additional_content_alignment'),
                    ] );
                    echo $ui_manager->end_group_controls();

                    echo $control_manager->add_color_control( [
                        'label'  => __('Text Color','responsive-menu-pro'),
                        'id'     => 'rmp-menu-additional-content-color',
                        'name'    => 'menu[menu_additional_content_colour]',
                        'value'    => rmp_get_value($options,'menu_additional_content_colour'),
                        
                    ] );

                    ?>
            </div>
        </div>
</li>
