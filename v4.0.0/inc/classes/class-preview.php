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
	 * Menu elements the live preview can render, mapped to the RMP_Menu
	 * method that renders each one.
	 *
	 * The keys match the `data-toggle` values on the item-order checkboxes in
	 * templates/menu-elements/.
	 *
	 * @since 4.7.4
	 *
	 * @var array
	 */
	const PREVIEW_ELEMENTS = array(
		'menu'                => 'menu',
		'search'              => 'menu_search_box',
		'title'               => 'menu_title',
		'social-icons'        => 'menu_social_icons',
		'additional-content'  => 'menu_additional_content',
	);

	/**
	 * This function get the content of menu item for live preview element.
	 *
	 * @return HTML
	 */
	public function enable_menu_item() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_id = isset( $_POST['menu_id'] ) ? absint( wp_unslash( $_POST['menu_id'] ) ) : 0;

		if ( empty( $menu_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Menu ID missing !', 'responsive-menu' ) ) );
		}

		/**
		 * The element name is a slug, not a number — intval() collapsed every
		 * value except 'menu' to 0, so no branch below ever matched and the
		 * preview always rendered the additional-content element.
		 */
		$menu_element = isset( $_POST['menu_element'] ) ? sanitize_key( wp_unslash( $_POST['menu_element'] ) ) : '';

		if ( ! isset( self::PREVIEW_ELEMENTS[ $menu_element ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Unknown menu element !', 'responsive-menu' ) ) );
		}

		$menu   = new RMP_Menu( $menu_id );
		$method = self::PREVIEW_ELEMENTS[ $menu_element ];

		/**
		 * Every render method echoes its markup and returns nothing, so the
		 * output has to be captured — otherwise it is emitted before the JSON
		 * body and the response is not parseable.
		 */
		ob_start();
		$menu->$method();
		$html = ob_get_clean();

		wp_send_json_success( array( 'markup' => $html ) );
	}
}
