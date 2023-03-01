<?php
/**
 * This is core class file for responsive menu pro to design the menu
 * with custom walker with saved settings.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

namespace RMP\Features\Inc;

/** Disable the direct access to this class */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Walker prepare the menu as per loction and menu id.
 *
 * @since    4.0.0
 * @package    responsive_menu_pro
 *
 * @author     Expresstech System
 */
class Walker extends \Walker_Nav_Menu {


	/**
	 * Hold the current menu item.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string $current_item
	 */
	private $current_item;

	/**
	 * Hold the top most item id
	 *
	 * @since   4.0.0
	 * @access  protected
	 * @var     object
	 */
	protected $root_item_id;

	/**
	 * This is Walker class constructor function.
	 *
	 * @access public
	 * @param array $option
	 */
	public function __construct( $options ) {
		$this->options = $options;
	}

	/**
	 * Function to create element for menu items.
	 *
	 * @access public
	 * @version 4.0.0
	 *
	 * @param string/HTML $output
	 * @param object      $item
	 * @param int         $depth
	 * @param array       $args
	 * @param int         $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->set_current_item( $item );

		$classes = array();
		if ( ! empty( $item->classes ) ) {
			$classes = (array) $item->classes;
		}

		$rmp_menu_classes = $classes;

		/** Add rmp menu classes as per item */
		foreach ( $classes as $class ) {
			switch ( $class ) {
				case 'menu-item':
					$rmp_menu_classes[] = 'rmp-menu-item';
					break;
				case 'current-menu-item':
					$rmp_menu_classes[] = 'rmp-menu-current-item';
					break;
				case 'menu-item-has-children':
					$rmp_menu_classes[] = 'rmp-menu-item-has-children';
					break;
				case 'current-menu-parent':
					$rmp_menu_classes[] = 'rmp-menu-item-current-parent';
					break;
				case 'current-menu-ancestor':
					$rmp_menu_classes[] = 'rmp-menu-item-current-ancestor';
					break;
			}
		}

		// Add top/sub level class as per item.
		if ( 0 === intval( $item->menu_item_parent ) ) {
			$rmp_menu_classes[] = 'rmp-menu-top-level-item';
			$this->root_item_id = $item->ID;
		} else {
			$rmp_menu_classes[] = 'rmp-menu-sub-level-item';
		}

		/* Clear child class if we are at the final depth level */
		if ( isset( $rmp_menu_classes ) ) {
			$has_child = array_search( 'rmp-menu-item-has-children', $rmp_menu_classes, true );
			if ( ( intval( $depth ) + 1 ) === intval( $this->options['menu_depth'] ) && false !== $has_child ) {
				unset( $rmp_menu_classes[ $has_child ] );
			}
		}

		$class_names = join( ' ', array_unique( $rmp_menu_classes ) );

		/** Prepare classes for menu item. */
		if ( ! empty( $class_names ) ) {
			$class_names = sprintf( 'class="%s"', esc_attr( $class_names ) );
		} else {
			$class_names = '';
		}

		$class_names = apply_filters( 'rmp_nav_item_class', $class_names, $item );

		// Start menu item and set classes & ID.
		$output .= sprintf(
			'<li id="rmp-menu-item-%s" %s role="none">',
			esc_attr( $item->ID ),
			$class_names
		);

