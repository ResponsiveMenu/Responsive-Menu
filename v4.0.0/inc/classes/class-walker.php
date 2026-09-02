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
	 * Drop the "has children" class from items whose children are not in the list.
	 *
	 * WordPress stamps `menu-item-has-children` on before `wp_nav_menu_objects` runs, so
	 * anything that removes items on that filter - the plugin's own logged-in/logged-out
	 * visibility settings among them - can leave a parent claiming children it no longer
	 * has. The walker would then emit a submenu toggle whose aria-controls points at a
	 * <ul> that is never rendered: a control that announces a collapsed submenu, resolves
	 * to nothing, and does nothing when activated.
	 *
	 * @since 4.8.0
	 * @access public
	 *
	 * @param array $elements  Menu item objects.
	 * @param int   $max_depth Maximum depth to walk.
	 * @param mixed ...$args   Arguments passed through to the parent walker.
	 * @return string
	 */
	public function walk( $elements, $max_depth, ...$args ) {
		$has_children = array();

		foreach ( (array) $elements as $element ) {
			if ( ! empty( $element->menu_item_parent ) ) {
				$has_children[ intval( $element->menu_item_parent ) ] = true;
			}
		}

		foreach ( (array) $elements as $element ) {
			if ( empty( $element->classes ) || ! empty( $has_children[ intval( $element->ID ) ] ) ) {
				continue;
			}

			$element->classes = array_diff( (array) $element->classes, array( 'menu-item-has-children' ) );
		}

		return parent::walk( $elements, $max_depth, ...$args );
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
			'<li id="rmp-menu-item-%s" %s>',
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

		/*
		 * Build the submenu toggle. It is a real <button> that sits beside the link rather
		 * than a <div> inside it: a control nested in an anchor is neither valid HTML nor
		 * reachable by keyboard, so the submenu could only ever be opened with a mouse.
		 */
		$sub_menu_arrow = '';
		$has_children   = in_array( 'rmp-menu-item-has-children', $rmp_menu_classes, true );

		/* No toggle if we are at the final depth level - there is nothing left to reveal. */
		if ( intval( $depth ) + 1 === intval( $this->options['menu_depth'] ) ) {
			$has_children = false;
		}

		if ( $has_children ) {
			$is_expanded = ( 'on' === $this->options['auto_expand_all_submenus'] )
				|| (
					'on' === $this->options['auto_expand_current_submenus'] &&
					(
						in_array( 'rmp-menu-item-current-parent', $rmp_menu_classes, true ) ||
						in_array( 'rmp-menu-item-current-ancestor', $rmp_menu_classes, true )
					)
				);

			$arrow_classes = 'rmp-menu-subarrow';
			if ( $is_expanded ) {
				$arrow_classes .= ' rmp-menu-subarrow-active';
			}

			/* translators: %s: Title of the menu item the submenu belongs to. */
			$toggle_label = sprintf( __( 'Toggle submenu of %s', 'responsive-menu' ), wp_strip_all_tags( $title ) );

			/**
			 * Filters the accessible name of a submenu toggle button.
			 *
			 * @since 4.8.0
			 *
			 * @param string $toggle_label Accessible name announced by screen readers.
			 * @param object $item         Menu item object.
			 * @param int    $depth        Depth of the menu item.
			 */
			$toggle_label = apply_filters( 'rmp_submenu_toggle_label', $toggle_label, $item, $depth );

			$sub_menu_arrow = sprintf(
				'<button type="button" class="%1$s" aria-expanded="%2$s" aria-controls="rmp-submenu-%3$s" aria-label="%4$s">%5$s</button>',
				esc_attr( $arrow_classes ),
				$is_expanded ? 'true' : 'false',
				esc_attr( $item->ID ),
				esc_attr( $toggle_label ),
				rm_sanitize_html_tags( $is_expanded ? $this->get_active_arrow() : $this->get_inactive_arrow() )
			);
		}

		$item_output  = '';
		$item_output .= sprintf( '<a %s >', $attributes );
		$item_output .= $title;
		$item_output .= '</a>';
		$item_output .= $sub_menu_arrow;

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

		/*
		 * Add sub-menu item wrap. The id is what the parent item's toggle button points at
		 * with aria-controls, and role="menu" is deliberately absent: this is site
		 * navigation, not an application menu with roving-tabindex semantics.
		 */
		$output .= sprintf(
			'<ul id="rmp-submenu-%1$s" aria-label="%2$s" data-depth="%3$s" class="rmp-submenu rmp-submenu-depth-%4$s">',
			esc_attr( $this->current_item->ID ),
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
