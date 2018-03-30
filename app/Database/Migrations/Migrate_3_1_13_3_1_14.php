<?php

namespace ResponsiveMenu\Database\Migrations;

class Migrate_3_1_13_3_1_14 extends Migrate {

    protected $migrations = [
        // Menu > Styling
        'menu_links_line_height' => 'menu_links_height',
        'menu_links_line_height_unit' => 'menu_links_height_unit',

        // Sub Menus > Styling
        'submenu_font' => 'menu_font',
        'submenu_font_size' => 'menu_font_size',
        'submenu_font_size_unit' => 'menu_font_size_unit',
        'submenu_links_height' => 'menu_links_height',
        'submenu_links_height_unit' => 'menu_links_height_unit',
        'submenu_links_line_height' => 'menu_links_height',
        'submenu_links_line_height_unit' => 'menu_links_height_unit',
        'submenu_text_alignment' => 'menu_text_alignment',

        // Sub Menus > Item Borders
        'submenu_border_width' => 'menu_border_width',
        'submenu_border_width_unit' => 'menu_border_width_unit',
        'submenu_item_border_colour' => 'menu_item_border_colour',
        'submenu_item_border_colour_hover' => 'menu_item_border_colour_hover',
        'submenu_current_item_border_colour' => 'menu_current_item_border_colour',
        'submenu_current_item_border_hover_colour' => 'menu_current_item_border_hover_colour',

        // Sub Menus > Item Text
        'submenu_link_colour' => 'menu_link_colour',
        'submenu_link_hover_colour' => 'menu_link_hover_colour',
        'submenu_current_link_colour' => 'menu_current_link_colour',
        'submenu_current_link_hover_colour' => 'menu_current_link_hover_colour',

        // Sub Menus > Item Background
        'submenu_item_background_colour' => 'menu_item_background_colour',
        'submenu_item_background_hover_colour' => 'menu_item_background_hover_colour',
        'submenu_current_item_background_colour' => 'menu_current_item_background_colour',
        'submenu_current_item_background_hover_colour' => 'menu_current_item_background_hover_colour',

        // Sub Menus > Trigger Icon
        'submenu_arrow_position' => 'arrow_position',

        // Sub Menus > Trigger Sizing
        'submenu_submenu_arrow_height' => 'submenu_arrow_height',
        'submenu_submenu_arrow_height_unit' => 'submenu_arrow_height_unit',
        'submenu_submenu_arrow_width' => 'submenu_arrow_width',
        'submenu_submenu_arrow_width_unit' => 'submenu_arrow_width_unit',

        // Sub Menus > Trigger Colours
        'submenu_sub_arrow_shape_colour' => 'menu_sub_arrow_shape_colour',
        'submenu_sub_arrow_shape_hover_colour' => 'menu_sub_arrow_shape_hover_colour',
        'submenu_sub_arrow_shape_colour_active' => 'menu_sub_arrow_shape_colour_active',
        'submenu_sub_arrow_shape_hover_colour_active' => 'menu_sub_arrow_shape_hover_colour_active',

        // Sub Menus > Trigger Background
        'submenu_sub_arrow_background_colour' => 'menu_sub_arrow_background_colour',
        'submenu_sub_arrow_background_hover_colour' => 'menu_sub_arrow_background_hover_colour',
        'submenu_sub_arrow_background_colour_active' => 'menu_sub_arrow_background_colour_active',
        'submenu_sub_arrow_background_hover_colour_active' => 'menu_sub_arrow_background_hover_colour_active',

        // Sub Menus > Trigger Border
        'submenu_sub_arrow_border_colour' => 'menu_sub_arrow_border_colour',
        'submenu_sub_arrow_border_hover_colour' => 'menu_sub_arrow_border_hover_colour',
        'submenu_sub_arrow_border_colour_active' => 'menu_sub_arrow_border_colour_active',
        'submenu_sub_arrow_border_hover_colour_active' => 'menu_sub_arrow_border_hover_colour_active',

        // Desktop Menu > Top Level Styling
        'single_menu_line_height' => 'single_menu_height',
        'single_menu_line_height_unit' => 'single_menu_height_unit',

    ];

}
