<?php

/**
 * This is core class file for responsive menu pro.
 *
 * @package responsive_menu_pro
 * @since   4.0.0
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
		 */
		public function __construct( $menu_id ) {
			$option_manager = Option_Manager::get_instance();
			$this->options  = $option_manager->get_options( $menu_id );

			$this->menu_id = $menu_id;
		}

		/**
		 * Prepare mobile menu markup.
		 *
		 * @version 4.0.0
		 *
		 * @return HTML|string
		 */
		public function mobile_menu() {
			$menu_items = '';
			if ( ! empty( $this->options['items_order'] ) ) {
				$menu_items = $this->options['items_order'];
			}

			if ( empty( $menu_items ) ) {
				return;
			}

			$side_animation         = 'rmp-' . $this->options['animation_type'] . '-' . $this->options['menu_appear_from'];
			$menu_container_classes = apply_filters( 'rmp_menu_container_classes', array( 'rmp-container', $side_animation ), $this->menu_id );
			$menu_container_classes = implode( ' ', $menu_container_classes );

			$this->menu_trigger()
			?>
			<div id="rmp-container-<?php echo esc_attr( $this->menu_id ); ?>" class="rmp-container <?php echo esc_attr( $menu_container_classes ); ?>">
				<?php
				foreach ( $menu_items as $key => $value ) {
					if ( ! empty( $value ) && 'on' === $value ) {
						if ( 'menu' === $key ) {
							$this->menu();
						} elseif ( 'title' === $key ) {
							$this->menu_title();
						} elseif ( 'search' === $key ) {
							$this->menu_search_box();
						} else {
							$this->menu_additional_content();
						}
					}
				}
				?>
			</div>
			<?php
		}

		/**
		 * Function to print the menu markups in webpage.
		 */
		public function build_menu() {
			$this->mobile_menu();

			/**
			 * Filters the menu marksup.
			 *
			 * @since 4.1.0
			 *
			 * @param HTML|string $html
			 * @param int         menu_id
			 */
			echo apply_filters( 'rmp_menu_html', '', $this->menu_id );
		}

		/**
		 * Function to return the prepared menu items.
		 *
		 * @since 4.0.0
		 *
		 * @return HTML|string
		 */
		public function menu() {
			$param = $this->rmp_nav_menu_args();

			if ( empty( $param ) ) {
				return;
			}

			$param['echo'] = false;

			echo wp_nav_menu( $param );

			/**
			 * Filters the nav menu markups.
			 *
			 * @since 4.1.2
			 *
			 * @param HTML  $menu_markups
			 * @param int   $this->menu_id
			 * @param array $param
			 */
			echo apply_filters( 'rmp_menu_markups', '', $this->menu_id, $param );
		}

		public function menu_trigger() {
			$trigger_click_animation = '';
			if ( ! empty( $this->options['button_click_animation'] ) ) {
				$trigger_click_animation = 'rmp-menu-trigger-' . $this->options['button_click_animation'];
			}

			$toggle_theme_class = '';
			if ( ! empty( $this->options['menu_theme'] ) ) {
				$toggle_theme_class = 'rmp-' . str_replace( ' ', '-', strtolower( $this->options['menu_theme'] ) ) . '-trigger';
			}

			$toggle_theme_class = apply_filters( 'rmp_menu_toggle_classes', array( 'rmp_menu_trigger', $trigger_click_animation ), $this->menu_id );
			$toggle_theme_class = implode( ' ', $toggle_theme_class );
			if ( wp_is_mobile() ) {
				$toggle_theme_class .= ' rmp-mobile-device-menu';
			}
			$menu_trigger_destination = '';
			if ( ! empty( $this->options['hamburger_position_selector'] ) ) {
				$menu_trigger_destination = 'data-destination=' . $this->options['hamburger_position_selector'];
			}
			?>
			<button type="button"  aria-controls="rmp-container-<?php echo esc_attr( $this->menu_id ); ?>" aria-label="Menu Trigger" id="rmp_menu_trigger-<?php echo esc_attr( $this->menu_id ); ?>" <?php echo esc_attr( $menu_trigger_destination ); ?> class="<?php echo esc_attr( $toggle_theme_class ); ?>">
				<?php
				$trigger_text_position = '';

				if ( ! empty( $this->options['button_title_position'] ) ) {
					$trigger_text_position = $this->options['button_title_position'];
				}

				if ( ( 'left' === $trigger_text_position || 'top' === $trigger_text_position ) && ! empty( $this->options['button_title'] ) ) {
					// Menu trigger text.
					?>
				<div class="rmp-trigger-label rmp-trigger-label-<?php echo esc_attr( $trigger_text_position ); ?>">
					<span class="rmp-trigger-text"><?php echo esc_html( $this->options['button_title'] ); ?></span>
						<?php
						if ( ! empty( $this->options['button_title_open'] ) ) {
							?>
					<span class="rmp-trigger-text-open"><?php echo esc_html( $this->options['button_title_open'] ); ?></span>
						<?php } ?>
				</div>
				<?php } ?>
				<span class="rmp-trigger-box">
				<?php

				// Normal state menu trigger type.
				if ( ! empty( $this->options['button_font_icon'] ) ) {
					?>
					<span class="rmp-trigger-icon rmp-trigger-icon-inactive"><?php echo wp_kses_post( $this->options['button_font_icon'] ); ?></span>
					<?php
				} elseif ( ! empty( $this->options['button_image'] ) ) {
					?>
					<img src="<?php echo esc_url( $this->options['button_image'] ); ?>" alt="<?php echo esc_attr( rmp_image_alt_by_url( $this->options['button_image'] ) ); ?>" class="rmp-trigger-icon rmp-trigger-icon-inactive" width="100" height="100">
					<?php
				} else {
					?>
					<span class="responsive-menu-pro-inner"></span>
					<?php
				}

				// Active state menu trigger type.
				if ( ! empty( $this->options['button_font_icon_when_clicked'] ) ) {
					?>
				<span class="rmp-trigger-icon rmp-trigger-icon-active"><?php echo wp_kses_post( $this->options['button_font_icon_when_clicked'] ); ?></span>
					<?php
				} elseif ( ! empty( $this->options['button_image_when_clicked'] ) ) {
					?>
				<img src="<?php echo esc_url( $this->options['button_image_when_clicked'] ); ?>" alt="<?php echo esc_attr( rmp_image_alt_by_url( $this->options['button_image_when_clicked'] ) ); ?>" class="rmp-trigger-icon rmp-trigger-icon-active" width="100" height="100">
					<?php
				}
				?>
			</span>
			<?php

			if ( ( 'bottom' === $trigger_text_position || 'right' === $trigger_text_position ) && ! empty( $this->options['button_title'] ) ) {
				// Menu trigger text.
				?>
				<div class="rmp-trigger-label rmp-trigger-label-<?php echo esc_attr( $trigger_text_position ); ?>">
					<span class="rmp-trigger-text"><?php echo esc_html( $this->options['button_title'] ); ?></span>
					<?php
					if ( ! empty( $this->options['button_title_open'] ) ) {
						?>
					<span class="rmp-trigger-text-open"><?php echo esc_html( $this->options['button_title_open'] ); ?></span>
					<?php } ?>
				</div>
		<?php } ?>
		</button>
			<?php
		}

		/**
		 * Returns menu title.
		 *
		 * @return HTML|string
		 */
		public function menu_title() {
			$menu_title_wrap = null;
			$menu_title      = '';
			if ( ! empty( $this->options['menu_title'] ) ) {
				$menu_title = $this->options['menu_title'];
			}

			$menu_image = '';
			if ( ! empty( $this->options['menu_title_image'] ) ) {
				$image_alt  = rmp_image_alt_by_url( $this->options['menu_title_image'] );
				$menu_image = sprintf(
					'<img class="rmp-menu-title-image" src="%1$s" alt="%2$s" title="%2$s" width="100" height="100"/>',
					esc_url( $this->options['menu_title_image'] ),
					esc_attr( $image_alt )
				);
			}

			if ( ! empty( $this->options['menu_title_font_icon'] ) ) {
				$menu_image = sprintf( '%s', $this->options['menu_title_font_icon'] );
			}

			$link_target = '_self';
			if ( ! empty( $this->options['menu_title_link_location'] ) ) {
				$link_target = $this->options['menu_title_link_location'];
			}
			?>
			<div id="rmp-menu-title-<?php echo esc_attr( $this->menu_id ); ?>" class="rmp-menu-title">
				<?php if ( ! empty( $this->options['menu_title_link'] ) ) { ?>
					<a href="<?php echo esc_url( $this->options['menu_title_link'] ); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="rmp-menu-title-link" id="rmp-menu-title-link">
				<?php }else { ?>
					<span class="rmp-menu-title-link">
				<?php } ?>
						<?php $title_html = $menu_image;
						$title_html .= "<span>" . $menu_title . "</span>";
						echo rm_sanitize_html_tags( $title_html );
				if ( ! empty( $this->options['menu_title_link'] ) ) { ?>
					</a>
				<?php }else { ?>
					</span>
				<?php } ?>
			</div>
			<?php
		}

		/**
		 * Return menu search box.
		 *
		 * @return HTML|string
		 */
		public function menu_search_box() {
			?>
			<div id="rmp-search-box-<?php echo esc_attr( $this->menu_id ); ?>" class="rmp-search-box">
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="rmp-search-form" role="search">
						<input type="search" name="s" title="Search" placeholder="<?php esc_attr_e( 'Search', 'responsive-menu' ); ?>" class="rmp-search-box">
					</form>
				</div>
			<?php
		}

		/**
		 * Function to prepare the the menu additional content section.
		 *
		 * @since 4.0.0
		 *
		 * @return HTML|string $content
		 */
		public function menu_additional_content() {
			$content = '';

			if ( ! empty( $this->options['menu_additional_content'] ) ) {

				// Remove script tags if found in menu contents.
				$content = preg_replace( '#<script(.*?)>(.*?)</script>#', '', $this->options['menu_additional_content'] );
			}
			?>
			<div id="rmp-menu-additional-content-<?php echo esc_attr( $this->menu_id ); ?>" class="rmp-menu-additional-content">
					<?php echo do_shortcode( $content ); ?>
				</div>
			<?php

			/**
			 * Filters the menu additional contents markups.
			 *
			 * @since 4.1.0
			 *
			 * @param string $content
			 * @param int    $menu_id
			 */
			echo apply_filters( 'menu_additional_content_html', '', $this->menu_id );
		}

		public function rmp_nav_menu_args( $args = null ) {
			$menu          = $this->get_wp_menu_to_use();
			$menu_location = $this->get_wp_menu_location();
			$wp_menu_obj   = wp_get_nav_menu_object( $menu );

			// Check menu object is not empty.
			if ( empty( $wp_menu_obj ) ) {
				return $args;
			}

			$menu_depth = 0;
			if ( ! empty( $this->options['menu_depth'] ) ) {
				$menu_depth = $this->options['menu_depth'];
			}

			$menu_label = ! empty( $this->options['menu_name'] ) ? $this->options['menu_name'] : 'Default';

			if ( empty( $menu_label ) ) {
				$menu_label = $menu;
			}

			$item_wrap_attrs = array(
				'id'         => '%1$s',
				'class'      => '%2$s',
				'role'       => 'menubar',
				'aria-label' => $menu_label,
			);

			$wrap_attributes = apply_filters( 'rmp_wrap_attributes', $item_wrap_attrs, $this->menu_id, $menu_location );

			$attributes = '';
			foreach ( $wrap_attributes as $attribute => $value ) {
				if ( ! empty( $value ) ) {
					$attributes .= sprintf( ' %s="%s"', $attribute, esc_attr( $value ) );
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
				'menu_id'         => 'rmp-menu-' . $this->menu_id,
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

			$param = apply_filters( 'rmp_nav_menu_args', $param, $wp_menu_obj->term_id, $menu_location );
			return $param;
		}


		/**
		 * Function to return the correct wp menu name.
		 *
		 * @version 4.0.0
		 *
		 * @return string  Name of wp menu.
		 */
		public function get_wp_menu_to_use() {
			$menu = '';

			// Set menu as per settings priority.
			if ( ! empty( $this->options['different_menu_for_mobile'] ) && 'on' === $this->options['different_menu_for_mobile'] && wp_is_mobile() ) {
				$menu = $this->options['menu_to_use_in_mobile'];
			} elseif ( ! empty( $this->options['theme_location_menu'] ) && has_nav_menu( $this->options['theme_location_menu'] ) ) {
				$menu = get_term( get_nav_menu_locations()[ $this->options['theme_location_menu'] ], 'nav_menu' )->slug;
			} elseif ( ! empty( $this->options['menu_to_use'] ) ) {
				$menu = $this->options['menu_to_use'];
			} elseif ( ! empty( get_terms( 'nav_menu' )[0]->slug ) ) {
				$menu = get_terms( 'nav_menu' )[0]->slug;
			}

			return $menu;
		}

		/**
		 * Function to get the location of menu.
		 *
		 *  @return string   Returns the menu location.
		 */
		public function get_wp_menu_location() {
			$menu = $this->get_wp_menu_to_use();
			if ( empty( $menu ) ) {
				return;
			}

			$theme_location  = null;
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
	}
endif;
