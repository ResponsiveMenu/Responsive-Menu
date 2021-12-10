<?php
/**
 * Preview class.
 * This class is responsible for preview related functionality.
 *
 * @version 4.0.0
 * @author  Expresstech System
 *
 * @package responsive-menu
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;
use RMP\Features\Inc\RMP_Menu;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Preview
 */
class Preview {

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
		add_action( 'wp_ajax_rmp_enable_menu_item', array( $this, 'enable_menu_item' ) );
	}

	/**
	 * This function get the content of menu item for live preview element.
	 *
	 * @return HTML
	 */
	public function enable_menu_item() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_id      = isset( $_POST['menu_id'] ) ? intval( wp_unslash( $_POST['menu_id'] ) ) : '';
		$menu_element = isset( $_POST['menu_element'] ) ? intval( wp_unslash( $_POST['menu_element'] ) ) : '';
		$menu         = new RMP_Menu( $menu_id );

		if ( 'menu' === $menu_element ) {
			$html = $menu->menu();
		} elseif ( 'search' === $menu_element ) {
			$html = $menu->menu_search_box();
		} elseif ( 'title' === $menu_element ) {
			$html = $menu->menu_title();
		} else {
			$html = $menu->menu_additional_content();
		}

		wp_send_json_success( array( 'markup' => $html ) );
	}
}
