<?php

/**
 * This is migration class which is responsible for migration.
 *
 * @since   4.0.0
 *
 * @package responsive_menu_pro
 */

namespace RMP\Features\Inc;

/** Disable the direct access to this class */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'RMP_Migration' ) ) :

/**
 * Class RMP_Migration handle the migration.
 *
 * @package    responsive_menu_pro
 *
 * @author     Expresstech System
 */
class RMP_Migration {

     /**
	 * Instance of this class.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      object $instance Instance of this class.
	 */
    protected static $instance;

	/**
	 * Returns new or existing instance.
	 *
	 * @since    4.0.0
	 *
	 * @return RMP_Admin instance.
	 */
	final public static function get_instance() {

		if ( ! isset( static::$instance ) ) {
			static::$instance = new RMP_Migration();
			static::$instance->setup();
		}

		return self::$instance;
    }

    /**
	 * Setup hooks.
	 *
	 * @since    4.0.0
	 */
	protected function setup() {

        if ( ! empty( get_option('responsive_menu_pro_version') ) ) {
            add_action( 'rmp_after_cpt_registered', array( $this, 'migrate' ) );
        }
		
	}

    /**
     * Function to migrate the options and convert it to new menu.
     */
    public function migrate() {

        if ( ! empty( get_option( 'rmp_migrate') ) ) {
            return;
        }

        $older_options = $this->get_table_options();

        //Separate the global options and migrate it into new format.
        $this->migrate_global_settings( $older_options );

        //Separate the mega menu item options into new format.
        $mega_options = $this->migrate_mega_menu_options( $older_options );

        $converted_options = $this->convert_older_menu_option_to_new_format();

        $desktop_options = $this->convert_to_desktop_options( $older_options );

        $new_menu = array(
			'post_title'  => 'Default Menu',
			'post_author' => get_current_user_id(),
			'post_status' => 'publish',
			'post_type'   => 'rmp_menu',
		);

        $post_id = wp_insert_post( $new_menu );

        if ( ! empty( $post_id ) ) {

            $converted_options['mega_menu'] = [];

            foreach( $mega_options as $key => $options ) {
                $converted_options['mega_menu'][$key] = 'on';
                update_post_meta( $post_id, '_rmp_mega_menu_'.$key, $options );
            }

            $converted_options['menu_name'] = 'Default Menu';
            update_post_meta( $post_id, 'rmp_menu_meta', $converted_options );
            update_post_meta( $post_id, '_desktop', $desktop_options );
           
            /**
             * Fires when menu is migrated.
             * 
             * @param int $post_id
             */
            do_action( 'rmp_migrate_menu_style', $post_id );
        }

       update_option( 'rmp_migrate', true );
    }

    /**
     * Function to convert the desktop options into new version.
     * 
     * @version 4.0.0
     * 
     * @param array $options
     * 
     * @return array
     */
    public function convert_to_desktop_options( $options ) {

        return [
            'menu_font' => $options['single_menu_font'],
            'menu_font_size' => $options['single_menu_font_size'],
            'menu_font_size_unit' => $options['single_menu_font_size_unit'],
            'menu_item_background_colour' => $options['single_menu_item_background_colour'],
            'menu_item_background_hover_colour' => $options['single_menu_item_background_colour_hover'],
            'menu_link_colour' => $options['single_menu_item_link_colour'],
            'menu_link_hover_colour' => $options['single_menu_item_link_colour_hover'],
            'menu_links_height' => $options['single_menu_height'],
            'menu_links_height_unit' => $options['single_menu_height_unit'],
            'menu_links_line_height' => $options['single_menu_line_height'],
            'menu_links_line_height_unit' => $options['single_menu_line_height_unit'],
            'submenu_font' => $options['single_menu_submenu_font'],
            'submenu_font_size' => $options['single_menu_submenu_font_size'],
            'submenu_font_size_unit' => $options['single_menu_submenu_font_size_unit'],
            'submenu_item_background_colour' => $options['single_menu_item_submenu_background_colour'],
            'submenu_item_background_hover_colour' => $options['single_menu_item_submenu_background_colour_hover'],
            'submenu_link_colour' => $options['single_menu_item_submenu_link_colour'],
            'submenu_link_hover_colour' => $options['single_menu_item_submenu_link_colour_hover'],
            'submenu_links_height' => $options['single_menu_submenu_height'],
            'submenu_links_height_unit' => $options['single_menu_submenu_height_unit'],
            'submenu_links_line_height' => $options['single_menu_submenu_line_height'],
            'submenu_links_line_height_unit' => $options['single_menu_submenu_line_height_unit'],
        ];

    }

