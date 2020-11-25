<?php
/**
 * This file contain the Widget_Manager class and it's functions.
 * 
 * @version 4.0.0
 * @author  Expresstech System
 * 
 * @package responsive-menu-pro
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Widget_Manager
 * This class is responsible for handling the operation related to mega menu widgets.
 * 
 * @version 4.0.0
 * 
 */
class Widget_Manager {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @version 4.0.0
	 * 
	 * @return void
	 */
	protected function setup_hooks() {
		add_action( 'init', array( $this, 'register_sidebar' ) );
        add_action('wp_ajax_rmp_add_widget', array($this,'rmp_add_widget') );
        add_action('wp_ajax_rmp_edit_widget', array($this,'rmp_edit_widget') );
        add_action('wp_ajax_rmp_save_widget', array($this,'rmp_save_widget') );
        add_action('wp_ajax_rmp_delete_widget', array($this,'rmp_delete_widget') );
	}


    /**
     * Returns the HTML for a single widget instance.
     *
     * @since 4.0.0
     * @param string widget_id Something like meta-3
     */
    public function show_widget( $id ) {

        if ( empty( $id ) ) {
            return;
        }

        global $wp_registered_widgets;

        //Check widget is exist.
        if ( empty( $wp_registered_widgets[$id]['name'] ) ) {
            return;
        }

        $params = array_merge(
            array( array_merge( array( 'widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name'] ) ) ),
            (array) $wp_registered_widgets[$id]['params']
        );

        $params[0]['id'] = 'rmp-sidebar';
        $params[0]['before_title'] = apply_filters( "rmp_before_widget_title", '<h4 class="mega-block-title">', $wp_registered_widgets[$id] );
        $params[0]['after_title'] = apply_filters( "rmp_after_widget_title", '</h4>', $wp_registered_widgets[$id] );
        $params[0]['before_widget'] = apply_filters( "rmp_before_widget", "", $wp_registered_widgets[$id] );
        $params[0]['after_widget'] = apply_filters( "rmp_after_widget", "", $wp_registered_widgets[$id] );

        if ( defined("RMP_DYNAMIC_SIDEBAR_PARAMS") && RMP_DYNAMIC_SIDEBAR_PARAMS ) {
            $params[0]['before_widget'] = apply_filters( "rmp_before_widget", '<div id="" class="">', $wp_registered_widgets[$id] );
            $params[0]['after_widget'] = apply_filters( "rmp_after_widget", '</div>', $wp_registered_widgets[$id] );

            $params = apply_filters('dynamic_sidebar_params', $params);
        }

        $callback = $wp_registered_widgets[$id]['callback'];

