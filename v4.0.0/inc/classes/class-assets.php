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
 * 
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

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action('admin_head', [ $this, 'admin_custom_style_inline'] );
		add_action('admin_head', [ $this, 'rmp_menu_editor_style_inline'] );
	}

	/**
	 * Add custom css to manage headerbar extra padding.
	 * 
	 * @since 4.0.1
	 */
	function rmp_menu_editor_style_inline() {

		$editor = filter_input( INPUT_GET, 'editor', FILTER_SANITIZE_STRING );
		if ( ! empty( $editor ) && get_post_type() == 'rmp_menu' && is_admin() ) {
			echo '<style>
			html.wp-toolbar {
				margin: 0;
				padding: 0 !important;
			}
			</style>';
		}
	}

	/**
	 * Add custom css to manage size of admin menu logo.
	 * 
	 * @since 4.0.0
	 */
	function admin_custom_style_inline() {
		echo '<style>
			#adminmenu .menu-icon-rmp_menu .wp-menu-image img{
				height: 18px;
			}

			.responsive-menu-license-upgrade-link {
				color: #f80668;
				font-weight: 600;
			}

		</style>';
	}

	/**
	 * To enqueue scripts and styles in admin.
	 *
	 * @param string $hook_suffix Admin page name.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		

		$post_type = get_post_type(); 

		if ( empty( $post_type ) && ! empty( $_GET['post_type'] ) ) {
			$post_type = $_GET['post_type'];
		}

		if ( 'rmp_menu' !== $post_type ) {
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
			'rmp_editor_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/rmp-editor.js',
			array('jquery'),
			RMP_PLUGIN_VERSION,
			true
		);
	
		wp_enqueue_script(
			'rmp_admin_icon_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/rmp-icon.js',
			array('jquery'),
			RMP_PLUGIN_VERSION,
			true
		);

		wp_enqueue_script(
			'rmp_admin_selectize_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/selectize.js',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_style(
			'rmp_admin_selectize_styles',
			RMP_PLUGIN_URL_V4 . '/assets/admin/scss/selectize.css',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'rmp_custom_color_alpha_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/wp-color-alpha.js',
			array('wp-color-picker'),
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'rmp_admin_dropzone_scripts',
			'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js',
			array('jquery'),
			RMP_PLUGIN_VERSION
		);

		wp_enqueue_style(
			'rmp_admin_styles',
			RMP_PLUGIN_URL_V4 . '/assets/admin/scss/admin.css',
			null,
			RMP_PLUGIN_VERSION
		);

		wp_register_script(
			'rmp_admin_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/rmp-admin.js',
			array( 'wp-color-picker', 'jquery' ),
			RMP_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'rmp_admin_scripts',
			'rmpObject',
			array (
				'ajaxURL'  => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce('rmp_nonce'),
				'THEMES_FOLDER_URL' => wp_upload_dir()['baseurl'] . '/rmp-themes/',
			)
		);

		wp_enqueue_script( 'rmp_admin_scripts' );

		wp_enqueue_script(
			'rmp_preview_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/rmp-preview.js',
			array('jquery'),
			RMP_PLUGIN_VERSION,
			true
		);

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
		 * Fires after enqueue the scripts.
		 */
		do_action( 'after_rmp_enqueue_scripts' );
	}

}