    /**
     * Convert all required the options to new responsive menu.
     */
    public function convert_older_menu_option_to_new_format() {

        $older_options = $this->get_table_options();
        $new_options = $older_options;

        //Menu elements order.
        $new_options['items_order'] = json_decode( $older_options['items_order'], true );

        //Header bar menu elements.
        $new_options['header_bar_items_order'] = json_decode( $older_options['header_bar_items_order'], true );
        $new_options['header_bar_items_order']['menu'] = 'on';

        //Migrate all font icon options.
        $page_icons = json_decode( $older_options['menu_font_icons'], true );
        $menu_item_icons = [];

        //Migrate toggle button events.
        $new_options['button_trigger_type_click'] = 'off';
        $new_options['button_trigger_type_hover'] = 'off';
        if ( 'click' == $older_options['button_trigger_type'] ) {
            $new_options['button_trigger_type_click'] = 'on';
        } elseif( 'hover' == $older_options['button_trigger_type'] ) {
            $new_options['button_trigger_type_hover'] = 'on';
        } else {
            $new_options['button_trigger_type_click'] = 'on';
            $new_options['button_trigger_type_hover'] = 'on';
        }

        if( ! empty( $page_icons['id'] ) ) {
            foreach( $page_icons['id'] as $key => $item_id  ) {
                if( empty( $item_id ) ) {
                    continue;
                }
                $icon = $page_icons['icon'][$key];
                $type = $page_icons['type'][$key];
                $menu_item_icons['id'][] = $item_id;
                $menu_item_icons['icon'][] = $this->get_icon_element( $type, $icon );
            }
        }

        $new_options['menu_font_icons'] = $menu_item_icons;

        if ( ! empty( $new_options['active_arrow_font_icon'] ) ) {
            $new_options['active_arrow_font_icon'] = $this->get_icon_element( $older_options['active_arrow_font_icon_type'], $older_options['active_arrow_font_icon'] );
        }

        if ( ! empty( $new_options['button_font_icon'] ) ) {
            $new_options['button_font_icon'] = $this->get_icon_element( $older_options['button_font_icon_type'], $older_options['button_font_icon'] );
        }

        if ( ! empty( $new_options['button_font_icon_when_clicked'] ) ) {
            $new_options['button_font_icon_when_clicked'] = $this->get_icon_element( $older_options['button_font_icon_when_clicked_type'], $older_options['button_font_icon_when_clicked'] );

        }

        if ( ! empty( $new_options['inactive_arrow_font_icon'] ) ) {
            $new_options['inactive_arrow_font_icon'] = $this->get_icon_element( $older_options['inactive_arrow_font_icon_type'], $older_options['inactive_arrow_font_icon'] );
        }

        if ( ! empty( $new_options['menu_title_font_icon'] ) ) {
            $new_options['menu_title_font_icon'] = $this->get_icon_element( $older_options['menu_title_font_icon_type'], $older_options['menu_title_font_icon'] );
        }

        $default_options = rmp_get_default_options();
        $new_options     = array_merge( $default_options, $new_options );

        //Padding on menu elements.
        $new_options['menu_title_padding']  = [
            'left' => '5%',
            'top' => '0px',
            'right' => '5%',
            'bottom' => '0px'
        ];

        $new_options['menu_search_section_padding']  = [
            'left' => '5%',
            'top' => '0px',
            'right' => '5%',
            'bottom' => '0px'
        ];

        $new_options['menu_additional_section_padding']  = [
            'left' => '5%',
            'top' => '0px',
            'right' => '5%',
            'bottom' => '0px'
        ];

        $new_options['tablet_breakpoint'] = $older_options['breakpoint'];

        return $new_options;
    }


