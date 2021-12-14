<?php
/**
 * RMP Widget class that hold the responsive menu elementor widget.
 *
 * @category   Class
 * @package    responsive-menu-pro
 * @author     Expresstech System
 * @since      4.0.2
 */

namespace RMP\Features\Inc\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use RMP\Features\Inc\RMP_Menu;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RMP_Widget extends Widget_Base {

	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	/**
	 * Function to return the widget name.
	 *
	 * @since 4.0.2
	 *
	 * @return string
	 */
	public function get_name() {
		return 'RMP_Widget';
	}

	/**
	 * Function to return the widget title.
	 *
	 * @since 4.0.2
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Responsive Menu', 'responsive-menu' );
	}

	/**
	 * Function to return the widget icon.
	 *
	 * @since 4.0.2
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-menu-bar';
	}

	/**
	 * Function to add the widget in the category list.
	 *
	 * @since 4.0.2
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Add keywords to search the widget in elementor.
	 *
	 * @since 4.0.2
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'menu', 'nav', 'button', 'responsive' );
	}

	/**
	 * Function to registered the input controls.
	 *
	 * @since 4.0.2
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Menu Setting', 'responsive-menu' ),
			)
		);

		$menus = rmp_get_all_menus();

		if ( ! empty( $menus ) ) {
			$menu_id = null;
			if ( function_exists( 'array_key_first' ) ) {
				$menu_id = array_key_first( $menus );
			} else {
				$menu_id = ! empty( $menus ) ? array_keys( $menus )[0] : null;
			}

			$this->add_control(
				'rmp_menu',
				array(
					'label'        => esc_html__( 'Responsive Menu', 'responsive-menu' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					/* translators: %s: HTML tag */
					'description'  => sprintf( esc_html__( 'Go to the %1$sResponsive menu customizer%2$s to style your menu.', 'responsive-menu' ), '<a class="rmp-menu-edit-link" href="' . admin_url( 'post.php?post=' . $menu_id . '&action=edit&editor=true' ) . '" target="_blank" rel="noopener">', '</a>' ),
				)
			);
		} else {
			$this->add_control(
				'rmp_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s: HTML tag */
					'raw'             => '<strong>' . esc_html__( 'There are no menus in your site.', 'responsive-menu' ) . '</strong><br>' . sprintf( esc_html__( 'Go to the %1$s Menus screen %2$s to create one.', 'responsive-menu' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=rmp_menu' ) ) . '" target="_blank" rel="noopener">', '</a>' ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Function to update the contents in preview and render the menu.
	 *
	 * @since 4.0.2
	 */
	protected function render() {
		$available_menus = rmp_get_all_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$menu_id  = $settings['rmp_menu'];

		$menu = new RMP_Menu( $menu_id );
		$menu->mobile_menu();
	}
}
