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
			add_filter( 'register_block_type_args', array( $this, 'allow_core_blocks_in_menu_items' ), 10, 2 );
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

			// Register rmp/menu-items server-side so WP knows the block exists for rendering.
			register_block_type( 'rmp/menu-items', [
				'attributes' => [
					'id'                 => [ 'type' => 'string' ],
					'menuStyle'          => [ 'type' => 'object' ],
					'submenuStyle'       => [ 'type' => 'object' ],
					'submenuBehaviour'   => [ 'type' => 'object' ],
					'submenuIndentation' => [ 'type' => 'object' ],
					'triggerIcon'        => [ 'type' => 'object' ],
					'blockStyles'        => [ 'type' => 'object' ],
				],
				'render_callback' => [ $this, 'render_menu_items_block' ],
			] );
		}

		public function render_menu_items_block( $attributes, $content ) {
			if ( ! empty( $attributes['id'] ) && ! empty( $attributes['blockStyles'] ) && is_array( $attributes['blockStyles'] ) ) {
				$css = sprintf(
					'.rmp-block-menu-items-%1$s { %2$s }',
					esc_attr( $attributes['id'] ),
					$this->render_css( $attributes['blockStyles'] )
				);
				if ( ! empty( $css ) ) {
					$style_id = 'rmp-block-menu-items-' . $attributes['id'];
					if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'rmp_block_render_head_css', true, $attributes ) ) {
						$this->render_inline_css( $css, $style_id, true );
					}
				}
			}
			return $content;
		}

		public function allow_core_blocks_in_menu_items( $args, $block_type ) {
			$allowed_blocks = array(
				'core/navigation-link',
				'core/navigation-submenu',
				'core/button',
				'core/home-link',
				'core/social-links',
				'core/loginout',
			);

			if ( in_array( $block_type, $allowed_blocks, true ) ) {
				if ( isset( $args['parent'] ) && is_array( $args['parent'] ) ) {
					if ( ! in_array( 'rmp/menu-items', $args['parent'], true ) ) {
						$args['parent'][] = 'rmp/menu-items';
					}
				} else {
					$args['parent'] = array( 'rmp/menu-items' );
				}
			}

			return $args;
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

				// Inject breakpoint media query as a CSS-only fallback.
				// JS applies .rmp-desktop-mode dynamically; this hides the
				// trigger before JS runs to prevent a flash-of-hamburger.
				if ( ! empty( $attributes['breakpoint'] ) ) {
					$bp         = intval( $attributes['breakpoint'] );
					$bp_css     = sprintf(
						'@media (min-width: %1$dpx) {
							.rmp-block-navigator-%2$s .rmp-block-menu-trigger { display: none !important; }
							.rmp-block-navigator-%2$s .rmp-block-container {
								position: static !important;
								height: auto !important;
								width: auto !important;
								max-width: none !important;
								min-width: 0 !important;
								overflow: visible !important;
								visibility: visible !important;
								opacity: 1 !important;
							}
						}',
						$bp,
						esc_attr( $unique_id )
					);
					$bp_style_id = 'rmp-block-breakpoint-' . $unique_id;
					if ( ! wp_style_is( $bp_style_id, 'enqueued' ) ) {
						$this->render_inline_css( $bp_css, $bp_style_id, true );
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