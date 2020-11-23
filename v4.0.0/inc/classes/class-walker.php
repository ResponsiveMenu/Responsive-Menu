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
use RMP\Features\Inc\Widget_Manager;

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
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0) {

		$this->set_current_item($item);

		$classes = array();
		if ( ! empty( $item->classes ) ) {
			$classes = (array) $item->classes;
		}

		$rmp_menu_classes = $classes;

		/** Add rmp menu classes as per item */
		foreach( $classes as $class ) {
			switch($class) {
				case 'menu-item' :
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
		if ( $item->menu_item_parent == 0 ) {
			$rmp_menu_classes[] = 'rmp-menu-top-level-item';
			$this->root_item_id = $item->ID;
		} else {
			$rmp_menu_classes[] = 'rmp-menu-sub-level-item';
		}

		// Avoid item which is already in mega menu contents.
		$is_mega_menu = $this->is_mega_menu_item( $this->root_item_id );
        if ( $is_mega_menu && $item->menu_item_parent ) {
			return;
		}

		// Add item-has-child class if single top menu item is mega menu.
		$is_mega_menu = $this->is_mega_menu_item( $item->ID );
        if ( $is_mega_menu && ! $item->menu_item_parent ) {
			$has_child = array_search( 'menu-item-has-children', $rmp_menu_classes );
			if( ! $has_child ) {
				$rmp_menu_classes[] = 'rmp-menu-item-has-children';
				$rmp_menu_classes[] = 'menu-item-has-children';
			}
		}

		/* Clear child class if we are at the final depth level */
		if ( isset( $rmp_menu_classes ) ) {
			$has_child = array_search( 'rmp-menu-item-has-children', $rmp_menu_classes );
			if ( ( $depth + 1 ) == $this->options['menu_depth'] && ( $has_child ) !== false) {
				unset( $rmp_menu_classes[$has_child] );
			}
		}

		$class_names = join( ' ', array_unique( $rmp_menu_classes ) );

		/** Prepare classes for menu item. */
		if ( ! empty( $class_names )  ) {
			$class_names = sprintf( 'class="%s"', esc_attr( $class_names ) );	
		} else {
			$class_names = '';
		}

		// Start menu item and set classes & ID.
		$output .= sprintf( 
			'<li id="rmp-menu-item-%s" %s role="none">',
			esc_attr( $item->ID ) ,
			$class_names
		);

		// Set attributes on menu item link.
		$atts             = array();
		$atts['title']    = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target']   = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']      = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']     = ! empty( $item->url ) ? $item->url : '';
		$atts['class']    = 'rmp-menu-item-link';
		$atts['role']     = 'menuitem';
		$atts             = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach( $atts as $key => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ('href' === $key ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= sprintf( ' %s = "%s" ' , $key , $value );
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'rmp_menu_item_title', $title, $item, $args, $depth );

		// Activate the required menu item by default.
		$sub_menu_arrow = '';
		if ( in_array( 'rmp-menu-item-has-children', $rmp_menu_classes ) ) {

			$inactive_arrow = sprintf(
				'<div class="rmp-menu-subarrow">%s</div>',
				$this->get_inactive_arrow()
			);

			$active_arrow = sprintf(
				'<div class="rmp-menu-subarrow rmp-menu-subarrow-active">%s</div>',
				$this->get_active_arrow()
			);

			if( 'on' == $this->options['auto_expand_all_submenus'] ) {
				$sub_menu_arrow = $active_arrow;
			} elseif (
				$this->options['auto_expand_current_submenus'] == 'on' &&
				( in_array( 'rmp-menu-item-current-parent', $rmp_menu_classes ) ||
				in_array('rmp-menu-item-current-ancestor', $rmp_menu_classes ) ) ) {
				$sub_menu_arrow = $active_arrow;
			} else {
				$sub_menu_arrow = $inactive_arrow;
			}
		}

		/* Clear Arrow if we are at the final depth level */
		if ( $depth + 1 == $this->options['menu_depth'] ) {
			$sub_menu_arrow = '';
		}

		$item_output  = '';
		$item_output .= sprintf( '<a %s >', $attributes );
		$item_output .= $this->get_menu_font_icon( $item->ID ); // Set menu item icon.
		$item_output .= $title;
		$item_output .= $sub_menu_arrow;
		$item_output .= '</a>';

		// If description is enable then add it below of menu item.
		if ( ! empty( $item->description ) && $this->options['submenu_descriptions_on'] == 'on' ) {
			$item_output .= sprintf( '<p class="rmp-menu-item-description"> %s </p>', esc_html( $item->description ) );  
		}

		// Add mega menu contents if item has enabled the mega menu options.
        if ( $is_mega_menu && ! $item->menu_item_parent ) {
            $item_output .= $this->prepare_mega_menu($item->ID );
		}

		/* End Add Desktop Menu Widgets to Sub Items */
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args );

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

        $is_mega_menu = $this->is_mega_menu_item( $this->root_item_id );
        if ( $is_mega_menu ) {
            return;
		}

        $extra_class = '';
        if ( $this->options['use_desktop_menu'] == 'on' ) {
            $extra_class .= ' rmp-desktop-menu-container ';
		}

        // Add sub-menu item wrap.
        $output .= sprintf( '<ul aria-label="%s" 
            role="menu" data-depth="%s" 
            class=" %s rmp-submenu rmp-submenu-depth-%s">',
            esc_attr( $this->current_item->title),
            ( $depth + 2 ),
            $extra_class,
            ($depth + 1) . $this->get_submenu_class_open_or_not()
		);

		if ( $this->options['use_slide_effect'] == 'on' ) {
			$output .= sprintf(
				'<div class="rmp-go-back"> %s </div>',
				$this->get_active_arrow() .' '.$this->options['slide_effect_back_to_text']
			);
    	}
	}

	/**
	 * Function to add the mega menu contents, items and widgets.
	 * 
	 * @version 4.0.0
	 * @access public
	 * 
	 * @param int $item_id
	 * 
	 * @return string|HTML Mega menu contents.
	 */
	public function prepare_mega_menu( $item_id ) {

        $extra_class = '';
        if ( $this->options['use_desktop_menu'] == 'on' ) {
            $extra_class .= 'rmp-desktop-menu-container ';
		}

		$mega_menu_output   = '';
        $mega_menu_settings = get_post_meta( $this->options['menu_id'], '_rmp_mega_menu_' . $item_id );

		if ( ! empty( $mega_menu_settings[0]['rows'] ) )  {
            $mega_menu_output .= sprintf('<div class="rmp-mega-menu-panel rmp-mega-menu-%s">', $item_id );
            foreach( $mega_menu_settings[0]['rows'] as $row ) {
                $mega_menu_output .= sprintf('<div class="rmp-mega-menu-row ">');
                foreach( $row['columns'] as $col ) {
                    $col_size = $col['meta']['column_size'];
                    $mega_menu_output .= sprintf('<div class="rmp-mega-menu-col rmp-mega-menu-col-%s">', $col_size);
                    if ( empty( $col['menu_items'] ) ) {
                        $mega_menu_output .= '</div>';
                        continue;
                    }
                    foreach( $col['menu_items'] as $item ) {
                        if ( ! empty( $item['item_type'] ) && 'widget' === $item['item_type'] ) {
                            $widget_manager = Widget_Manager::get_instance();
                            $mega_menu_output .= $widget_manager->show_widget( $item['item_id'] );
                        } else {
                            $id = get_post_meta( $item['item_id'], '_menu_item_object_id', true );
                            $url = get_permalink( $id );
                            $mega_menu_output .= sprintf(
                                '<a class="rmp-menu-item-link" href="%s">%s</a>',
                                esc_url( $url ),
                                $item['item_title']
                            );
                        }
                    }
                    $mega_menu_output .= '</div>';
                }
                $mega_menu_output .= '</div>';
            }
            $mega_menu_output .= '</div>';
		}

		// Add back options for sub menu sliding features.
        $output = '';
		if ( $this->options['use_slide_effect'] == 'on' ) {
			$output .= sprintf('<div class="rmp-go-back"> %s </div>',
				$this->get_active_arrow() .' '.$this->options['slide_effect_back_to_text']
			);
		}

        // Add mega menu items and widgets.
        $output = sprintf(
            '<ul role="menu" data-depth="1" class="rmp-mega-menu-container %s rmp-submenu rmp-submenu-depth-1 %s"> %s %s </ul>',
            $extra_class,
            $this->get_submenu_class_open_or_not(),
            $output,
            $mega_menu_output
		);

        return $output;
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
		$output .= "</li>";
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

		$is_mega_menu = $this->is_mega_menu_item( $this->root_item_id );
        if ( $is_mega_menu ) {
            return;
		}

		$output .= "</ul>";
	}

	/**
	 * Function to check the menu item is mega menu.
	 * 
	 * @param int $item_id
	 * 
	 * @return boolean
	 */
	public function is_mega_menu_item( $item_id ) {

		if ( ! empty( $this->options['mega_menu'][ $item_id ] ) && $this->options['mega_menu'][ $item_id ] == 'on' ) {
			return true;
		}

		return false;
	}

	/**
	 * Function get the active item toggle icon.
	 * 
	 * @return HTML
	 */
	public function get_active_arrow() {
		if ( ! empty( $this->options['active_arrow_font_icon'] ) ) {
			return $this->options['active_arrow_font_icon'];
		} elseif( ! empty( $this->options['active_arrow_image'] ) ) {
			return sprintf( '<img alt="%s" src="%s" />',
				rmp_image_alt_by_url( $this->options['active_arrow_image']),
				esc_url($this->options['active_arrow_image'])
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
		} elseif( ! empty( $this->options['inactive_arrow_image'] ) ) {
			return sprintf( '<img alt="%s" src="%s" />',
			    rmp_image_alt_by_url( $this->options['inactive_arrow_image']),
				esc_url($this->options['inactive_arrow_image'])
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
		return $this->options['auto_expand_all_submenus'] == 'on';
	}

	/**
	 * Check current submenu need to open or not.
	 * 
	 * @return boolean
	 */
	public function expand_current_submenu_on_and_item_is_parent() {
		return ($this->options['auto_expand_current_submenus'] == 'on')
			&& ($this->get_current_item()->current_item_ancestor || $this->get_current_item()->current_item_parent);
	}

	/**
	 * Function to return menu item icon.
	 * 
	 * @param int $id
	 * 
	 * @return string   Icon element.
	 */
	public function get_menu_font_icon( $id ) {

        if ( ! empty( $this->options['menu_font_icons'] ) ) {
			$icons = $this->options['menu_font_icons'];

			if ( in_array( $id, $icons['id'] ) ) {
				$key = array_search( $id, $icons['id'] );

				return $icons['icon'][$key];
			}

		}

		return '';
    }
}