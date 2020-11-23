<?php
/**
 * Editor_Manager class.
 * This class is responsible for editing the menu functionality.
 * 
 * @version 4.0.0
 * @author  Expresstech System
 * 
 * @package responsive-menu-pro
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;
use RMP\Features\Inc\Option_Manager;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Editor_Manager
 */
class Editor_Manager {

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
		add_action('wp_ajax_rmp_save_menu_action', array( $this, 'rmp_save_options' ) );
		add_action('wp_ajax_rmp_save_draft_options', array( $this, 'rmp_save_draft_options' ) );
		add_action('wp_ajax_rmp_mega_menu_item_enable', array( $this, 'enable_mega_menu_item' ) );
		add_action('wp_ajax_rmp_save_mega_menu_item', array( $this, 'rmp_save_mega_menu_item' ) );

		add_filter( 'wp_is_mobile', [$this, 'check_device_mode'], 10, 1 );
	}

	/**
	 * Return the true/false as per request device.
	 * 
	 * @return boolean
	 */
	public function check_device_mode( $is_mobile ) {

		if ( $is_mobile ) {
			return true;
		}

		$rmp_device_mode = '';

		if ( ! empty( $_GET['rmp_device_mode'] ) ) {
			$rmp_device_mode = $_GET['rmp_device_mode'];
		}

		if ( empty( $rmp_device_mode) || 'desktop' === $rmp_device_mode ) {
			return false;
		}

		return true;
	}

	/**
	 * This function save the settings and meta of mega menu item.
	 * 
	 * @version 4.0.0
	 * 
	 * @return json
	 */
	public function rmp_save_mega_menu_item() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$item_id = sanitize_text_field( $_POST['item_id'] );
		if ( empty( $item_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Menu Item ID missing', 'responsive-menu-pro' ) ] );
        }

        $menu_id = sanitize_text_field( $_POST['menu_id'] );
		if ( empty( $menu_id ) ) {
            wp_send_json_error( 
                [ 'message' => __( 'Menu ID missing !', 'responsive-menu-pro' )]);
		}

		if ( empty( $_POST['item_meta'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Unable to get mega menu settings', 'responsive-menu-pro' )] );
        }

		$item_meta = [];

		// Don't forget to sanitize the data using recursive.
		if ( is_array( $_POST['item_meta'] ) ) {

			$item_meta = $_POST['item_meta'];
		}

		update_post_meta( $menu_id, '_rmp_mega_menu_'. $item_id, $item_meta );

		/**
		 * Fires when mega menu item settings update.
		 * 
		 * @version 4.0.0 
		 * 
		 * @param int   $menu_id    Menu Id.
		 * @param int   $item_id    Item ID for which mega menu settings are updated
		 * @param array $item_meta  List of mega menu settings of an item.
		 */
		do_action( 'rmp_update_mega_menu_item', $menu_id, $item_id, $item_meta );

        wp_send_json_success( ['message' => 'success'] );

	}

	/**
	 * Function to update the enable option of mega menu item.
	 * 
	 * @version 4.0.0
	 * 
	 * @return json
	 */
	public function enable_mega_menu_item() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_id  = sanitize_text_field( $_POST['menu_id'] );
		$item_id  = sanitize_text_field( $_POST['item_id'] );
		$value    = sanitize_text_field( $_POST['value'] );

		$options = get_post_meta( $menu_id, 'rmp_menu_meta' );

		if ( ! empty( $options ) ) {
			$options = $options[0];
			$options['mega_menu'][$item_id] = $value ;
			$options = update_post_meta( $menu_id, 'rmp_menu_meta', $options );

			wp_send_json_success( [ 'message' => __( 'Success', 'responsive-menu-pro' ) ] );
		}

		wp_send_json_error( 
			[ 'message' => __( 'Menu not found', 'responsive-menu-pro' ) ]
		);

	}

	/**
	 * This function saved the menu options when click update in the menu editor.
	 *
	 * @since	4.0.0
	 * 
	 * @return json
	 */
	public function rmp_save_options() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$options = [];
		parse_str( $_POST['form'], $options );

		$menu_id = sanitize_text_field( $options['menu_id'] );
		if ( empty( $menu_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Menu ID missing !', 'responsive-menu-pro' ) ] );
		}

		$active_device   = sanitize_text_field( $options['rmp_device_mode'] );

		$options = $options['menu'];

		// Instance of option manager.
		$option_manager  = Option_Manager::get_instance();

		//Get settings which has multi device options.
		$device_options  = rmp_get_multi_device_options();

		// Extract the settings which has multi device options and save the settings with a active device.
		$device_options = array_intersect_key( $options, $device_options );

		//Combine the mobile options and saved.
		if ( 'mobile' === $active_device ) {
			update_post_meta( $menu_id, '_mobile', $device_options );
		} else {
			$mobile_options  = get_transient( $menu_id . '_mobile' );
			if ( empty( $mobile_options ) || ! is_array( $mobile_options ) ) {
				$mobile_options = [];
			}

			$saved_options  = $option_manager->get_mobile_options( $menu_id );
			if ( empty( $saved_options ) || ! is_array( $saved_options ) ) {
				$saved_options = [];
			}

			$mobile_options = array_merge( $saved_options, $mobile_options );
			update_post_meta( $menu_id, '_mobile', $mobile_options );

			if ( ! empty( $mobile_options ) && is_array( $mobile_options ) ) {
				$options = array_merge( $options, $mobile_options );
			}
		}

		// Combine tablet options and saved.
		if ( 'tablet' === $active_device ) {
			update_post_meta( $menu_id, '_tablet', $device_options );
		} else {

			$tablet_options  = get_transient( $menu_id . '_tablet' );
			if ( empty( $tablet_options ) || ! is_array( $tablet_options ) ) {
				$tablet_options = [];
			}

			$saved_options  = $option_manager->get_tablet_options( $menu_id );
			if ( empty( $saved_options ) || ! is_array( $saved_options ) ) {
				$saved_options = [];
			}

			$tablet_options = array_merge( $saved_options, $tablet_options );

			update_post_meta( $menu_id, '_tablet', $tablet_options );
		}

		// Combine tablet options and saved.
		if ( 'desktop' === $active_device ) {
			update_post_meta( $menu_id, '_desktop', $device_options );
		} else {

			$desktop_options  = get_transient( $menu_id . '_desktop' );
			if ( empty( $desktop_options ) || ! is_array( $desktop_options ) ) {
				$desktop_options = [];
			}

			$saved_options  = $option_manager->get_tablet_options( $menu_id );
			if ( empty( $saved_options ) || ! is_array( $saved_options ) ) {
				$saved_options = [];
			}

			$desktop_options = array_merge( $saved_options, $desktop_options );
			update_post_meta( $menu_id, '_desktop', $desktop_options );

		}

		update_post_meta( $menu_id, 'rmp_menu_meta', $options );
		delete_transient( $menu_id . '_mobile' );
		delete_transient( $menu_id . '_tablet' );
		delete_transient( $menu_id . '_desktop' );

		/**
		 * Fires when saved the options.
		 * 
		 * @version 4.0.0
		 * @param int $menu_id Menu ID.
		 */
		do_action( 'rmp_save_menu', $menu_id );

		// Return the response after success.
		wp_send_json_success();
	}

	/**
	 * Temporary store the options for multi device options.
	 * 
	 * Draft menu options as per device and also
	 * Returns the options for selected device.
	 *
	 * @since	4.0.0
	 * 
	 * @return json
	 */
	public function rmp_save_draft_options() {

		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_id = filter_input( INPUT_POST, 'menu_id', FILTER_SANITIZE_STRING );
		if ( empty( $menu_id ) ) {
			wp_send_json_error( __('Menu ID missing !', 'responsive-menu-pro') );	
		}

		$save_for_device = filter_input( INPUT_POST, 'save_for_device', FILTER_SANITIZE_STRING );
		$move_on_device  = filter_input( INPUT_POST, 'move_on_device', FILTER_SANITIZE_STRING );

		$options = array();
		if ( ! empty( $_POST['options'] ) && is_array( $_POST['options'] ) ) {
			foreach( $_POST['options'] as $key => $value ) {
				$options[ $key ] = sanitize_text_field( $value );
			}	
		}

		// Save device related options on transient.
		set_transient( $menu_id . '_'. $save_for_device , $options, 3600 );

		$option_manager = Option_Manager::get_instance();

		if ( 'desktop' === $move_on_device ) {
			$device_options = $option_manager->get_desktop_options( $menu_id );
		} elseif( 'tablet' === $move_on_device ) {
			$device_options = $option_manager->get_tablet_options( $menu_id );
		} else {
			$device_options = $option_manager->get_mobile_options( $menu_id );
		}

		if ( empty( $device_options ) ) {
			$device_options = [];
		}

		$draft_options = get_transient( $menu_id . '_'. $move_on_device );

		if ( ! empty( $draft_options ) && is_array( $draft_options ) ) {
			$device_options = array_merge( $device_options, $draft_options );
		}

		wp_send_json_success( $device_options );
	}

}