		// Set attributes on menu item link.
		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = 'rmp-menu-item-link';
		$atts['role']   = 'menuitem';
		$atts           = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $key => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $key ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= sprintf( ' %s="%s" ', $key, $value );
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'rmp_menu_item_title', $title, $item, $args, $depth );

		// Activate the required menu item by default.
		$sub_menu_arrow = '';
		if ( in_array( 'rmp-menu-item-has-children', $rmp_menu_classes, true ) ) {
			$inactive_arrow = sprintf(
				'<div class="rmp-menu-subarrow">%s</div>',
				$this->get_inactive_arrow()
			);

			$active_arrow = sprintf(
				'<div class="rmp-menu-subarrow rmp-menu-subarrow-active">%s</div>',
				$this->get_active_arrow()
			);

			if ( 'on' === $this->options['auto_expand_all_submenus'] ) {
				$sub_menu_arrow = $active_arrow;
			} elseif (
				'on' === $this->options['auto_expand_current_submenus'] &&
				( in_array( 'rmp-menu-item-current-parent', $rmp_menu_classes, true ) ||
				in_array( 'rmp-menu-item-current-ancestor', $rmp_menu_classes, true ) ) ) {
				$sub_menu_arrow = $active_arrow;
			} else {
				$sub_menu_arrow = $inactive_arrow;
			}
		}

		/* Clear Arrow if we are at the final depth level */
		if ( intval( $depth ) + 1 === intval( $this->options['menu_depth'] ) ) {
			$sub_menu_arrow = '';
		}

		$item_output  = '';
		$item_output .= sprintf( '<a %s >', $attributes );
		$item_output .= $title;
		$item_output .= $sub_menu_arrow;
		$item_output .= '</a>';

		// If description is enable then add it below of menu item.
		if ( ! empty( $item->description ) && 'on' === $this->options['submenu_descriptions_on'] ) {
			$item_output .= sprintf( '<p class="rmp-menu-item-description"> %s </p>', esc_html( $item->description ) );
		}

		// Theme support for twenty twenty one.
		if ( function_exists( 'twenty_twenty_one_add_sub_menu_toggle' ) ) {
			remove_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10 );
			remove_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_nav_menu_social_icons', 10 );
		}

		/* End Add Desktop Menu Widgets to Sub Items */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Function to build the sub-menu items.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string|HTML $output
	 * @param int         $depth
	 * @param array       $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		// Add sub-menu item wrap.
		$output .= sprintf(
			'<ul aria-label="%s"
            role="menu" data-depth="%s"
            class="rmp-submenu rmp-submenu-depth-%s">',
			esc_attr( $this->current_item->title ),
			( $depth + 2 ),
			( $depth + 1 ) . $this->get_submenu_class_open_or_not()
		);
	}

	/**
	 * Function to close the menu item.
	 *
	 * @access public
	 * @version 4.0.0
	 *
	 * @param string/HTML $output
	 * @param object      $item
	 * @param int         $depth
	 * @param array       $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= '</li>';
	}

	/**
	 * Function to close the sub-menu items.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string|HTML $output
	 * @param int         $depth
	 * @param array       $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '</ul>';
	}

	/**
	 * Function get the active item toggle icon.
	 *
	 * @return HTML
	 */
	public function get_active_arrow() {
		if ( ! empty( $this->options['active_arrow_font_icon'] ) ) {
			return $this->options['active_arrow_font_icon'];
		} elseif ( ! empty( $this->options['active_arrow_image'] ) ) {
			return sprintf(
				'<img alt="%s" src="%s" />',
				rmp_image_alt_by_url( $this->options['active_arrow_image'] ),
				esc_url( $this->options['active_arrow_image'] )
			);
		} else {
			return $this->options['active_arrow_shape'];
		}
	}

	/**
	 * Function get the inactive item toggle icon.
	 *
	 * @return HTML
	 */
	public function get_inactive_arrow() {
		if ( ! empty( $this->options['inactive_arrow_font_icon'] ) ) {
			return $this->options['inactive_arrow_font_icon'];
		} elseif ( ! empty( $this->options['inactive_arrow_image'] ) ) {
			return sprintf(
				'<img alt="%s" src="%s" />',
				rmp_image_alt_by_url( $this->options['inactive_arrow_image'] ),
				esc_url( $this->options['inactive_arrow_image'] )
			);
		} else {
			return $this->options['inactive_arrow_shape'];
		}
	}

	/**
	 * Function to set the current item object.
	 *
	 * @param object $item Menu item object.
	 */
	public function set_current_item( $item ) {
		$this->current_item = $item;
	}

	/**
	 * Function to return the current item object.
	 *
	 * @return object $item Menu item object.
	 */
	public function get_current_item() {
		return $this->current_item;
	}

	/**
	 * Check submenu need to open or not.
	 *
	 * @return boolean
	 */
	public function get_submenu_class_open_or_not() {
		return $this->expand_all_submenu_options_is_on() || $this->expand_current_submenu_on_and_item_is_parent() ? ' rmp-submenu-open' : '';
	}

	/**
	 * Check all submenu need to open or not.
	 *
	 * @return boolean
	 */
	public function expand_all_submenu_options_is_on() {
		return 'on' === $this->options['auto_expand_all_submenus'];
	}

	/**
	 * Check current submenu need to open or not.
	 *
	 * @return boolean
	 */
	public function expand_current_submenu_on_and_item_is_parent() {
		return ( 'on' === $this->options['auto_expand_current_submenus'] )
			&& ( $this->get_current_item()->current_item_ancestor || $this->get_current_item()->current_item_parent );
	}
}
