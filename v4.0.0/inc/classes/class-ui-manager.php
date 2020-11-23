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
		add_action('wp_ajax_rmp_open_mega_menu_item', array($this,'rmp_open_mega_menu_dialog') );
        add_action('wp_ajax_rmp_add_mega_menu_column', array($this,'rmp_add_mega_menu_column') );
        add_action('wp_ajax_rmp_add_mega_menu_row', array($this,'rmp_add_mega_menu_row') );

        $this->control_manager = Control_Manager::get_instance();
	}


	public function rmp_add_mega_menu_row() {

	check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

	$item_id = sanitize_text_field( $_POST['item_id'] );
	if ( empty( $item_id ) ) {
		wp_send_json_error( 
			[ 'message' => __( 'Menu Item ID missing', 'responsive-menu-pro' ) ]
		);
	}

	$column_sizes = sanitize_text_field( $_POST['column_sizes'] );
	if ( empty( $column_sizes ) ) {
		wp_send_json_error( 
			[ 'message' => __( 'Column Sizes Missing !', 'responsive-menu-pro' ) ]
		);
	}

	wp_send_json_success( [
		'html' => $this->prepare_mega_menu_row ( [
			'column_sizes' => $column_sizes,
			'item_id' => $item_id ],
			true
		)
	] );

	}

	public function rmp_add_mega_menu_column() {

	check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

	$column_size = sanitize_text_field( $_POST['column_size'] );
	if ( empty( $column_size ) ) {
		wp_send_json_error( 
			[ 'message' => __( 'Column Size Missing !', 'responsive-menu-pro' ) ]
		);
	}

	$status =  array(
		'error'   => false,
		'message' => __('Success', 'responsive-menu-pro'),
		'html' => $this->prepare_mega_menu_column( [ 'column_size' => $column_size ] ),
	);

	header('Content-type: application/json');
	echo json_encode($status);	
	exit();	

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

		$title_contents = '';
		if ( ! empty( $accordion_attr['item_header']['item_title'] ) ) {
			$title_contents .= sprintf('<span class="%s"> %s %s </span>',
				esc_attr( $title_span_class ),
				esc_html( $accordion_attr['item_header']['item_title'] ),
				$tool_tip
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


	public function start_popup_controls_panel( $param ) {

	if ( empty( $param ) ) {
		return;
	}

	$group_classes = '';

	if ( ! empty( $param['group_classes'] ) ) {
		$group_classes = $param['group_classes'];
	}

	$html  = sprintf('<div class="rmp-input-control-wrapper %s">', $group_classes );

	if ( ! empty( $param['label'] ) ) {
		$html .= sprintf('<label class="rmp-input-control-label"> %s </label>', esc_html( $param['label'] ) );
	}

	if ( ! empty( $param['tool_tip'] ) ) {
		$html .= $this->control_manager->add_tool_tip( $param['tool_tip'] );
	}

	$html .= sprintf('<div class="rmp-input-control">');

	$html .= sprintf( '<span class="popup-controls-toggle %s" aria-hidden="true"></span>', $param['toggle_class'] );

	$class = '';
	if ( ! empty( $param['panel_class'] ) ) {
		$class = $param['panel_class'];
	}
	$html .= sprintf(
		'<div class="rmp-popup-controls %s">',
		esc_attr( $class )
	);

		// If param has echo as true then echo the markups otherwise return.
		if  ( ! empty( $param['echo'] ) ) {
		echo $html;
	} else {
		return $html;
	}
	}

	public function end_popup_controls_panel( ) {
	$html = sprintf('</div></div></div>');

	return $html;
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

	public function rmp_open_mega_menu_dialog() {

	check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

	$menu_id = sanitize_text_field( $_POST['menu_id'] );
	if ( empty( $menu_id ) ) {
		wp_send_json_error( array(
			'error'   => true,
			'message' => __('Menu ID is missing !', 'responsive-menu-pro'),
		) );
	}

	$menu_item_id = sanitize_text_field( $_POST['menu_item_id'] );
	if ( empty( $menu_item_id ) ) {
		wp_send_json_error( array(
			'error'   => true,
			'message' => __('Menu item ID is missing !', 'responsive-menu-pro'),
		) );
	}

	$status =  array(
		'error'   => false,
		'message' => __('Success', 'responsive-menu-pro'),
		'html' => $this->prepare_mega_menu_dialog( $menu_id, $menu_item_id ),
	);
	header('Content-type: application/json');
	echo json_encode($status);	
	exit();	


	}

	public function get_mega_menu_row_structure() {
	ob_start();
	?>
		<div class="rmp-mega-menu-structure-wrap">
			<button class="rmp-mega-menu-add-row" title="ADD Row">
				<span class="fas fa-plus-circle "></span>
				<span> <?php echo __('Row', 'responsive-menu-pro'); ?> </span>
			</button>
			<div class="rmp-mega-menu-row-structure">
				<div class="rmp-mega-menu-row-structure-header"> <?php esc_html_e( 'Select Structure', 'responsive-menu-pro' )?> </div>
				<button class="rmp-icon-button rmp-mega-menu-row-structure-cancel">
					<span class="dashicons dashicons-no-alt "></span>
				</button>
				<ul class="rmp-mega-menu-row-structure-list">
					<li class="col-structure" col-size="12">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M100,0V50H0V0Z"></path>
						</svg>
					</li>
					<li class="col-structure" col-size="6 6">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M49,0V50H0V0Z M100,0V50H51V0Z"></path>
						</svg>
					</li>
					<li class="col-structure" col-size="4 4 4">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M32,0V50H0V0Z M66,0V50H34V0Z M100,0V50H68V0Z"></path>
						</svg>
					</li>
					<li class="col-structure" col-size="3 3 3 3">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M23.5,0V50H0V0Z M49,0V50H25.5V0Z M74.5,0V50H51V0Z M100,0V50H76.5V0Z"></path>
						</svg>
					</li>
					<li class="col-structure" col-size="4 8">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M32.6667,0V50H0V0Z M100,0V50H34.6667V0Z"></path>
						</svg>
					</li>

					<li class="col-structure" col-size="8 4">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M65.3333,0V50H0V0Z M100,0V50H67.3333V0Z"></path>
						</svg>
					</li>

					<li class="col-structure" col-size="3 3 6">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M24,0V50H0V0Z M50,0V50H26V0Z M100,0V50H52V0Z"></path>
						</svg>
					</li>

					<li class="col-structure" col-size="6 3 3">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M48,0V50H0V0Z M74,0V50H50V0Z M100,0V50H76V0Z"></path>
						</svg>
					</li>

					<li class="col-structure" col-size="3 6 3">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M24,0V50H0V0Z M74,0V50H26V0Z M100,0V50H76V0Z"></path>
						</svg>
					</li>

					<li class="col-structure" col-size="2 2 2 2 2 2">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 50">
							<path d="M15,0V50H0V0Z M32,0V50H17V0Z M49,0V50H34V0Z M66,0V50H51V0Z M83,0V50H68V0Z M100,0V50H85V0Z"></path>
						</svg>
					</li>
				</ul>
			</div>
		</div>
	<?php

	return ob_get_clean();
	}

	public function prepare_mega_menu_column( $param = [] ) {

	$column_size = 4;

	if ( ! empty( $param['column_size'] ) ) {   
		$column_size = $param['column_size'];
	}

	$item_id = ! empty( $param['item_id'] ) ? $param['item_id'] : 0;

	$col_edit  = sprintf('<a class="rmp-icon-button rmp-action-col-add-widget"><span class="dashicons dashicons-plus "></span></a>');
	$col_edit .= sprintf('<a class="rmp-icon-button rmp-action-col-edit popup-controls-toggle"><span class="dashicons dashicons-edit "></span></a>');
	$col_edit .= sprintf('<div class="rmp-popup-controls ">');
	$column_meta = [];
	if ( ! empty( $param['col_meta'] ) ) {
		$column_meta = $param['col_meta'];
	}

	$col_edit .= $this->control_manager->add_group_text_control( [
		'label'  => __('Column Padding','responsive-menu-pro'),
		'type'   =>   'text',
		'class'  =>  'rmp-mega-menu-column-padding',
		'name'   => sprintf('menu[deskktop][%s][column_padding]', $item_id ),
		'input_options' => [  'top', 'right', 'bottom', 'left' ],
		'value_options' => ! empty( $column_meta['column_padding'] ) ? $column_meta['column_padding'] : '',
		'echo'   => false,
	] );

	$col_edit .= $this->start_group_controls();
	$col_edit .=  $this->control_manager->add_color_control( [
		'label'  => __('Background Color','responsive-menu-pro'),
		'id'     => 'rmp-mega-menu-column-background',
		'name'   => sprintf('menu[deskktop][%s][column_background]', $item_id ),
		'value'  => ! empty( $column_meta['column_background']['color'] ) ? $column_meta['column_background']['color'] : '#000',
		'echo'   => false,
	] );

	$col_edit .=  $this->control_manager->add_color_control( [
		'label'  => __('Background Hover','responsive-menu-pro'),
		'id'     => 'rmp-mega-menu-column-background-hover',
		'name'   => sprintf('menu[deskktop][%s][column_background_hover]', $item_id ),
		'value'  => ! empty( $column_meta['column_background']['hover'] ) ? $column_meta['column_background']['hover'] : '#000',
		'echo'   => false,
	] );
	$col_edit .= $this->end_group_controls();

	$col_edit .= '</div>';

	$col_delete   = sprintf('<a class="rmp-icon-button rmp-action-col-delete"><span class="dashicons dashicons-no-alt "></span></a>');
	$col_actions  = sprintf('<div class="rmp-mega-menu-col-actions">%s %s</div>', $col_edit, $col_delete );


	$col_collapse = sprintf('<a class="rmp-icon-button rmp-mega-menu-col-collapse" title="Collapse">
							<span class="dashicons dashicons-arrow-left-alt2"></span>
					</a>');

	$col_size = sprintf('<span class="rmp-mega-menu-col-size"> %s/12 </span>', esc_html( $column_size) );

	$col_expand = sprintf('<a class="rmp-icon-button rmp-mega-menu-col-expand" title="Expand">
							<span class="dashicons dashicons-arrow-right-alt2"></span>
						</a>');

	$col_settings = sprintf(
		'<div class="rmp-mega-menu-col-settings">%s %s %s</div>',
		$col_collapse,
		$col_size,
		$col_expand
	);

	$column_header  = sprintf('<div class="rmp-mega-menu-col-header">%s %s</div>', $col_settings, $col_actions );


	$child_items = [];
	if ( ! empty( $param['child_items'] ) ) {
		$child_items = $param['child_items'];
	}

	$widgets = '';
	foreach( $child_items as $item ) {
		$widgets .= $this->prepare_widget( $item );
	}

	$column_widgets = sprintf('<div class="rmp-mega-menu-widgets">%s</div>', $widgets );

	$column = sprintf(
		'<div class="rmp-mega-menu-col" data-col-size="%s" data-total-items="%s">',
		esc_html( $column_size),
		count($child_items)
	);

	$column .= sprintf('<div class="rmp-mega-menu-col-wrap"> %s %s </div>', $column_header, $column_widgets);
	$column .= '</div>';
						
	return $column;

	}

	public function prepare_widget( $item ) {

	$action = '';
	$inner_section = '';
	$item_type = 'menu_item';
	if ( ! empty( $item['item_type'] ) && 'widget' === $item['item_type'] ) {

		$item_type = $item['item_type'];

		$action = sprintf(
			'<div class="widget-title-action">
				<a class="widget-option widget-action rmp-widget-edit">
					%s
				</a>
			</div>',
			file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/general.svg' )
		);

		$inner_section = '<div class="widget-inner widget-inside"></div>';
	}

	$html = sprintf(
		'<div class="widget" widget-title="%1$s" widget-id="%2$s" widget-type="%3$s" id="%2$s">
			<div class="widget-top">
			%4$s
				<div class="widget-title">%1$s</div>
			</div>
			%5$s
		</div>',
		esc_html( $item['item_title'] ),
		esc_attr( $item['item_id'] ),
		esc_attr( $item_type ),
		$action,
		$inner_section
	);

	return $html;

	}

	public function prepare_mega_menu_row( $param , $is_ajax = false ) {

	// Will add the row edit features soon.
	//$row_edit      = sprintf('<a class="rmp-icon-button rmp-action-row-edit"><span class="dashicons dashicons-edit "></span></a>');
	$row_delete    = sprintf('<a class="rmp-icon-button rmp-action-row-delete"><span class="dashicons dashicons-no-alt "></span></a>');
	$row_move_icon = sprintf('<span class="ui-accordion-header-icon ui-icon dashicons dashicons-arrow-right" aria-hidden="true"></span>');

	$row_actions    = sprintf('<div class="mega-row-actions">%s %s</div>', $row_move_icon, $row_delete );
	$add_col_button = sprintf(
		'<button class="rmp-mega-menu-add-column" title="Add Column">
			<span class="fas fa-plus-circle "></span>
			<span>%s</span>
		</button>',
		__('Column', 'responsive-menu-pro')
	);

	$row_header   = sprintf('<div class="rmp-mega-menu-row-header">%s %s</div>', $row_actions, $add_col_button );
	$row_contents = '';

	$column_sizes = '4';
	$used_size    = 0;
	$item_id      = ! empty( $param['item_id'] ) ? $param['item_id'] : 0;

	if ( $is_ajax ) {

		if ( ! empty( $param['column_sizes'] ) ) {
			$column_sizes = $param['column_sizes'];
		}

		$column_sizes = explode(' ', $column_sizes );

		foreach( $column_sizes as $size ) {
			$used_size += $size;
			$row_contents .= $this->prepare_mega_menu_column([
				'column_size' => $size,
				'item_id'     => $item_id,
			] );
		}
	} elseif( ! empty( $param['row']['columns'] )  ) {
		foreach ( $param['row']['columns'] as $column ) {
			$used_size += $column['meta']['column_size'];
			$row_contents .= $this->prepare_mega_menu_column( [
				'column_size' => $column['meta']['column_size'],
				'item_id'     => $item_id,
				'col_meta'    => $column['meta'],
				'child_items' => $column['menu_items']
			] );
		}
	} else {
		$child_items = ! empty( $param['child_items'] ) ? $param['child_items'] : 0;
		$used_size   = $column_sizes;

		$row_contents .= $this->prepare_mega_menu_column( [
			'column_size' => $column_sizes,
			'item_id'     => $item_id,
			'child_items' => $child_items
		] );
	}

	$row = sprintf(
		'<div class="rmp-mega-menu-row" data-row-size="12" data-row-used-cols="%s" data-row-available-cols="%s">%s %s</div>',
		$used_size,
		( 12 - $used_size ),
		$row_header,
		$row_contents
	);

	return $row;
	}

	public function prepare_mega_menu_dialog( $menu_id, $item_id ) {
		ob_start();
		$options = get_post_meta( $menu_id, 'rmp_menu_meta' );

		if ( ! empty( $options ) ) {
			$options = $options[0];
		}

		$menu_to_use = $options['menu_to_use'];
		$menu_items   = wp_get_nav_menu_items($menu_to_use);
		$child_items = array();
		$item_name = '';
		foreach( $menu_items as $item ) {

			if( $item->ID == $item_id ) {
				$item_name = $item->title;
			} else if ( $item->menu_item_parent == $item_id ) {
				$child_items[] = ['item_id' => $item->ID, 'item_title' => $item->title, 'item_type' => 'menu item'];
			}
		}
	
		$options_manager = Option_Manager::get_instance();
		$rmp_mega_item = $options_manager->mega_menu_options( $menu_id, $item_id );
		$panel_meta = [];
		$rows = [];
		if ( ! empty( $rmp_mega_item ) ) {
			$panel_meta = $rmp_mega_item['meta'];
			$rows = $rmp_mega_item['rows'];
		}

	?>
		<section class="rmp-dialog-overlay rmp-mega-menu-dialog" data-item-id="<?php echo $item_id; ?>">
			<div class="rmp-dialog-backdrop"></div>
			<div class="rmp-dialog-wrap rmp-menu-controls wp-clearfix">
			<div class="rmp-dialog-header">
				<strong class="title">
					<span class="popup-controls-toggle rmp-panel-settings">
						<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/general.svg' ); ?>
					</span>

					<div class="rmp-popup-controls ">
						<?php 

							echo $this->start_group_controls();
								echo $this->control_manager->add_text_input_control( [
									'label'  => __('Panel Width','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-desktop-mega-menu-panel-width',
									'name'   => 'menu[desktop_mega_menu_panel_width]',
									'value'    => ! empty( $panel_meta['panel_width'] ) ? $panel_meta['panel_width'] : 100,
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-desktop-mega-menu-panel-width-unit',
										'name' => 'menu[desktop_mega_menu_panel_width_unit]',
										'classes' => 'is-unit',
										'value' => ! empty( $panel_meta['panel_width_unit'] ) ? $panel_meta['panel_width_unit'] : '%',
									],
								] );

								echo  $this->control_manager->add_color_control( [
									'label'  => __('Background Color','responsive-menu-pro'),
									'id'     => 'rmp-mega-menu-panel-background',
									'name'    => 'menu[menu_link_colour]',
									'value'    => ! empty( $panel_meta['panel_background']['color'] ) ? $panel_meta['panel_background']['color'] : '#000',
								] );
							echo $this->end_group_controls();

							echo  $this->control_manager->add_image_control( [
								'label'  => __('Background Image','responsive-menu-pro'),
								'group_classes' => 'full-size',
								'id'     => 'rmp-mega-menu-panel-background-image',
								'picker_class'  => 'rmp-menu-inactive-arrow-image-selector',
								'picker_id' => "rmp-mega-menu-panel-image-selector",
								'name'    => 'menu[panel_background_image]',
								'value'    => ! empty( $panel_meta['panel_background']['image'] ) ? $panel_meta['panel_background']['image'] : '',
							] );

							echo  $this->control_manager->add_group_text_control( [
								'label'  => __('Panel Padding','responsive-menu-pro'),
								'type'   =>   'text',
								'class'  =>  'rmp-mega-menu-panel-padding',
								'name'    => 'menu[menu_link_colour]',
								'input_options' => [  'top', 'right', 'bottom', 'left' ],
								'value_options' => ! empty( $panel_meta['panel_padding'] ) ? $panel_meta['panel_padding'] : ''
							] );

						?>
					</div>
					<?php echo $item_name; ?>
					<a href="#" id="rmp-mega-menu-sync"> <?php echo __('Sync live preview..', 'responsive-menu-pro');?></a>
				</strong>
				<span class="close dashicons dashicons-no"></span>
			</div>
			<div class="rmp-dialog-contents wp-clearfix">
					<div class="rmp-mega-menu-contents">
						<div id="rmp-mega-menu-grid" class="rmp-mega-menu-grid">
							<?php

							if ( ! empty( $rows ) ) {
								foreach( $rows as $row ) {
									echo $this->prepare_mega_menu_row( ['item_id' => $item_id, 'row' => $row] );
								}
							} else {
								echo $this->prepare_mega_menu_row( ['item_id' => $item_id, 'child_items' => $child_items] );
							}
							?>
						</div>
						<?php echo $this->get_mega_menu_row_structure();?>
					</div>
					<?php
						echo $this->control_manager->get_wp_core_widget_list();
					?>
				</div>
			</div>
		</section>

	<?php

	return ob_get_clean();
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