    public function get_icon_element( $type, $icon ) {

        switch($type) {
            case 'glyphicon':
                return '<span class="rmp-font-icon glyphicon glyphicon-' . $icon . '" aria-hidden="true"></span>';
            case 'font-awesome':
                return '<span class="rmp-font-icon fas fa-' . $icon .'"></span>';
            case 'font-awesome-brand':
                return '<span class="rmp-font-icon fab fa-' . $icon .'"></span>';
            default:
                return $icon;
        }
    }
    
    public function migrate_mega_menu_options( $older_options ) {

        if ( empty( $older_options['desktop_menu_options'] ) ) {
            return;
        }

        $mega_menu = json_decode( $older_options['desktop_menu_options'], true );
        $mega_options = [];
        $parent_key = '';
        foreach( $mega_menu as $key => $options ) {
            if ( ! empty( $mega_menu[$key]['type'] ) && 'mega' == $options['type'] ) {
                $parent_key = $key;
                $mega_options[$key] = [
                    'meta' => [
                        'panel_width'      => $options['submenu_panel_max_width'],
                        'panel_width_unit' => $options['submenu_panel_max_width_unit'],
                        'panel_padding'    => [
                            'left'   => 0,
                            'top'    => 0,
                            'right'  => 0,
                            'bottom' => 0,
                        ],
                        'panel_background' => [
                            'color' => $options['parent_background_colour'],
                            'image' => $options['parent_background_image'],
                        ],
                        'menu_item_color'  => [
                            'background'       => $options['mega_menu_items_background_colour'],
                            'background_hover' => $options['mega_menu_items_background_hover_colour'],
                            'text'             => $options['mega_menu_items_text_colour'],
                            'text_hover'       => $options['mega_menu_items_text_hover_colour'],
                        ]
                    ],
                    'rows' => [
                        '0' => [
                            'meta' => [
                                'row_size' => 12
                            ],
                            'columns' => []
                        ]
                    ]
                ];
            } else if( empty( $mega_menu[$key]['type'] ) && ! empty( $parent_key ) ) {
                $column_size = 4;

                if ( 'auto' != $options['width'] ) {
                    $column_size = $options['width'];
                }

                $menu_items = [];
                if ( ! empty( $options['widgets'] ) ) {
                    foreach( $options['widgets'] as $items ) {
                        if  ( ! empty( $items['title'] ) ) {
                            $title = $this->get_title_by_menu_item_id( $older_options , $key );
                            $menu_items[] = [
                                'item_id' => $key,
                                'item_type' => 'menu_item',
                                'item_title' => $title,
                            ];

                        } else {
                            $widget_contents = '';
                            if (  ! empty( $items['image'] ) ) {
                                $widget_contents = sprintf('<img src="%s"/>', esc_url( $items['image']['url'] ) );
                            } elseif ( ! empty( $items['text'] ) ) {
                                $widget_contents = $items['text']['text'];
                            }

                            $widget_id = $this->insert_widget_in_sidebar(
                                'custom_html',
                                [
                                    'title'    => '',
                                    'content' => $widget_contents,
                                ],
                                'rmp-sidebar'
                            ); 

                            $menu_items[] = [
                                'item_id'    => $widget_id,
                                'item_type'  => 'widget',
                                'item_title' => 'Custom HTML'
                            ];                    
                        }
                    }
                }
                $mega_options[$parent_key]['rows'][0]['columns'][] = [
                    'meta'       => [
                        'column_size'       => $column_size,
                        'column_padding'    => [
                            'left'   => $options['top_padding'],
                            'top'    => $options['right_padding'],
                            'right'  => $options['bottom_padding'],
                            'bottom' => $options['left_padding'],
                        ],
                        'column_background' => [
                            'color' => '',
                            'hover' => '',
                        ]
                    ],
                    'menu_items' => $menu_items
                ];
            } else {
                $parent_key = 0;
            }
        }

        return $mega_options;
    }

