<?php
/**
 * Editor class.
 * This class is responsible for editor UI.
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
 * Class Editor
 */
class Editor {

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
		add_action( 'admin_head', [ $this,'render_menu_editor_page'] );
	}

	/**
	 * Function to load the menu editor page when click on particular menu
	 * customize option from menu list.
	 * 
	 * @version 4.0.0
	 * 
	 * @return void
	 */
	public function render_menu_editor_page() {

		$editor = filter_input( INPUT_GET, 'editor', FILTER_SANITIZE_STRING );
		if ( ! empty( $editor ) && get_post_type() == 'rmp_menu' && is_admin() ) {
			set_current_screen();
			include RMP_PLUGIN_PATH_V4 . '/templates/rmp-editor.php';
			exit;
		}
	}

	/**
	 * Add hooter section in editor.
	 *
	 * @version 4.0.0
	 *
	 * @param HTML.
	 */
	public function header_section( $menu_name ) {

		ob_start();
		?>
			<div id="rmp-editor-header" class="rmp-editor-header">

				<!-- Plugin logo on editor header-->
				<div class="rmp-editor-header-logo">
					<span class="dashicons dashicons-arrow-left-alt rmp-editor-header-back"></span>
					<img alt="logo" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 .'/assets/images/rmp-logo.png' ); ?>" />
				</div>

				<!-- Menu title on editor header-->
				<div class="rmp-editor-header-title"><?php echo esc_html( $menu_name ); ?></div>

				<!-- Exit from editor button in header-->
				<a class="rmp-editor-header-close" href="<?php echo esc_url( admin_url().'/edit.php?post_type=rmp_menu' ); ?>">
					<span class="fas fa-times"></span>
					<span class="screen-reader-text">
						<?php echo esc_html__('Close the editor and go back to the previous page', 'responsive-menu-pro' ); ?>
					</span>
				</a>

			</div>
		<?php

		$html = ob_get_clean();

		/**
		 * Filters the editor header.
		 *
		 * @param string|HTML $html 
		 */
		$html = apply_filters( 'rmp_editor_header_html', $html );

		return $html;
	}

	/**
	 * Add Footer section in editor.
	 *
	 * @param HTML.
	 */
	public function footer_section() {
		ob_start();
		?>
			<div id="rmp-editor-footer" class="rmp-editor-footer">

				<!-- Update the settings button in footer-->
				<button type="button" class="menu-save" id="rmp-save-menu-options">
					<?php esc_html_e( 'Update', 'responsive-menu-pro' ); ?>
				</button>

				<!-- Themes options when click on up arrow button in footer-->
				<button type="button" class="rmp-theme-settings" id="rmp-theme-action" >
					<span class="dashicons dashicons-arrow-up "></span>
				</button>

				<div class="rmp-footer-sub-menu-wrapper" id="rmp-footer-theme-options">
					<ul class="rmp-footer-sub-menu">
						<li> 
							<a id="rmp-theme-save-button" class="rmp-theme-save-button"> 
								<span class="fas fa-save"></span>	
								<span> <?php esc_html_e( 'Save as theme', 'responsive-menu-pro' ); ?></span>
							</a>
						</li>

						<li> 
							<a  id="rmp-theme-change-button" class="rmp-theme-change-button" > 
								<span class="fas fa-folder-open "></span>
								<span><?php esc_html_e( 'Change theme', 'responsive-menu-pro' ); ?></span>
							</a>
						</li>
					</ul>
				</div>

				<!-- Device options in footer-->
				<div class="rmp-preview-device-wrapper">

					<button type="button" id="rmp-preview-mobile" class=" rmp-device-preview rmp-preview-mobile active" aria-pressed="1" data-device="mobile">
						<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/mobile.svg' ); ?>
						<span class="screen-reader-text">
							<?php echo esc_html__('Enter mobile preview mode', 'responsive-menu-pro' ); ?>
						</span>
					</button>

					<button type="button" id="rmp-preview-tablet" class="rmp-preview-tablet rmp-device-preview" aria-pressed="" data-device="tablet">
						<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/tablet.svg' ); ?>
						<span class="screen-reader-text">
							<?php echo esc_html__('Enter tablet preview mode', 'responsive-menu-pro' ); ?>
						</span>
					</button>

					<button type="button" id="rmp-preview-desktop" class="rmp-preview-desktop rmp-device-preview" aria-pressed="" data-device="desktop">
						<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/desktop.svg' ); ?>
						<span class="screen-reader-text">
							<?php echo esc_html__('Enter desktop preview mode', 'responsive-menu-pro' ); ?>
						</span>
					</button>

				</div>

			</div>
		<?php

		$html = ob_get_clean();

		/**
		 * Filters the editor footer html.
		 *
		 * @param string|HTML $html 
		 */
		$html = apply_filters( 'rmp_editor_footer_html', $html );

		return $html;
	}

	/**
	 * Function to return the markups for sidebar drawers.
	 * 
	 * @return HTML|string
	 */
	public function sidebar_drawer() {
		return (
			'<button type="button" class="collapse-sidebar" aria-expanded="true" aria-label="Hide Controls">
				<span class="collapse-sidebar-arrow"></span>
			</button>'
		);
	}

}
