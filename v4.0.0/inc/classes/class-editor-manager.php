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
		add_action('wp_ajax_rmp_mega_menu_item_enable', array( $this, 'enable_mega_menu_item' ) );
		add_action('wp_ajax_rmp_save_mega_menu_item', array( $this, 'rmp_save_mega_menu_item' ) );

		// Hide the wp admin bar from preview iframe.
		if ( ! empty( $_GET['rmp_preview_mode'] ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}

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

		$options = $options['menu'];

		// Merge the default and update options.
		$options = array_merge( rmp_get_default_options() , $options );

		update_post_meta( $menu_id, 'rmp_menu_meta', $options );

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

}