    /**
     * Insert a widget in a sidebar.
     * 
     * @param string $widget_id   ID of the widget (search, recent-posts, etc.)
     * @param array  $widget_data  Widget settings.
     * @param string $sidebar     ID of the sidebar.
     */
    public function insert_widget_in_sidebar( $widget_id, $widget_data, $sidebar ) {
        // Retrieve sidebars, widgets and their instances
        $sidebars_widgets = get_option( 'sidebars_widgets', array() );
        $widget_instances = get_option( 'widget_' . $widget_id, array() );

        // Retrieve the key of the next widget instance
        $numeric_keys = array_filter( array_keys( $widget_instances ), 'is_int' );
        $next_key = $numeric_keys ? max( $numeric_keys ) + 1 : 2;

        // Add this widget to the sidebar
        if ( ! isset( $sidebars_widgets[ $sidebar ] ) ) {
            $sidebars_widgets[ $sidebar ] = array();
        }
        $sidebars_widgets[ $sidebar ][] = $widget_id . '-' . $next_key;

        // Add the new widget instance
        $widget_instances[ $next_key ] = $widget_data;

        // Store updated sidebars, widgets and their instances
        update_option( 'sidebars_widgets', $sidebars_widgets );
        update_option( 'widget_' . $widget_id, $widget_instances );

        return $widget_id . '-' . $next_key;
    }

    public function get_title_by_menu_item_id( $options, $item_id ) {

        if( ! empty( $options['theme_location_menu'] ) ) {
            $menu = get_term(get_nav_menu_locations()[$options['theme_location_menu']], 'nav_menu')->name;
        } elseif( ! empty( $options['menu_to_use'] ) ) {
            $menu = $options['menu_to_use'];
        } else {
            $menu = get_terms('nav_menu')[0]->slug;
        }
    
        $nav_menu_items =  wp_get_nav_menu_items($menu);

        foreach($nav_menu_items as $menu_item ) {
            if( $menu_item->ID === $item_id ) {
                return $menu_item->title;
            }
        }

        return;
    }

    /**
     * Function to separate the global setting options.
     * 
     * @param array  $older_options List of options.
     */
    public function migrate_global_settings( $older_options ) {

        if ( empty( $older_options ) ) {
            return;
        }

        $global_options = [];
        $global_options['rmp_custom_css']               = $older_options['custom_css'];
        $global_options['rmp_license_key']              = get_option('responsive_menu_pro_license_key');
        $global_options['rmp_external_files']           = $older_options['external_files'];
        $global_options['rmp_minify_scripts']           = $older_options['minify_scripts'];
        $global_options['rmp_remove_glyphicon']         = $older_options['remove_bootstrap'];
        $global_options['rmp_scripts_in_footer']        = $older_options['scripts_in_footer'];
        $global_options['rmp_remove_fontawesome']       = $older_options['remove_fontawesome'];

        if( 'on' === $older_options['menu_adjust_for_wp_admin_bar'] ) {
            $global_options['menu_adjust_for_wp_admin_bar'] = 'adjust';
        } else {
            $global_options['menu_adjust_for_wp_admin_bar'] = 'none';
        }

        $global_options = array_merge( rmp_global_default_setting_options(), $global_options );
        update_option( 'rmp_global_setting_options', $global_options );

    }



    public function get_table_options() {

        if ( ! $this->is_rmp_table_exist() ) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'responsive_menu_pro';

        $query   = sprintf( 'SELECT * FROM %s', $table_name );
        $results = $wpdb->get_results( $query , ARRAY_A);

        $options = [];
        foreach( $results as $result ) {
            $options[$result['name']] = $result['value'];
        }

        return $options;
    }

    /**
     * Function to check the table is exist or not.
     * 
     * @access public
     * 
     * @return bool
     */
    public function is_rmp_table_exist() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'responsive_menu_pro';

        $sql_query = $wpdb->prepare(
            "SHOW TABLES LIKE %s",
            $table_name
        );

        if ( $wpdb->get_var( $sql_query ) === $table_name ) {
           return true;
        } 

        return false;
    }

}

RMP_Migration::get_instance();

endif;
