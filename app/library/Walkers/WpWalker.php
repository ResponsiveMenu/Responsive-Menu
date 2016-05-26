<?php

namespace ResponsiveMenu\Walkers;

class WpWalker extends \Walker_Nav_Menu
{

    public function __construct($arrows)
    {
      $this->arrows = $arrows;
    }

  	public function start_lvl( &$output, $depth = 0, $args = array() )
    {
  		$output .= "<ul class='responsive-menu-submenu'>";
  	}

  	public function end_lvl( &$output, $depth = 0, $args = array() )
    {
  		$output .= "</ul>";
  	}

  	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {

  		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
      $responsive_menu_classes = [];

      # Turn into our Responsive Menu Classes
      foreach($classes as $class):
        switch($class):
          case 'menu-item': $responsive_menu_classes[] = 'responsive-menu-item'; break;
          case 'current-menu-item': $responsive_menu_classes[] = 'responsive-menu-current-item'; break;
          case 'menu-item-has-children': $responsive_menu_classes[] = 'responsive-menu-item-has-children'; break;
          case 'current-menu-parent': $responsive_menu_classes[] = 'responsive-menu-item-current-parent'; break;
          case 'current-menu-ancestor': $responsive_menu_classes[] = 'responsive-menu-item-current-ancestor'; break;
        endswitch;
      endforeach;

  		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

  		$class_names = join(' ', array_unique($responsive_menu_classes));
  		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

  		$id = ' id="responsive-menu-item-' . esc_attr( $item->ID ) . '"';

  		$output .= '<li' . $id . $class_names .'>';

  		$atts = array();
  		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
  		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
  		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
  		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';


  		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

  		$attributes = '';
  		foreach ( $atts as $attr => $value ) {
  			if ( ! empty( $value ) ) {
  				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
  				$attributes .= ' ' . $attr . '="' . $value . '"';
  			}
  		}

  		/** This filter is documented in wp-includes/post-template.php */
  		$title = apply_filters( 'the_title', $item->title, $item->ID );

  		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

      if(in_array('responsive-menu-item-has-children', $responsive_menu_classes)):

        if( in_array('responsive-menu-item-current-parent', $responsive_menu_classes)
          || in_array('responsive-menu-item-current-ancestor', $responsive_menu_classes)):
          $initial_arrow = '<div class="responsive-menu-subarrow responsive-menu-subarrow-active">' . $this->arrows['active'] . '</div>';
        else:
          $initial_arrow = '<div class="responsive-menu-subarrow">' . $this->arrows['inactive'] . '</div>';
        endif;
      else:
        $initial_arrow = '';
      endif;

      $item_output = $args->before;
  		$item_output .= '<a'. $attributes .'>';
  		$item_output .= $args->link_before . $title . $args->link_after;
      $item_output .= $initial_arrow;
  		$item_output .= '</a>';
  		$item_output .= $args->after;

  		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

  	}

  	public function end_el( &$output, $item, $depth = 0, $args = array() )
    {
  		$output .= "</li>\n";
  	}

}
