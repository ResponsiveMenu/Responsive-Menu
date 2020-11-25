<li class="rmp-accordion-item rmp-order-item">
    <div class="rmp-accordion-title rmp-order-item-title">
        <span class="item-title"><?php esc_html_e( 'Title', 'responsive-menu-pro' ); ?></span>
        <span class="item-controls">
            <input type='hidden' value='' name='menu[items_order][title]'/>
            <input type="checkbox" value="on" data-toggle="title" class="no-updates toggle item-type" id="rmp-item-order-title" name="menu[items_order][title]" <?php if ( ! empty( $options['items_order']['title'] ) ) { echo 'checked'; } ?>>
            <a class="item-edit open-item" aria-label="open Title options">
                <span class="screen-reader-text">Open</span>
            </a>
        </span>
    </div>

    <div class="rmp-accordion-content tabs rmp-menu-controls">
        <ul class="nav-tab-wrapper">
            <li><a class="nav-tab nav-tab-active" href="#title-contents"><?php esc_html_e('Contents', 'responsive-menu-pro'); ?></a></li>
            <li><a class="nav-tab" href="#title-styles"><?php esc_html_e('Styles', 'responsive-menu-pro'); ?></a></li>
        </ul>
        <div id="title-contents" class="title">
            <?php

            echo $control_manager->add_text_input_control( [
                'label'  => __('Title Text ','responsive-menu-pro'),
                'group_classes' => 'full-size',
                'type' => 'text',
                'placeholder' => 'Enter Title',
                'id'     => 'rmp-menu-menu-title',
                'class' => 'no-updates',
                'name'   => 'menu[menu_title]',
                'value'    => rmp_get_value($options,'menu_title'),
            ] );

            echo $control_manager->add_text_input_control( [
                'label'  => __('Link ','responsive-menu-pro'),
                'group_classes' => 'full-size',
                'type' => 'text',
                'placeholder' => 'Enter Link',
                'id'     => 'rmp-menu-title-link',
                'name'   => 'menu[menu_title_link]',
                'value'    => rmp_get_value($options,'menu_title_link'),
            ] );

            echo $control_manager->add_select_control( [
                'label'  => __('Link Target','responsive-menu-pro'),
                'id'     => 'rmp-menu-title-link-location',	
                'name'    => 'menu[menu_title_link_location]',
                'options' => array( '_blank' => 'New Tab' , '_self' => 'Same Page', '_parent' => 'Parent Page', '_top' => 'Full Window Body' ),
                'value'   => rmp_get_value($options,'menu_title_link_location'),
            ] );

            echo $ui_manager->accordion_divider();

            echo $control_manager->add_image_control( [
                'label'  => __('Image','responsive-menu-pro'),
                'group_classes' => 'full-size',
                'id'     => 'rmp-menu-title-image',
                'picker_class'  => 'rmp-menu-title-image-selector',
                'picker_id' => "rmp-menu-title-image-selector",
                'name'    => 'menu[menu_title_image]',
                'value'    => rmp_get_value($options,'menu_title_image'),
            ] );

            echo $control_manager->add_icon_picker_control( [
                'label'  => __('Set Font','responsive-menu-pro'),
                'id'     => 'rmp-button-title-icon',
                'group_classes' => 'full-size',
                'picker_class'  => 'rmp-button-title-icon-picker-button',
                'picker_id' => "rmp-button-title-icon-selector",
                'name'    => 'menu[menu_title_font_icon]',
                'value'    => rmp_get_value($options,'menu_title_font_icon')
            ] );

            ?>
        </div>

        <div id="title-styles" class="title ">

                <?php 

                    echo $control_manager->add_group_text_control( [
                        'label'  => __('Padding','responsive-menu-pro'),
                        'type'   =>   'text',
                        'class'  =>  'rmp-menu-title-section-padding',
                        'name'    => 'menu[menu_title_section_padding]',
                        'input_options' => [  'top', 'right', 'bottom', 'left' ],
                        'value_options' => ! empty( $options['menu_title_section_padding'] ) ? $options['menu_title_section_padding'] : ''
                    ] );

                    echo $ui_manager->start_group_controls();
                    echo $control_manager->add_color_control( [
                        'label'  => __('Background','responsive-menu-pro'),
                        'id'     => 'rmp-menu-title-background-colour',
                        'name'    => 'menu[menu_title_background_colour]',
                        'value'    => rmp_get_value($options,'menu_title_background_colour'),
                        
                    ] );
                    echo $control_manager->add_color_control( [
                        'label'  => __('Background Hover','responsive-menu-pro'),
                        'id'     => 'rmp-menu-title-background-hover-colour',
                        'name'    => 'menu[menu_title_background_hover_colour]',
                        'value'    => rmp_get_value($options,'menu_title_background_hover_colour'),
                        
                    ] );
                    echo $ui_manager->end_group_controls();

                    echo $ui_manager->accordion_divider();
                    echo $ui_manager->start_group_controls();
                    echo $control_manager->add_text_input_control( [
                        'label'  => __('Font Size','responsive-menu-pro'),
                        'type'   => 'number',
                        'class' => 'no-updates',
                        'id'     => 'rmp-menu-title-font-size',
                        'name'   => 'menu[menu_title_font_size]',
                        'value'    => rmp_get_value($options,'menu_title_font_size'),
                        'has_unit' => [
                            'unit_type' => 'all',
                            'id' => 'rmp-menu-title-font-size-unit',
                            'name' => 'menu[menu_title_font_size_unit]',
                            'classes' => 'is-unit no-updates',
                            'default' => 'px',
                            'value' => rmp_get_value($options,'menu_title_font_size_unit'),
                        ],
                    ] );

                    echo $control_manager->add_text_alignment_control( [
                        'label'  => __('Text Alignment','responsive-menu-pro'),
                        'class'   => 'rmp-menu-title-alignment',
                        'name'    => 'menu[menu_title_alignment]',
                        'options' => ['left','center','right','justify'],
                        'value'    => rmp_get_value($options,'menu_title_alignment'),
                        
                    ] );
                    echo $ui_manager->end_group_controls();

                    echo $ui_manager->start_group_controls();
                    echo $control_manager->add_color_control( [
                        'label'  => __(' Text Color','responsive-menu-pro'),
                        'id'     => 'rmp-menu-title-colour',
                        'name'    => 'menu[menu_title_colour]',
                        'value'    => rmp_get_value($options,'menu_title_colour'),
                        
                    ] );
                    echo $control_manager->add_color_control( [
                        'label'  => __(' Text Hover','responsive-menu-pro'),
                        'id'     => 'rmp-menu-title-hover-colour',
                        'name'    => 'menu[menu_title_hover_colour]',
                        'value'    => rmp_get_value($options,'menu_title_hover_colour'),
                        
                    ] );
                    echo $ui_manager->end_group_controls();

                    echo $ui_manager->accordion_divider();

                    echo $ui_manager->start_group_controls();
                        echo $control_manager->add_text_input_control( [
                            'label'  => __('Image Width','responsive-menu-pro'),
                            'type'   => 'number',
                            'id'     => 'rmp-menu-title-image-width',
                            'name'   => 'menu[menu_title_image_width]',
                            'value'    => rmp_get_value($options,'menu_title_image_width'),
                            'placeholder' => __('Enter width','responsive-menu-pro'),
                            'has_unit' => [
                                'unit_type' => 'all',
                                'id' => 'rmp-menu-title-image-width-unit',
                                'name' => 'menu[menu_title_image_width_unit]',
                                'classes' => 'is-unit',
                                'default' => '%',
                                'value' => rmp_get_value($options,'menu_title_image_width_unit'),
                            ],
                        ] );

                        echo $control_manager->add_text_input_control( [
                            'label'  => __('Image Height','responsive-menu-pro'),
                            'type'   => 'number',
                            'id'     => 'rmp-menu-title-image-height',
                            'name'   => 'menu[menu_title_image_height]',
                            'value'    => rmp_get_value($options,'menu_title_image_height'),
                            'placeholder' => __('Enter height','responsive-menu-pro'),
                            'has_unit' => [
                                'unit_type' => 'all',
                                'id' => 'rmp-menu-title-image-height-unit',
                                'name' => 'menu[menu_title_image_height_unit]',
                                'classes' => 'is-unit',
                                'default' => 'px',
                                'value' => rmp_get_value($options,'menu_title_image_height_unit'),
                            ],
                        ] );
                    echo $ui_manager->end_group_controls();
                ?>
        </div>
    </div>
</li>