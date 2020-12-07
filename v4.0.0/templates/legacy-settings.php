
<div id="tab-legacy-settings" class="rmp-accordions" aria-label="Legacy Settings">
    <ul class="rmp-accordion-container" id="rmp-legacy-settings-container">
        <?php
            echo $ui_manager->start_accordion_item( [
                'item_header' => [
                    'item_title' => __('Theme Location','responsive-menu-pro'),
                ]
            ] );

            $locations = get_registered_nav_menus();
            $locations = array_merge( [ 0 => 'None'] ,  $locations );

            echo $control_manager->add_select_control( [
                'label'  => __('Menu from location','responsive-menu-pro'),
                'id'     => 'rmp-theme-location-menu',
                'tool_tip' => [
                    'text' => __('If you want to use a theme location menu rather than a specific menu then you can select that here.','responsive-menu-pro'),
                ],
                'name'    => 'menu[theme_location_menu]',
                'options' => $locations,
                'value'   => rmp_get_value($options,'theme_location_menu'),
            ] );

            echo $ui_manager->end_accordion_item();

            echo $ui_manager->start_accordion_item( [
                'item_header' => [
                    'item_title' => __('Sub Level Menu Trigger','responsive-menu-pro'),
                ]
            ] );

            echo $ui_manager->start_group_controls();

            echo $control_manager->add_text_input_control( [
                'label'  => __('Width','responsive-menu-pro'),
                'type'   => 'number',
                'id'     => 'rmp-submenu-child-arrow-width',
                'name'   => 'menu[submenu_submenu_arrow_width]',
                'value'    => rmp_get_value($options,'submenu_submenu_arrow_width'),
                
                'tool_tip' => [
                    'text' => 'Set the width of the menu trigger items and their units.	'
                ],
                'has_unit' => [
                    'unit_type' => 'all',
                    'id' => 'rmp-submenu-child-arrow-width-unit',
                    'name' => 'menu[submenu_submenu_arrow_width_unit]',
                    'classes' => 'is-unit',
                    'default' => 'px',
                    'value' => rmp_get_value($options,'submenu_submenu_arrow_width_unit'),
                ],
            ] );

            echo $control_manager->add_text_input_control( [
                'label'  => __('Height','responsive-menu-pro'),
                'type'   => 'number',
                'id'     => 'rmp-submenu-child-arrow-height',
                'name'   => 'menu[submenu_submenu_arrow_height]',
                'value'    => rmp_get_value($options,'submenu_submenu_arrow_height'),
                'tool_tip' => [
                    'text' => 'Set the height of the menu trigger items and their units.'
                ],
                'has_unit' => [
                    'unit_type' => 'all',
                    'id' => 'rmp-submenu-child-arrow-height-unit',
                    'name' => 'menu[submenu_submenu_arrow_height_unit]',
                    'classes' => 'is-unit',
                    'default' => 'px',
                    'value' => rmp_get_value($options,'submenu_submenu_arrow_height_unit'),
                ],
            ] );
            echo $ui_manager->end_group_controls();

            echo $control_manager->add_select_control( [
                'label'  => __('Position','responsive-menu-pro'),
                'id'     => 'rmp-submenu-arrow-position',
                'class'  => 'rmp-submenu-arrow-position',
                'name'    => 'menu[submenu_arrow_position]',
                'options' => array( 'right' => 'Right' , 'left' => 'Left' ),
                'value'   => rmp_get_value($options,'submenu_arrow_position'),
            ] );								

            echo $ui_manager->start_sub_accordion();

            echo $ui_manager->start_accordion_item( [
                'item_header' => [
                    'item_title' =>  __('Background Color','responsive-menu-pro')
                ]
            ] );

            echo $ui_manager->start_group_controls();
            echo $control_manager->add_color_control( [
                'label'  => __('Normal','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-background-color',
                'name'    => 'menu[submenu_sub_arrow_background_colour]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_background_colour'),
                
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Hover','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-background-hover-colour',
                'name'    => 'menu[submenu_sub_arrow_background_hover_colour]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_background_hover_colour'),
                
            ] );
            echo $ui_manager->end_group_controls();

            echo $ui_manager->start_group_controls();
            echo $control_manager->add_color_control( [
                'label'  => __('Active Item','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-background-colour-active',
                'name'    => 'menu[submenu_sub_arrow_background_colour_active]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_background_colour_active'),
                
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Active Item Hover','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-background-hover-colour-active',
                'name'    => 'menu[submenu_sub_arrow_background_hover_colour_active]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_background_hover_colour_active'),
                
            ] );
            echo $ui_manager->end_group_controls();
            echo $ui_manager->end_accordion_item();
            
            echo $ui_manager->start_accordion_item( [
                'item_header' => [
                    'item_title' => __('Border Color','responsive-menu-pro')
                ]
            ] );

            echo $control_manager->add_text_input_control( [
                'label'  => __('Border Width','responsive-menu-pro'),
                'type'   => 'number',
                'id'     => 'rmp-submenu-sub-arrow-border-width',
                'name'   => 'menu[submenu_sub_arrow_border_width]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_border_width'),
                'class' => 'no-updates',
                'has_unit' => [
                    'unit_type' => 'all',
                    'id' => 'rmp-menu-sub-arrow-border-width-unit',
                    'name' => 'menu[submenu_sub_arrow_border_width_unit]',
                    'classes' => 'is-unit',
                    'value' => rmp_get_value($options,'submenu_sub_arrow_border_width_unit'),
                ],
            ] );

            echo $ui_manager->start_group_controls();
            echo $control_manager->add_color_control( [
                'label'  => __('Normal','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-border-colour',
                'name'    => 'menu[submenu_sub_arrow_border_colour]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_border_colour'),
                
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Hover','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-border-hover-colour',
                'name'    => 'menu[submenu_sub_arrow_border_hover_colour]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_border_hover_colour'),
                
            ] );
            echo $ui_manager->end_group_controls();
            echo $ui_manager->start_group_controls();

            echo $control_manager->add_color_control( [
                'label'  => __('Active Item','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-border-colour-active',
                'name'    => 'menu[submenu_sub_arrow_border_colour_active]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_border_colour_active'),
                
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Active Item Hover','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-border-hover-colour-active',
                'name'    => 'menu[submenu_sub_arrow_border_hover_colour_active]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_border_hover_colour_active'),
                
            ] );
            echo $ui_manager->end_group_controls();

            echo $ui_manager->end_accordion_item();
            echo $ui_manager->start_accordion_item( [
                'item_header' => [
                    'item_title' => __('Arrow Text Color','responsive-menu-pro')
                ]
            ] );

            echo $ui_manager->start_group_controls();
            echo $control_manager->add_color_control( [
                'label'  => __('Normal','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-shape-colour',
                'name'    => 'menu[submenu_sub_arrow_shape_colour]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_shape_colour'),
                
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Hover','responsive-menu-pro'),
                'id'     => 'rmp-submenu-item-border-colour-hover',
                'name'    => 'menu[submenu_sub_arrow_shape_hover_colour]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_shape_hover_colour'),
                
            ] );
            echo $ui_manager->end_group_controls();

            echo $ui_manager->start_group_controls();
            echo $control_manager->add_color_control( [
                'label'  => __('Active Item','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-shape-colour-active',
                'name'    => 'menu[submenu_sub_arrow_shape_colour_active]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_shape_colour_active'),
                
            ] );

            echo $control_manager->add_color_control( [
                'label'  => __('Active Item Hover','responsive-menu-pro'),
                'id'     => 'rmp-submenu-sub-arrow-shape-hover-colour-active',
                'name'    => 'menu[submenu_sub_arrow_shape_hover_colour_active]',
                'value'    => rmp_get_value($options,'submenu_sub_arrow_shape_hover_colour_active'),
                
            ] );
            echo $ui_manager->end_group_controls();

            echo $ui_manager->end_accordion_item();

            echo $ui_manager->end_accordion_item();
        ?>
    </ul>
</div>