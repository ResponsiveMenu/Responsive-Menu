<?php
/**
 * Converts our Responsive Menu to a Gutenberg block.
 *
 * @package QSM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RMPBlock' ) ) {
	class RMPBlock {

		// The instance of this class
		private static $instance = null;

		// Returns the instance of this class.
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Main Construct Function
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_block' ) );
		}
		/**
		 * Register block.
		 */
		public function register_block() {
			if ( ! function_exists( 'register_block_type' ) ) {
				return;
			}
			register_block_type( __DIR__ . '/build', [
				'render_callback' => [ $this, 'render_block' ],
			] );

		}

		public function render_block( $attributes, $content, $block ) {
			if ( ! empty( $attributes['id'] ) ) {
				$unique_id = $attributes['id'];

				if ( ! empty( $attributes['blockStyles'] ) && is_array( $attributes['blockStyles'] ) ) {
					$css = sprintf(
						'.rmp-block-navigator-%1$s { %2$s }',
						esc_attr( $unique_id ),
						$this->render_css( $attributes['blockStyles'] ),
					);

					// print css
					if ( ! empty( $css ) ) {
						$style_id = 'rmp-block-navigator-' . $unique_id;

						if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'rmp_block_render_head_css', true, $attributes ) ) {
							$this->render_inline_css( $css, $style_id, true );
						}
					}
				}
			}

			return $content;
		}

		/**
		 * Generate dynamic styles
		 *
		 * @param array $styles
		 * @return string
		 */
		private function render_css( $styles ) {
			$style = [];
			foreach ( ( array ) $styles as $key => $value ) {
				$style[] = $key . ': ' . $value;
			}

			return join( ';', $style );
		}
		/**
		 * Render Inline CSS helper function
		 *
		 * @param array  $css the css for each rendered block.
		 * @param string $style_id the unique id for the rendered style.
		 * @param bool   $in_content the bool for whether or not it should run in content.
		 */
		private function render_inline_css( $css, $style_id, $in_content = false ) {
			wp_register_style( $style_id, false );
			wp_enqueue_style( $style_id );
			wp_add_inline_style( $style_id, $css );
			if ( 1 === did_action( 'wp_head' ) && $in_content ) {
				wp_print_styles( $style_id );
			}
		}
	}
	RMPBlock::get_instance();
}