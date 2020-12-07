<?php
/**
 * This file contain thh UI_Manager class and it's functionalities.
 * 
 * @version 4.0.0
 * 
 * @author  Expresstech System
 * 
 * @package responsive-menu-pro
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
		return '<div class="rmp-h-separator clearfix"></div>';
	}


	public function start_accordion_item( $accordion_attr ) {

		if ( empty( $accordion_attr ) ) {
			return;
		}

		$item_class = '';
		if ( ! empty( $accordion_attr['item_class'] ) ) {
			$item_class = $accordion_attr['item_class'];
		}

		$html = sprintf('<li class="rmp-accordion-item %s">', esc_attr( $item_class ) );

		//According header.
		if ( ! empty( $accordion_attr['item_header'] ) ) {

			$title_class = '';
			if ( ! empty( $accordion_attr['item_header']['title_class'] ) ) {
				$title_class = $accordion_attr['item_header']['title_class'];
			}

			$title_span_class = '';
			if ( ! empty( $accordion_attr['item_header']['title_span_class'] ) ) {
				$title_span_class = $accordion_attr['item_header']['title_span_class'];
			}

			//Check tooltip text is added or not.
			$tool_tip = '';
			if ( ! empty( $accordion_attr['tool_tip'] ) ) {
				$tool_tip = $this->control_manager->get_tool_tip( $accordion_attr['tool_tip'] );
			}

			$feature_label = '';
			// Check feature type.
			if( ! empty( $accordion_attr['feature_type'] ) ) {
				$feature_label = sprintf(
					'<a target="_blank" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > %s </a>',
					$accordion_attr['feature_type']
				);
			}

			$title_contents = '';
			if ( ! empty( $accordion_attr['item_header']['item_title'] ) ) {
				$title_contents .= sprintf('<span class="%s"> %s %s %s</span>',
					esc_attr( $title_span_class ),
					esc_html( $accordion_attr['item_header']['item_title'] ),
					$tool_tip,
					$feature_label
				);
			}

			$switcher = '';
			if ( ! empty( $accordion_attr['item_header']['item_control']['switcher'] ) ) {

				$switcher .= '<span class="item-controls">';
				$switcher .= sprintf( '<input type="hidden" value="off" name="%s"/>', esc_attr( $accordion_attr['item_header']['item_control']['name'] ) );
				$switcher .= sprintf( '<input type="checkbox" id="%s" name="%s" class="toggle %s" value="on" %s>',
					esc_attr( $accordion_attr['item_header']['item_control']['id'] ),
					esc_attr( $accordion_attr['item_header']['item_control']['name'] ),
					esc_attr( $accordion_attr['item_header']['item_control']['class'] ),
					esc_attr( $accordion_attr['item_header']['item_control']['is_checked'] )
				);
				$switcher .= '</span>';
			}

			$title_contents .= $switcher;

			$html .= sprintf('<div class="rmp-accordion-title %s">%s</div>',
				esc_attr( $title_class ),
				$title_contents
			);
		}

		// If self_close_item is true then avoid contents for this accordion item and close it.
		if ( ! empty( $accordion_attr['self_close_item'] ) ) {
			$html .= '</li>';
			return $html;
		}

		//Accordion contents start.
		$content_class = '';
		if ( ! empty( $accordion_attr['item_content']['content_class'] ) ) {
			$content_class = $accordion_attr['item_content']['content_class'];
		}

		$html .= sprintf('<div class="rmp-accordion-content rmp-menu-controls %s">', esc_attr( $content_class ) );

		return $html;

	}

	public function end_accordion_item() {
		//Accordion contents end.
		return  '</div></li>';
	}


	public function add_editor_menu_item( $tab_attr ) {

		if ( empty( $tab_attr ) ) {
			return;
		}

		$item_class = '';
		if ( ! empty( $tab_attr['item_class'] ) ) {
			$item_class = $tab_attr['item_class'];
		}

		$aria_owns = '';
		if ( ! empty( $tab_attr['aria_owns'] ) ) {
			$aria_owns = $tab_attr['aria_owns'];
		}

		$html = sprintf('<li class="rmp-tab-item %s" aria-owns="%s">', esc_attr( $item_class ), esc_attr( $aria_owns ) );

	
		//Item header.
		if ( ! empty( $tab_attr['item_header'] ) ) {

			$title_class = '';
			if ( ! empty( $tab_attr['item_header']['title_class'] ) ) {
				$title_class = $tab_attr['item_header']['title_class'];
			}

			$title_contents = '';

			if ( ! empty( $tab_attr['item_header']['item_title'] ) ) {
				$title_contents .= sprintf('<span class="%s"> %s </span>',
					esc_attr( $title_class ),
					esc_html( $tab_attr['item_header']['item_title'] )
				);
			}

			//Item icon.
			if ( ! empty( $tab_attr['item_header']['item_svg_icon']  ) ) {
				$html .= sprintf(
					'<span class="rmp-tab-item-icon">%s</span>',
					file_get_contents( $tab_attr['item_header']['item_svg_icon'] )
				);
			}

			$html .= sprintf('<h3 class="rmp-tab-item-title %s">%s</h3>',
				esc_attr( $title_class ),
				$title_contents
			);
		}

		$html .= '</li>';

		return $html;

	}

	public function start_tabs_controls_panel( $param ) {

		$items_count = 2;
		if( ! empty( $param['tab_items'] ) ) {
			$items_count = count( $param['tab_items'] );
		}

		$html = sprintf( '<div class="tabs %s">
			<ul class="nav-tab-wrapper rmp-tab-items rmp-tab-items-%s" >',
			esc_attr( $param['tab_classes'] ),
			esc_attr( $items_count )
		);

		foreach( $param['tab_items'] as $tab_item ) {
			$html .= sprintf(
				'<li><a class="nav-tab %s" href="#%s">%s</a></li>',
				esc_attr( $tab_item['item_class'] ),
				esc_attr( $tab_item['item_target'] ),
				esc_html( $tab_item['item_text'] )
			);
		}

		$html .= '</ul>';

		return $html;
	}

	public function end_tabs_controls_panel() {
		return '</div>';
	}


	public function start_tab_item( $param ) {
		$html = sprintf('<div id="%s" class="%s">',esc_attr( $param['item_id'] ), esc_attr( $param['item_class'] ) );

		return $html;
	}

	public function end_tab_item() {
		return '</div>';
	}

	public function start_group_controls() {
		return '<div class="rmp-input-control-group">';
	}

	public function end_group_controls() {
		return '</div>';
	}



	public function start_sub_accordion() {
		return '<ul class="rmp-sub-accordion-container">';
	}

	public function end_sub_accordion() {
		return '</ul>';
	}


}
