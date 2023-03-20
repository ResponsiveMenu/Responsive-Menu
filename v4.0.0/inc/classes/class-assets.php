<?php
/**
 * Assets class.
 *
 * This class is responsible to load the resources as per page call.
 *
 * @since      4.0.0
 * @author     Expresstech System
 * @package    responsive_menu_pro
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Assets
 */
class Assets {

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
	 * @return void
	 */
	protected function setup_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_custom_style_inline' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'rmp_menu_editor_style_inline' ) );
	}

	/**
	 * Add custom css to manage headerbar extra padding.
	 *
	 * @since 4.0.1
	 */
	public function rmp_menu_editor_style_inline() {
		$editor = filter_input( INPUT_GET, 'editor', FILTER_SANITIZE_URL );
		if ( ! empty( $editor ) && 'rmp_menu' === get_post_type() && current_user_can( 'administrator' ) ) {
			$css_data = 'html.wp-toolbar {
				margin: 0;
				padding: 0 !important;
			}';
			wp_add_inline_style( 'rmp_admin_main_styles', $css_data );
		}
	}

	/**
	 * Add custom css to manage size of admin menu logo.
	 *
	 * @since 4.0.0
	 */
	public function admin_custom_style_inline() {
		wp_register_style( 'rmp_admin_inline', false, array(), RMP_PLUGIN_VERSION );
		wp_enqueue_style( 'rmp_admin_inline' );

		$css_data = '
			#adminmenu .menu-icon-rmp_menu .wp-menu-image img{
				height: 18px;
			}

			.responsive-menu-license-upgrade-link {
				color: #f80668;
				font-weight: 600;
			}
		';
		wp_add_inline_style( 'rmp_admin_inline', $css_data );
	}

	/**
	 * To enqueue scripts and styles in admin.
	 *
	 * @param string $hook_suffix Admin page name.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$post_type = get_post_type();

		if ( empty( $post_type ) && ! empty( $_GET['post_type'] ) ) {
			$post_type = sanitize_text_field( wp_unslash( $_GET['post_type'] ) );
		}

		if ( 'rmp_menu' !== $post_type || ! current_user_can( 'administrator' ) ) {
			return;
		}

		if ( wp_is_mobile() ) {
			wp_enqueue_script( 'jquery-touch-punch' );
		}

		/**
		 * Fires before enqueue the scripts.
		 */
		do_action( 'before_rmp_enqueue_scripts' );

		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'wp-color-picker' );

		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_script(
			'rmp_admin_selectize_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/selectize.js',
			null,
			RMP_PLUGIN_VERSION,
			true
		);

		wp_enqueue_style(
			'rmp_admin_selectize_styles',
			RMP_PLUGIN_URL_V4 . '/assets/admin/scss/selectize.css',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_style(
			'rmp_admin_main_styles',
			RMP_PLUGIN_URL_V4 . '/assets/admin/build/css/rmpMain.css',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'rmp_admin_dropzone_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/dropzone.min.js',
			array( 'jquery' ),
			RMP_PLUGIN_VERSION,
			true
		);

		wp_enqueue_style(
			'rmp_admin_styles',
			RMP_PLUGIN_URL_V4 . '/assets/admin/scss/admin.css',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_register_script(
			'rmp_admin_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/build/js/rmpMain.js',
			array( 'wp-color-picker', 'jquery' ),
			RMP_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'rmp_admin_scripts',
			'rmpObject',
			array(
				'ajaxURL'           => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'        => wp_create_nonce( 'rmp_nonce' ),
				'THEMES_FOLDER_URL' => wp_upload_dir()['baseurl'] . '/rmp-themes/',
			)
		);

		wp_enqueue_script( 'rmp_admin_scripts' );

		/** Enqueue the icons resources */

		wp_enqueue_style( 'dashicons' );

		wp_enqueue_style(
			'rmp-admin-fontawesome-icons',
			'https://use.fontawesome.com/releases/v5.13.0/css/all.css',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_style(
			'rmp-admin-glyph-icons',
			RMP_PLUGIN_URL_V4 . '/assets/admin/scss/glyphicons.css',
			null,
			RMP_PLUGIN_VERSION
		);

		/**
		 * Fires after enqueue the admin scripts.
		 */
		do_action( 'after_rmp_enqueue_admin_scripts' );
	}
}
