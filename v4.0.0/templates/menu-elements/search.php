
<li class="rmp-accordion-item rmp-order-item">
    <div class="rmp-accordion-title rmp-order-item-title">
        <span class="item-title"><?php esc_html_e( 'Search', 'responsive-menu-pro' ); ?></span>
        <span class="item-controls">
            <input type='hidden' value='' name='menu[items_order][search]'/>
            <input type="checkbox" data-toggle="search" value="on" id="rmp-item-order-search" class="no-updates toggle item-type" name="menu[items_order][search]" <?php if ( ! empty( $options['items_order']['search'] ) ) { echo "checked"; } ?>>
            <a class="item-edit open-item" aria-label="open Search options">
                <span class="screen-reader-text">Open</span>
            </a>
        </span>
    </div>
    <div class="rmp-accordion-content tabs rmp-menu-controls">
        <ul class="nav-tab-wrapper">
            <li><a class="nav-tab nav-tab-active" href="#search-contents"><?php esc_html_e('Contents', 'responsive-menu-pro'); ?></a></li>
            <li><a class="nav-tab" href="#search-styles"><?php esc_html_e('Styles', 'responsive-menu-pro'); ?></a></li>
        </ul>

        <div id="search-contents" class="title">
            <div class="rmp-input-control-wrapper full-size">
                <label class="rmp-input-control-label">
                    <?php esc_html_e('Placeholder Text', 'responsive-menu-pro'); ?>
                    <span>
                        <a target="_blank" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > PRO </a>
                    </span>
                </label>
                <div class="rmp-input-control">
                    <input disabled placeholder="Search" type="text" id="rmp-menu-search-box-text" name="menu[menu_search_box_text]" class="regular-text" value="<?php echo esc_attr( rmp_get_value( $options, 'menu_search_box_text' ) ); ?>"/>
                </div>
            </div>
        </div>

        <div id="search-styles" class="title">
            <?php

            echo $control_manager->add_group_text_control( [
                'label'  => __('Padding','responsive-menu-pro'),
                'type'   =>   'text',
                'class'  =>  'rmp-menu-search-section-padding',
                'name'    => 'menu[menu_search_section_padding]',
                'input_options' => [  'top', 'right', 'bottom', 'left' ],
                'value_options' => ! empty( $options['menu_search_section_padding'] ) ? $options['menu_search_section_padding'] : ''
            ] );

            echo $ui_manager->start_group_controls();
                echo $control_manager->add_text_input_control( [
                    'label'  => __('Height','responsive-menu-pro'),
                    'type'   => 'number',
                    'id'     => 'rmp-menu-search-box-height',
                    'name'   => 'menu[menu_search_box_height]',
                    'class' => 'no-updates',
                    'value'    => rmp_get_value($options,'menu_search_box_height'),
                    'has_unit' => [
                        'unit_type' => 'all',
                        'id' => 'rmp-menu-search-box-height-unit',
                        'name' => 'menu[menu_search_box_height_unit]',
                        'classes' => 'is-unit no-updates',
                        'default' => 'px',
                        'value' => rmp_get_value( $options, 'menu_search_box_height_unit' ),
                    ],
                ] );

                echo $control_manager->add_text_input_control( [
                    'label'  => __('Border Radius','responsive-menu-pro'),
                    'type'   => 'number',
                    'class' => 'no-updates',
                    'id'     => 'rmp-menu-search-box-border-radius',
                    'name'   => 'menu[menu_search_box_border_radius]',
                    'value'    => rmp_get_value($options,'menu_search_box_border_radius'),
                    'has_unit' => [
                        'unit_type' => 'px',
                    ],
                ] );

            echo $ui_manager->end_group_controls();
            
            echo $ui_manager->accordion_divider();

            echo $ui_manager->start_group_controls();
            echo $control_manager->add_color_control( [
                'label'  => __('Text Color','responsive-menu-pro'),
                'id'     => 'rmp-menu-search-box-text-colour',
                'name'    => 'menu[menu_search_box_text_colour]',
                'value'    => rmp_get_value($options,'menu_search_box_text_colour'),
                
            ] );
            echo $control_manager->add_color_control( [
                'label'  => __('Background Color','responsive-menu-pro'),
                'id'     => 'rmp-menu-search-box-background-colour',
                'name'    => 'menu[menu_search_box_background_colour]',
                'value'    => rmp_get_value($options,'menu_search_box_background_colour'),
                
            ] );
            echo $ui_manager->end_group_controls();
            echo $ui_manager->start_group_controls();
           
            echo $control_manager->add_color_control( [
                'label'  => __('Placeholder Color','responsive-menu-pro'),
                'id'     => 'rmp-menu-search-box-placeholder-colour',
                'name'    => 'menu[menu_search_box_placeholder_colour]',
                'value'    => rmp_get_value($options,'menu_search_box_placeholder_colour'),
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Border Color','responsive-menu-pro'),
                'id'     => 'rmp-menu-search-box-border-colour',
                'name'    => 'menu[menu_search_box_border_colour]',
                'value'    => rmp_get_value($options,'menu_search_box_border_colour'),
                
            ] );
            echo $ui_manager->end_group_controls();

            ?>

        </div>
    </div>
</li>
