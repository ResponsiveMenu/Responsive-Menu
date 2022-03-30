<?php
/**
 * This file contain thh UI_Manager class and it's functionalities.
 *
 * @version 4.0.0
 *
 * @author  Expresstech System
 *
 * @package responsive-menu
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Control_Manager;
use RMP\Features\Inc\Traits\Singleton;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UI_Manager
 * This class is responsible for provide the UI.
 *
 * @version 4.0.0
 */
class UI_Manager {

	use Singleton;

	/**
	 * Instance of this control manager class.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      object.
	 */

	protected $control_manager;

	protected $pro_plugin_url = 'https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile';

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
		$this->control_manager = Control_Manager::get_instance();
	}

	public function accordion_divider() {
		?>
		<div class="rmp-h-separator clearfix"></div>
		<?php
	}


	public function start_accordion_item( $accordion_attr ) {
		if ( empty( $accordion_attr ) ) {
			return;
		}

		$item_class = '';
		if ( ! empty( $accordion_attr['item_class'] ) ) {
			$item_class = $accordion_attr['item_class'];
		}
		?>
		<li class="rmp-accordion-item <?php echo esc_attr( $item_class ); ?>">
		<?php

		// According header.
		if ( ! empty( $accordion_attr['item_header'] ) ) {
			$title_class = '';
			if ( ! empty( $accordion_attr['item_header']['title_class'] ) ) {
				$title_class = $accordion_attr['item_header']['title_class'];
			}
			?>
			<div class="rmp-accordion-title <?php echo esc_attr( $title_class ); ?>">
			<?php

			$title_span_class = 'accordion-item-title ';
			if ( ! empty( $accordion_attr['item_header']['title_span_class'] ) ) {
				$title_span_class = $accordion_attr['item_header']['title_span_class'];
			}

			$title_contents = '';
			if ( ! empty( $accordion_attr['item_header']['item_title'] ) ) {
				?>
				<span class="<?php echo esc_attr( $title_span_class ); ?>">
				<?php
					echo esc_html( $accordion_attr['item_header']['item_title'] );
				// Check tooltip text is added or not.
				if ( ! empty( $accordion_attr['tool_tip'] ) ) {
					$this->control_manager->get_tool_tip( $accordion_attr['tool_tip'] );
				}

					// Check feature type.
				if ( ! empty( $accordion_attr['feature_type'] ) ) {
					?>
						<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" >
						<?php echo esc_html( $accordion_attr['feature_type'] ); ?>
						</a>
						<?php
				}
				?>
				</span>
				<?php
			}

			if ( ! empty( $accordion_attr['item_header']['item_control']['switcher'] ) ) {
				?>
				<span class="item-controls">
					<input type="hidden" value="off" name="<?php echo esc_attr( $accordion_attr['item_header']['item_control']['name'] ); ?>"/>
					<input type="checkbox" id="<?php echo esc_attr( $accordion_attr['item_header']['item_control']['id'] ); ?>" name="<?php echo esc_attr( $accordion_attr['item_header']['item_control']['name'] ); ?>" class="toggle <?php echo esc_attr( $accordion_attr['item_header']['item_control']['class'] ); ?>" value="on" <?php esc_attr( $accordion_attr['item_header']['item_control']['is_checked'] ); ?>>',
				</span>
				<?php
			}
			?>
			</div>
			<?php
		}

		// If self_close_item is true then avoid contents for this accordion item and close it.
		if ( ! empty( $accordion_attr['self_close_item'] ) ) {
			?>
			</li>
			<?php
			return;
		}

		// Accordion contents start.
		$content_class = '';
		if ( ! empty( $accordion_attr['item_content']['content_class'] ) ) {
			$content_class = $accordion_attr['item_content']['content_class'];
		}
		?>
		<div class="rmp-accordion-content rmp-menu-controls <?php echo esc_attr( $content_class ); ?>">
		<?php
	}

	public function end_accordion_item() {
		// Accordion contents end.
		?>
		</div></li>
		<?php
	}


	public function add_editor_menu_item( $tab_attr ) {
		if ( empty( $tab_attr ) ) {
			return;
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		$item_class = '';
		if ( ! empty( $tab_attr['item_class'] ) ) {
			$item_class = $tab_attr['item_class'];
		}

		$aria_owns = '';
		if ( ! empty( $tab_attr['aria_owns'] ) ) {
			$aria_owns = $tab_attr['aria_owns'];
		}
		?>
		<li class="rmp-tab-item <?php echo esc_attr( $item_class ); ?>" aria-owns="<?php echo esc_attr( $aria_owns ); ?>">
		<?php

		// Item header.
		if ( ! empty( $tab_attr['item_header'] ) ) {
			$title_class = '';
			if ( ! empty( $tab_attr['item_header']['title_class'] ) ) {
				$title_class = $tab_attr['item_header']['title_class'];
			}

			// Item icon.
			if ( ! empty( $tab_attr['item_header']['item_svg_icon'] ) ) {
				?>
				<span class="rmp-tab-item-icon">
					<?php
					$svg_icon = $wp_filesystem->get_contents( $tab_attr['item_header']['item_svg_icon'] );
					if ( $svg_icon ) {
						echo wp_kses( $svg_icon, rmp_allow_svg_html_tags() );
					}
					?>
				</span>
				<?php
			}
			?>
			<h3 class="rmp-tab-item-title <?php echo esc_attr( $title_class ); ?>">
			<?php
			if ( ! empty( $tab_attr['item_header']['item_title'] ) ) {
				?>
					<span class="<?php echo esc_attr( $title_class ); ?>">
				<?php echo esc_html( $tab_attr['item_header']['item_title'] ); ?>
					</span>
				<?php
			}
			?>
				</h3>
				<?php
		}
		?>
		</li>
		<?php
	}

	public function start_tabs_controls_panel( $param ) {
		$items_count = 2;
		if ( ! empty( $param['tab_items'] ) ) {
			$items_count = count( $param['tab_items'] );
		}
		?>
		<div class="tabs <?php echo esc_attr( $param['tab_classes'] ); ?>">
			<ul class="nav-tab-wrapper rmp-tab-items rmp-tab-items-<?php echo esc_attr( $items_count ); ?>" >
		<?php

		foreach ( $param['tab_items'] as $tab_item ) {
			?>
			<li>
				<a class="nav-tab <?php echo esc_attr( $tab_item['item_class'] ); ?>" href="#<?php echo esc_attr( $tab_item['item_target'] ); ?>">
					<?php echo esc_html( $tab_item['item_text'] ); ?>
				</a>
			</li>
			<?php
		}
		?>
		</ul>
		<?php
	}

	public function end_tabs_controls_panel() {
		?>
		</div>
		<?php
	}


	public function start_tab_item( $param ) {
		?>
		<div id="<?php echo esc_attr( $param['item_id'] ); ?>" class="<?php echo esc_attr( $param['item_class'] ); ?>">
		<?php
	}

	public function end_tab_item() {
		?>
		</div>
		<?php
	}

	public function start_group_controls() {
		?>
		<div class="rmp-input-control-group">
		<?php
	}

	public function end_group_controls() {
		?>
		</div>
		<?php
	}

	public function start_sub_accordion() {
		?>
		<ul class="rmp-sub-accordion-container">
		<?php
	}

	public function end_sub_accordion() {
		?>
		</ul>
		<?php
	}
}
