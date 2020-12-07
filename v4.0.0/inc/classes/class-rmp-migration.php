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

        if ( ! empty( get_option('responsive_menu_version') ) ) {
            add_action( 'rmp_after_cpt_registered', array( $this, 'migrate' ) );
        }
		
	}

    /**
     * Function to migrate the options and convert it to new menu.
     */
    public function migrate() {

        if ( ! empty( get_option( 'rmp_migrate10111') ) ) {
            return;
        }

        $older_options = $this->get_table_options();

        //Separate the global options and migrate it into new format.
        $this->migrate_global_settings( $older_options );

        $converted_options = $this->convert_older_menu_option_to_new_format();

        $new_menu = array(
			'post_title'  => 'Default Menu',
			'post_author' => get_current_user_id(),
			'post_status' => 'publish',
			'post_type'   => 'rmp_menu',
		);

        $post_id = wp_insert_post( $new_menu );

        if ( ! empty( $post_id ) ) {

            $converted_options['menu_name'] = 'Default Menu';
            update_post_meta( $post_id, 'rmp_menu_meta', $converted_options );
    
            /**
             * Fires when menu is migrated.
             * 
             * @param int $post_id
             */
            do_action( 'rmp_migrate_menu_style', $post_id );
        }

       update_option( 'rmp_migrate10111', true );
    }

    /**
     * Convert all required the options to new responsive menu.
     */
    public function convert_older_menu_option_to_new_format() {

        $older_options = $this->get_table_options();
        $new_options = $older_options;

        //Menu elements order.
        $new_options['items_order'] = json_decode( $older_options['items_order'], true );

        //Migrate all font icon options.
        $page_icons = json_decode( $older_options['menu_font_icons'], true );
        $menu_item_icons = [];

        //Migrate toggle button events.
        $new_options['button_trigger_type_click'] = 'on';
        $new_options['button_trigger_type_hover'] = 'off';

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

        $new_options['menu_sub_arrow_border_width'] = 1;
        if( ! empty( $older_options['menu_border_width'] ) ) {
            $new_options['menu_sub_arrow_border_width'] = $older_options['menu_border_width'];
        }

        $new_options['menu_sub_arrow_border_width_unit'] = 'px';
        if( ! empty( $older_options['menu_border_width_unit'] ) ) {
            $new_options['menu_sub_arrow_border_width_unit'] = $older_options['menu_border_width_unit'];
        }

        $new_options['submenu_sub_arrow_border_width'] = 1;
        if( ! empty( $older_options['submenu_border_width'] ) ) {
            $new_options['submenu_sub_arrow_border_width'] = $older_options['submenu_border_width'];
        }

        $new_options['submenu_sub_arrow_border_width_unit'] = 'px';
        if( ! empty( $older_options['menu_border_width_unit'] ) ) {
            $new_options['submenu_sub_arrow_border_width_unit'] = $older_options['submenu_border_width_unit'];
        }

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
        $global_options['menu_adjust_for_wp_admin_bar'] = 'hide';

        $global_options = array_merge( rmp_global_default_setting_options(), $global_options );
        update_option( 'rmp_global_setting_options', $global_options );

    }

    public function get_table_options() {

        if ( ! $this->is_rmp_table_exist() ) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'responsive_menu';

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
        $table_name = $wpdb->prefix . 'responsive_menu';

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
