<?php

/**
 * This is core class file for responsive menu pro.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

namespace RMP\Features\Inc;
use RMP\Features\Inc\Option_Manager;
use RMP\Features\Inc\Walker;

/** Disbale the direct access to this class */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'RMP_Menu' ) ) :

	/**
	 * Class RMP_Menu prepare the menu as per loction and menu id.
	 *
	 * @package    responsive_menu_pro
	 *
	 * @author     Expresstech System
	 */
	class RMP_Menu {

		/**
		 * Hold the menu id.
		 *
		 * @since    4.0.0
		 * @access   protected
		 * @var      string $menu_id
		 */
		protected $menu_id;

		/**
		 * Hold the menu id.
		 *
		 * @since    4.0.0
		 * @access   protected
		 * @var      array $options
		 */
		public $options;

		/**
		 * This is menu class constructor function.
		 * 
		 * @access public
		 *
		 */
		public function __construct( $menu_id ) {
		
			$option_manager = Option_Manager::get_instance();
			$this->options = $option_manager->get_options( $menu_id );

			$this->menu_id = $menu_id;
			// add_filter( 'wp_nav_menu', array( $this, 'rmp_add_menu_elements' ), 999999, 2 );
			// add_filter( 'wp_nav_menu_args', array( $this, 'rmp_location_wise_nav_menu_args' ), 999999, 1 );
		}

		public function rmp_add_menu_elements( $nav_menu, $args ) {

			if ( empty( $args->walker ) || empty( $args->theme_location ) || $this->menu_id !=  $args->menu_id ) {
				return $nav_menu;
			}

			$menu_switcher = $this->menu_trigger();
			$menu_items    = $this->options['items_order'];
			$html          = '';

			foreach( $menu_items as $key => $value ) {
				if ( ! empty( $value ) && $value === 'on' ) {
					if ( 'menu' === $key ) {
						$html .= $nav_menu;
					} elseif ( 'title' === $key ) {
						$html .= $this->menu_title();
					} elseif ( 'search' === $key ) {
						$html .= $this->menu_search_box();
					} else {
						$html .= $this->menu_additional_content();
					}
				}	
			}

			$html = sprintf( '%s<div id="rmp-container-%s" class="rmp-container">%s</div>',
				$menu_switcher,
				$this->menu_id,
				$html
			);

			echo  html;
		}

		public function mobile_menu() {
			/*
				$menu_location = $this->get_wp_menu_location();
				if ( ! empty( $menu_location ) ) {
					return;
				}
			*/

			$menu_switcher = $this->menu_trigger();
			
			$menu_items = '';
			if ( ! empty( $this->options['items_order'] ) ) {
				$menu_items    = $this->options['items_order'];
			}

			$html  = '';

			if ( empty( $menu_items ) ) {
				return;
			}

			foreach( $menu_items as $key => $value ) {
				if ( ! empty( $value ) && $value === 'on' ) {
					if ( 'menu' === $key ) {
						$html .= $this->menu();
					} elseif ( 'title' === $key ) {
						$html .= $this->menu_title();
					} elseif ( 'search' === $key ) {
						$html .= $this->menu_search_box();
					} else {
						$html .= $this->menu_additional_content();
					}
				}	
			}

			$side_animation = 'rmp-' . $this->options['animation_type'] . '-' . $this->options['menu_appear_from'];
			$html = sprintf( '%s<div id="rmp-container-%s" class="rmp-container %s">%s</div>',
				$menu_switcher,
				$this->menu_id,
				esc_attr( $side_animation ),
				$html
			);

			//If page overlay is enable then show it.
			if ( 'on' == $this->options['menu_overlay'] ) {
				$html .= sprintf('<div class="rmp-page-overlay" id="rmp-page-overlay-%s"></div>', esc_attr( $this->menu_id  ) );
			}

			return $html;
		}

		public function build_new_menu() {

			$html = '';

			if ( 'on' == $this->options['use_header_bar'] ) {
				$html = $this->get_rmp_header();
			} else {
				$html = $this->mobile_menu();
			}

			echo  $html;
			return;
		}

		public function menu() {
			$param  = $this->rmp_nav_menu_args();
			$param['echo'] = false;
			return wp_nav_menu( $param );
		}

		public function menu_trigger() {

			$menu_trigger_type = '<div class="rmp-trigger-box">';

			//Normal state menu trigger type.
			if ( ! empty( $this->options['button_font_icon'] ) ) {
				$menu_trigger_type .= sprintf(
					'<span class="rmp-trigger-icon rmp-trigger-icon-inactive">%s</span>',
					$this->options['button_font_icon']
				);
			} else if ( ! empty ( $this->options['button_image'] ) ) {
				$menu_trigger_type .= sprintf(
					'<img src="%s" alt="%s" class="rmp-trigger-icon rmp-trigger-icon-inactive">',
					esc_url( $this->options['button_image'] ),
					rmp_image_alt_by_url( $this->options['button_image'] )
				);
			} else {
				$menu_trigger_type .= sprintf('<span class="responsive-menu-pro-inner"></span>');
			}

			//Active state menu trigger type.
			if ( ! empty( $this->options['button_font_icon_when_clicked'] ) ) {
				$menu_trigger_type .= sprintf(
					'<span class="rmp-trigger-icon rmp-trigger-icon-active">%s</span>',
					$this->options['button_font_icon_when_clicked']
				);
			} else if ( ! empty ( $this->options['button_image_when_clicked'] ) ) {
				$menu_trigger_type .= sprintf(
					'<img src="%s" alt="%s" class="rmp-trigger-icon rmp-trigger-icon-active">',
					esc_url( $this->options['button_image_when_clicked'] ),
					rmp_image_alt_by_url( $this->options['button_image_when_clicked'] )
				);
			}

			$menu_trigger_type .= '</div>';
			
			$menu_trigger_text      = '';
			$trigger_text_position  = '';

			if ( !empty( $this->options['button_title_position'] ) ) {
				$trigger_text_position = $this->options['button_title_position'];
			}

			//Menu trigger text.
			if ( ! empty( $this->options['button_title'] ) ) {
				$menu_trigger_text .= sprintf(
					'<span class="rmp-trigger-text">%s</span>',
					esc_html( $this->options['button_title'] )
				);

				if ( ! empty( $this->options['button_title_open'] ) ) {
					$menu_trigger_text .= sprintf(
						'<span class="rmp-trigger-text-open">%s</span>',
						esc_html( $this->options['button_title_open'] )
					);
				}

				$menu_trigger_text  = sprintf(
					'<div class="rmp-trigger-label rmp-trigger-label-%s">
						%s
					</div>',
					esc_attr($trigger_text_position),
					$menu_trigger_text
				);
			}

	
			$menu_trigger_content = '';

			if ( 'left' === $trigger_text_position || 'top' === $trigger_text_position )  {
				$menu_trigger_content .= $menu_trigger_text;

			}

			$menu_trigger_content .= $menu_trigger_type;

			if ( 'bottom' === $trigger_text_position || 'right' === $trigger_text_position )  {
				$menu_trigger_content .= $menu_trigger_text;
			}


			$trigger_click_animation = '';
			if ( ! empty( $this->options['button_click_animation'] ) ) {
				$trigger_click_animation = $this->options['button_click_animation'];
			}

			$open_on_page_load = '';
			// if ( ! empty ($this->options['show_menu_on_page_load'] ) ) {
			// 	$open_on_page_load  = 'is-active';
			// }

			$rmp_menu_trigger = sprintf(
				'<button type="button" menu-pressed="rmp-container-%s"  aria-controls="navigation" aria-label="Menu Trigger" id="rmp_menu_trigger-%s" class="rmp_menu_trigger rmp-menu-trigger-%s %s">
					%s
				</button>',
				$this->menu_id,
				$this->menu_id,
				$trigger_click_animation,
				$open_on_page_load,
				$menu_trigger_content
			);

			return $rmp_menu_trigger;
		}


		public function menu_title() {

			$menu_title_wrap = null;
			$menu_title = '';
			if ( ! empty( $this->options['menu_title'] ) ) {
				$menu_title = $this->options['menu_title'];
			}

			$menu_image = '';
			if ( ! empty( $this->options['menu_title_image'] ) ) {
				$image_alt   = rmp_image_alt_by_url( $this->options['menu_title_image'] );
				$menu_image  = sprintf('<img class="rmp-menu-title-image" src="%1$s" alt="%2$s" title="%2$s"/>',
					esc_url( $this->options['menu_title_image'] ),
					esc_attr( $image_alt )
				);
			}

			if( ! empty( $this->options['menu_title_font_icon'] ) ) {
				$menu_image = sprintf( "%s", $this->options['menu_title_font_icon'] );
			}

			$link_target = '_self';
			if ( ! empty( $this->options['menu_title_link_location'] ) ) {
				$link_target = $this->options['menu_title_link_location'];
			}

			$link_href = '';
			if ( ! empty( $this->options['menu_title_link'] ) ) {
				$link_href = sprintf('href="%s"',
					esc_url( $this->options['menu_title_link'] )
				);
			}

			$menu_title_wrap = sprintf('<div id="rmp-menu-title-%s"><a %s target="%s" id="rmp-menu-title-link">%s %s</a></div>',
				esc_attr( $this->menu_id),
				$link_href,
				esc_attr( $link_target ),
				$menu_image,
				esc_html( $menu_title )
			);

			return $menu_title_wrap;
		}

		public function menu_search_box() {
			
			$placeholder_text = '';
			if ( ! empty( $this->options['menu_search_box_text'] ) ) {
				$placeholder_text = $this->options['menu_search_box_text'];
			}

			$menu_search_wrap = sprintf('
				<div id="rmp-search-box-%s">
					<form action="%s" class="rmp-search-form" role="search">
						<input type="search" name="s" title="Search" tabindex="1"
							placeholder="%s"
							class="rmp-search-box">
					</form>
				</div>',
				esc_attr( $this->menu_id),
				esc_url( home_url( '/' ) ),
				esc_attr( $placeholder_text )
			);

			return $menu_search_wrap;
		}

		public function menu_additional_content() {

			$content = '';

			if ( ! empty( $this->options['menu_additional_content'] ) ) {
				$content = $this->options['menu_additional_content'];
			}

			$menu_content_wrap = sprintf('<div id="rmp-menu-additional-content-%s">%s</div>', esc_attr( $this->menu_id), $content );

			return $menu_content_wrap;
		}

		public function rmp_nav_menu_args( $args = null ) {

			$menu  = $this->get_wp_menu_to_use();

			// Check menu exist or not.
			$is_exist = false;
			foreach( get_terms('nav_menu') as $nav_menu ) {
				if ( $nav_menu->slug === $menu ) {
					$is_exist = true;
					break;
				}
			}

			if ( ! $is_exist ) {
				return $args;
			}

			$menu_location = $this->get_wp_menu_location();
			$menu_depth    = $this->options['menu_depth'];
			$wp_menu_obj   = wp_get_nav_menu_object( $menu );

			if ( empty( $menu_depth ) ) {
				$menu_depth = 0;
			}

			$menu_label = ! empty( $this->options['menu_name'] ) ? $this->options['menu_name'] : 'Default';

			if ( empty( $menu_label ) ) {
				$menu_label = $menu;
			}

			$item_wrap_attrs = array(
                "id"         => '%1$s',
                "class"      => '%2$s',
				"role"       => "menubar",
				"aria-label" => $menu_label,
			);

			$wrap_attributes = apply_filters( "rmp_wrap_attributes", $item_wrap_attrs , $this->menu_id, $menu_location );

			$attributes = "";
			foreach ( $wrap_attributes as $attribute => $value ) {
                if ( ! empty( $value ) ) {
                    $attributes .= sprintf(' %s="%s"', $attribute, esc_attr( $value ) );
                }
			}

			$walker = new Walker( $this->options );
			if ( ! empty( $this->options['custom_walker'] ) ) {
				$walker = new $this->options['custom_walker']( $this->options );
			}

			$param = array(
				'container'       => 'div',
				'container_id'    => 'rmp-menu-wrap-' . $this->menu_id,
				'container_class' => 'rmp-menu-wrap',
				'menu_id'         => 'rmp-menu-'.$this->menu_id,
				'menu_class'      => 'rmp-menu',
				'menu'            => $wp_menu_obj,
				'depth'           => $menu_depth,
				'fallback_cb'     => 'wp_page_menu',
                'before'          => '',
                'after'           => '',
                'link_before'     => '',
                'link_after'      => '',
				'theme_location'  => '',
				'walker'          => $walker,
				'items_wrap'      => '<ul' . $attributes . '>%3$s</ul>',
			);

			$param = apply_filters( "rmp_nav_menu_args", $param, $wp_menu_obj->term_id , $menu_location );
			return $param;
		}

		/**
		 * Function to add the menu in theme location.
		 * 
		 * @param array $args
		 */
		public function rmp_location_wise_nav_menu_args( $args ) {

			if ( empty( $args['theme_location'] ) ) {
				return $args;
			}

			if ( $args['theme_location'] !== $this->get_wp_menu_location() ) {
				return $args;
			}
			
			$param = $this->rmp_nav_menu_args();
			$args  = array_merge( $args, $param );

			return $args;
		}

		public function get_wp_menu_to_use() {

			$menu = '';

			// Set menu as per settings priority.
			if ( ! empty( $this->options['different_menu_for_mobile'] ) && 'on' === $this->options['different_menu_for_mobile'] &&  wp_is_mobile() ) {
				$menu = $this->options['menu_to_use_in_mobile'];
			} elseif( ! empty( $this->options['theme_location_menu'] ) && has_nav_menu( $this->options['theme_location_menu'] ) ) {
				$menu = get_term(get_nav_menu_locations()[ $this->options['theme_location_menu'] ], 'nav_menu')->slug;
			} elseif( ! empty($this->options['menu_to_use'] ) ) {
				$menu = $this->options['menu_to_use'];
			} elseif( ! empty( get_terms('nav_menu')[0]->slug ) ) {
				$menu = get_terms('nav_menu')[0]->slug;
			}

			return $menu;
		}

		public function get_wp_menu_location() {

			// If `show on current theme location`  option is enabled then check associated menu with location.
			/*
			if ( empty( $this->options['current_theme_location'] ) || 'off' == $this->options['current_theme_location'] ) {
				return;
			}
			*/

			$menu = $this->get_wp_menu_to_use();
			if ( empty( $menu ) ) {
				return;
			}

			$theme_location = null;
			$menu_object     = wp_get_nav_menu_object( $menu );
			$theme_locations = get_nav_menu_locations();
			foreach ( $theme_locations as $location => $value ) {
				if ( $value === $menu_object->term_id ) {
					$theme_location = $location;
					break;
				}
			}
			return $theme_location;
		}

		public function get_rmp_header() {

			$header_item_html  = '';
			$header_items = [];
			if ( ! empty( $this->options['header_bar_items_order'] ) ) {
				$header_items = $this->options['header_bar_items_order'];
			}

			foreach( $header_items as $key => $value ) {
				if ( ! empty( $value ) && $value === 'on' ) {
					if( 'menu' === $key ) {
						$header_item_html .= $this->mobile_menu();
					} elseif ( 'title' === $key ) {
						$header_item_html .= $this->get_rmp_header_title();
					} elseif ( 'search' === $key ) {
						$header_item_html .= $this->get_rmp_header_search();
					} elseif( 'logo' === $key ) {
						$header_item_html .= $this->get_rmp_header_logo();
					} else {
						$header_item_html .= $this->get_rmp_header_content();
					}
				}
			}

			return sprintf(
				'<div id="rmp-header-bar-%s" class="rmp-header-bar-container">
					<div class="rmp-header-bar-items">
						%s
					</div>
				</div>',
				esc_attr( $this->menu_id),
				$header_item_html
			);
		}

		public function get_rmp_header_title() {

			$menu_title = '';
			if ( ! empty( $this->options['header_bar_title'] ) ) {
				$menu_title = esc_html( $this->options['header_bar_title'] );
			}

			if ( ! empty( $this->options['header_bar_logo_link'] ) ) {
				$menu_title = sprintf('<a href="%s">%s</a>',
					esc_url( $this->options['header_bar_logo_link'] ),
					$menu_title
				);
			}

			return sprintf('<div id="rmp-header-title-%s" class="rmp-header-bar-item rmp-header-bar-title">%s</div>',
				esc_attr( $this->menu_id),
				$menu_title
			);
		}

		public function get_rmp_header_logo() {

			$header_logo = '';
			if ( ! empty( $this->options['header_bar_logo'] ) ) {
				$image_alt    = rmp_image_alt_by_url( $this->options['header_bar_logo'] );
				$header_logo  = sprintf('<img class="rmp-menu-header-logo" src="%1$s" alt="%2$s" title="%2$s"/>',
					esc_url( $this->options['header_bar_logo'] ),
					esc_attr( $image_alt )
				);
			}

			if ( ! empty( $this->options['header_bar_logo_link'] ) ) {
				$header_logo = sprintf('<a href="%s">%s</a>',
					esc_url( $this->options['header_bar_logo_link'] ),
					$header_logo
				);
			}

			return sprintf('<div id="rmp-header-logo-%s" class="rmp-header-bar-item rmp-header-bar-logo">%s</div>',
				esc_attr( $this->menu_id),
				$header_logo
			);
		}

		public function get_rmp_header_search() {

			$placeholder_text = '';
			if ( ! empty( $this->options['menu_search_box_text'] ) ) {
				$placeholder_text = $this->options['menu_search_box_text'];
			}

			return sprintf('
				<div id="rmp-header-search-box-%s" class="rmp-header-bar-item rmp-header-bar-search">
					<form action="%s" class="rmp-search-form" role="search">
						<input type="search" name="s" title="Search" tabindex="1"
							placeholder="%s"
							class="rmp-search-box">
					</form>
				</div>',
				esc_attr( $this->menu_id),
				esc_url( home_url( '/' ) ),
				esc_attr( $placeholder_text )
			);

		}

		public function get_rmp_header_content() {

			$content = '';

			if ( ! empty( $this->options['header_bar_html_content'] ) ) {
				$content = do_shortcode($this->options['header_bar_html_content']);
			}

			return sprintf(
				'<div id="rmp-header-additional-content-%s" class="rmp-header-bar-item rmp-header-bar-contents">%s</div>',
				esc_attr( $this->menu_id),
				$content
			);

		}

	}
endif;