        if ( is_callable( $callback ) ) {
            ob_start();
            call_user_func_array( $callback, $params );
            return ob_get_clean();
        }

    }

    /**
     * Save a widget
     *
     * @since 4.0.0
     */
    public function rmp_save_widget() {

       // check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );


        $widget_id = sanitize_text_field( $_POST['widget_id'] );    
		if ( empty( $widget_id ) ) {
            wp_send_json_error(
                [ 'message' => __( 'Widget Id is missing', 'responsive-menu-pro' ) ]
            );
        }

        $id_base = sanitize_text_field( $_POST['id_base'] );    
		if ( empty( $id_base ) ) {
            wp_send_json_error(
                [ 'message' => __( 'Base Id is missing', 'responsive-menu-pro' ) ]
            );
        }

        $saved = $this->save_widget( $id_base );

        if ( $saved ) {
            wp_send_json_success( sprintf( __("Saved %s", "responsive-menu-pro"), $id_base ) );
        } else {
            wp_send_json_error( sprintf( __("Failed to save %s", "responsive-menu-pro"), $id_base ) );
        }

    }

    /**
     * Saves a widget. Calls the update callback on the widget.
     * The callback inspects the post values and updates all widget instances which match the base ID.
     *
     * @since 4.0.0
     * @param string $id_base - e.g. 'meta'
     * @return bool
     */
    public function save_widget( $id_base ) {
        global $wp_registered_widget_updates;

        $control = $wp_registered_widget_updates[$id_base];

        if ( is_callable( $control['callback'] ) ) {

            call_user_func_array( $control['callback'], $control['params'] );

            do_action( "rmp_after_widget_save" );

            return true;
        }

        return false;

    }

    /**
     * Function to create a new widget.
     * 
     * @since 4.0.0
     * 
     * @return HTML
     */

    public function rmp_edit_widget() {

        check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

        $widget_id = sanitize_text_field( $_POST['widget_id'] );
    
		if ( empty( $widget_id ) ) {
            wp_send_json_error(
                [ 'message' => __( 'Widget Id is missing', 'responsive-menu-pro' ) ]
            );
        }

        $html = $this->widget_edit_form( $widget_id );
        
        if ( $html ) {
           wp_send_json_success( $html);
        } else {
           wp_send_json_error( sprintf( __( 'Failed to edit %s','responsive-menu-pro'), $widget_id ) );
        }

     }

     /**
     * Deletes a widget
     *
     * @since 4.0.0
     */
    public function rmp_delete_widget() {

        check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

        $widget_id = sanitize_text_field( $_POST['widget_id'] );

        $this->remove_widget_from_sidebar( $widget_id );
        $this->remove_widget_instance( $widget_id );

        do_action( "rmp_after_widget_delete" );

        wp_send_json_success( sprintf( __( "Deleted %s", "responsive-menu-pro"), $widget_id ) );
    }

     /**
     * Removes a widget from the Mega Menu widget sidebar
     *
     * @since 4.0.0
     * @return string 
     */
    private function remove_widget_from_sidebar($widget_id) {

        $widgets = $this->get_mega_menu_sidebar_widgets();

        $rmp_widgets = array();

        foreach ( $widgets as $widget ) {

            if ( $widget != $widget_id )
                $rmp_widgets[] = $widget;

        }

        $this->set_mega_menu_sidebar_widgets($rmp_widgets);

        return $widget_id;

    }

    /**
     * Removes a widget instance from the database
     *
     * @since 4.0.0
     * @param string $widget_id 
     * @return bool. True if widget has been deleted.
     */
    private function remove_widget_instance( $widget_id ) {

        $id_base = $this->get_id_base_for_widget_id( $widget_id );
        $widget_number = $this->get_widget_number_for_widget_id( $widget_id );

        // add blank widget
        $current_widgets = get_option( 'widget_' . $id_base );

        if ( isset( $current_widgets[ $widget_number ] ) ) {
            unset( $current_widgets[ $widget_number ] );
            update_option( 'widget_' . $id_base, $current_widgets );

            return true;
        }

        return false;

    }

    /**
     * Returns the widget number
     *
     * @since 4.0.0
     * @param string $widget_id
     * @return int
     */
    public function get_widget_number_for_widget_id( $widget_id ) {

        $parts = explode( "-", $widget_id );

        return absint( end( $parts ) );
    }



    /**
     * Shows the widget edit form for the specified widget.
     *
     * @since 1.0
     * @param $widget_id - id_base-ID (eg meta-3)
     */
     public function widget_edit_form( $widget_id ) {
        global $wp_registered_widget_controls;

        $control = $wp_registered_widget_controls[ $widget_id ];

        // $parts = explode( "-", $widget_id );
        // $widget_number = absint( end( $parts ) );

        ob_start();

        $id_base = $this->get_id_base_for_widget_id( $widget_id );

        printf(
            '<form method="post">
                <input type="hidden" name="widget_id" class="widget-id" value="%1$s" />
                <input type="hidden" name="action" value="rmp_save_widget" />
                <input type="hidden" name="id_base" class="id_base" value="%2$s" />
                <div class="widget-content">',
            esc_attr( $widget_id ),
            esc_attr( $id_base )
        );

        if ( is_callable( $control['callback'] ) ) {
            call_user_func_array( $control['callback'], $control['params'] );
        }

        printf(
            '<div class="widget-controls">
                <a class="widget-delete" href="#delete">%s</a> |
                <a class="widget-close" href="#close">%s</a>
            </div>',
            __( 'Delete', 'responsive-menu-pro' ),
            __( 'Done', 'responsive-menu-pro' )
        );

        print '<span class="spinner"></span>';

        submit_button( __( 'Save' ), 'button-primary alignright rmp-save-widget', 'savewidget', false );

        print '</div></form>';

        return ob_get_clean();
     }

    /**
     * Function to create a new widget.
     * 
     * @since 4.0.0
     * 
     * @return HTML
     */

     public function rmp_add_widget() {

        check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

        $widget_base_id = sanitize_text_field( $_POST['widget_base_id'] );
        $widget_title   = sanitize_text_field( $_POST['widget_title'] );
        $menu_item_id   = sanitize_text_field( $_POST['menu_item_id'] );
    
		if ( empty( $widget_base_id ) || empty( $widget_title ) || empty( $menu_item_id ) ) {
            wp_send_json_error(
                [ 'message' => __( 'Widget parameter is missing', 'responsive-menu-pro' ) ]
            );
        }

        $widget = $this->prepare_widget( $widget_base_id, $menu_item_id, $widget_title);

        if ( $widget ) {
           wp_send_json_success( $widget);
        } else {
           wp_send_json_error( sprintf( __( 'Failed to add %s to %d','responsive-menu-pro'), $widget_base_id, $menu_item_id ) );
        }

     }

     /**
      * Prepare widget as menu item for menu.
      */
     public function prepare_widget( $id_base, $menu_item_id, $title ) {

        $widget_id = $this->get_new_widget_id( $id_base, $menu_item_id );

        $html = sprintf(
            '<div class="widget" widget-title="%1$s" widget-id="%2$s" widget-type="widget" id="%2$s">
                <div class="widget-top">
                    <div class="widget-title-action">
                        <a class="widget-option widget-action rmp-widget-edit">
                            <span class="fas fa-wrench"></span>
                        </a>
                    </div>
                    <div class="widget-title">%1$s</div>
                </div>
                <div class="widget-inner widget-inside"></div>
            </div>',
            esc_html( $title ),
            esc_attr( $widget_id )
        );
    
        return $html;
    }

    /**
    * Add new widget and get it's id.
    * @return int $widget_id
    */
    public function get_new_widget_id( $id_base, $menu_item_id ) {

        require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

        $next_id = next_widget_id_number( $id_base );

        $this->add_widget_instance( $id_base, $next_id, $menu_item_id );

        $widget_id = $this->add_widget_to_sidebar( $id_base, $next_id );

        return $widget_id;
    }



     /**
     * Adds a new widget instance of the specified base ID to the database.
     *
     * @param string $id_base
     * @param int $next_id
     * @param int $menu_item_id
     */
    private function add_widget_instance( $id_base, $next_id, $menu_item_id ) {

        $current_widgets = get_option( 'widget_' . $id_base );

        $current_widgets[ $next_id ] = array(
            "rmp_parent_menu_id" => $menu_item_id
        );

        update_option( 'widget_' . $id_base, $current_widgets );
    }

    /**
     * Adds a widget to the  RMP widget sidebar
     *
     * @since 4.0.0
     */
    private function add_widget_to_sidebar( $id_base, $next_id ) {

        $widget_id = $id_base . '-' . $next_id;

        $sidebar_widgets = $this->get_mega_menu_sidebar_widgets();

        $sidebar_widgets[] = $widget_id;

        $this->set_mega_menu_sidebar_widgets($sidebar_widgets);

        do_action( "rmp_after_widget_add" );

        return $widget_id;

    }

    /**
     * Returns an unfiltered array of all widgets in our sidebar
     *
     * @since 4.0.0
     * @return array
     */
    public function get_mega_menu_sidebar_widgets() {

        $sidebar_widgets = wp_get_sidebars_widgets();

        if ( ! isset( $sidebar_widgets[ 'rmp-sidebar'] ) ) {
            return false;
        }

        return $sidebar_widgets[ 'rmp-sidebar' ];

    }


    /**
     * Sets the sidebar widgets
     *
     * @since 4.0.0
     */
    private function set_mega_menu_sidebar_widgets( $widgets ) {

        $sidebar_widgets = wp_get_sidebars_widgets();

        $sidebar_widgets[ 'rmp-sidebar' ] = $widgets;

        wp_set_sidebars_widgets( $sidebar_widgets );

    }

     /**
     * Returns the id_base value for a Widget ID
     *
     * @since 4.0.0
     */
    public function get_id_base_for_widget_id( $widget_id ) {
        global $wp_registered_widget_controls;

        if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
            return false;
        }

        $control = $wp_registered_widget_controls[ $widget_id ];

        $id_base = isset( $control['id_base'] ) ? $control['id_base'] : $control['id'];

        return $id_base;
    }

    /**
     * Create our own widget area to store all mega menu widgets.
     * All widgets from all menus are stored here, they are filtered later
     *
     * @since 4.0.0
     */
    public function register_sidebar() {

        register_sidebar(
            [
                'id'            => 'rmp-sidebar',
                'name'          => __('Responsive Mega Menu Widgets', 'responsive-menu-pro'),
                'description'   => __('Here it stored all the widgets of responsive mega menu so please add the widgets from menu', 'responsive-menu-pro'),
            ]
        );
    }

}
