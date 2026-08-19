<?php
/**
 * Server side of the Responsive Menu block.
 *
 * The editor saves every visual setting as a bag of CSS custom properties, in
 * three tiers — desktop plus the tablet and mobile properties that differ. This
 * class turns that back into one rule and two media queries, which is what lets
 * a single saved markup serve every viewport.
 *
 * @package responsive-menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RMPBlock' ) ) {

	/**
	 * Registers and renders the `rmp/menu` and `rmp/menu-items` blocks.
	 */
	class RMPBlock {

		/**
		 * Singleton instance.
		 *
		 * @var RMPBlock|null
		 */
		private static $instance = null;

		/**
		 * Core blocks that are allowed inside `rmp/menu-items`.
		 *
		 * Mirrors `blocks/src/menu-items/constants.js`.
		 *
		 * @var string[]
		 */
		const MENU_ITEM_BLOCKS = array(
			'core/navigation-link',
			'core/navigation-submenu',
			'core/button',
			'core/home-link',
			'core/social-links',
			'core/loginout',
			'core/page-list',
		);

		/**
		 * Fallback breakpoints, matching the block attribute defaults.
		 */
		const DEFAULT_BREAKPOINT        = 768;
		const DEFAULT_TABLET_BREAKPOINT = 1024;
		const DEFAULT_MOBILE_BREAKPOINT = 767;

		/**
		 * Returns the instance of this class.
		 *
		 * @return RMPBlock
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Hook everything up.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_block' ) );
			add_filter( 'register_block_type_args', array( $this, 'allow_core_blocks_in_menu_items' ), 10, 2 );
		}

		/**
		 * Register both blocks.
		 *
		 * `rmp/menu` is registered from its build metadata; `rmp/menu-items` is
		 * registered by hand because it ships inside the same build entry and
		 * therefore has no `block.json` of its own. Its attribute list has to be
		 * complete: WordPress strips any attribute a render callback did not
		 * declare, so a missing entry silently loses that setting's styles.
		 */
		public function register_block() {
			if ( ! function_exists( 'register_block_type' ) ) {
				return;
			}

			register_block_type(
				__DIR__ . '/build',
				array(
					'render_callback' => array( $this, 'render_block' ),
				)
			);

			register_block_type(
				'rmp/menu-items',
				array(
					'attributes'      => $this->menu_items_attributes(),
					'uses_context'    => array(
						'rmp/breakpoint',
						'rmp/tabletBreakpoint',
						'rmp/mobileBreakpoint',
					),
					'render_callback' => array( $this, 'render_menu_items_block' ),
				)
			);
		}

		/**
		 * Attribute schema for `rmp/menu-items`.
		 *
		 * Mirrors `blocks/src/menu-items/attributes.js`. Only the types matter
		 * server-side — defaults live in the editor — but every key must be
		 * present for its value to survive to the render callback.
		 *
		 * @return array Attribute schema.
		 */
		private function menu_items_attributes() {
			return array(
				'id'                 => array( 'type' => 'string' ),
				'menuStyle'          => array( 'type' => 'object' ),
				'submenuStyle'       => array( 'type' => 'object' ),
				'submenuBehaviour'   => array( 'type' => 'object' ),
				'submenuIndentation' => array( 'type' => 'object' ),
				'triggerIcon'        => array( 'type' => 'object' ),
				'desktopMenuStyle'   => array( 'type' => 'object' ),
				'responsive'         => array( 'type' => 'object' ),
				'blockStyles'        => array( 'type' => 'object' ),
			);
		}

		/**
		 * Let the core blocks we support declare `rmp/menu-items` as a parent.
		 *
		 * @param array  $args       Block type registration arguments.
		 * @param string $block_type Block name.
		 * @return array Filtered arguments.
		 */
		public function allow_core_blocks_in_menu_items( $args, $block_type ) {
			if ( ! in_array( $block_type, self::MENU_ITEM_BLOCKS, true ) ) {
				return $args;
			}

			if ( isset( $args['parent'] ) && is_array( $args['parent'] ) ) {
				if ( ! in_array( 'rmp/menu-items', $args['parent'], true ) ) {
					$args['parent'][] = 'rmp/menu-items';
				}
			} else {
				$args['parent'] = array( 'rmp/menu-items' );
			}

			return $args;
		}

		/**
		 * Render `rmp/menu`.
		 *
		 * @param array  $attributes Block attributes.
		 * @param string $content    Saved inner markup.
		 * @return string Markup, unchanged — this callback only emits CSS.
		 */
		public function render_block( $attributes, $content ) {
			if ( empty( $attributes['id'] ) ) {
				return $content;
			}

			$unique_id   = sanitize_html_class( $attributes['id'] );
			$breakpoints = $this->resolve_breakpoints( $attributes );

			$css = $this->build_responsive_css(
				'.rmp-block-navigator-' . $unique_id,
				isset( $attributes['blockStyles'] ) ? $attributes['blockStyles'] : array(),
				$breakpoints
			);

			$css .= $this->desktop_fallback_css( $unique_id, $breakpoints['desktop'] );

			$this->print_block_css( 'rmp-block-navigator-' . $unique_id, $css, $attributes );

			return $content;
		}

		/**
		 * Render `rmp/menu-items`.
		 *
		 * @param array         $attributes Block attributes.
		 * @param string        $content    Saved inner markup.
		 * @param WP_Block|null $block      Block instance, for its context.
		 * @return string Markup, unchanged.
		 */
		public function render_menu_items_block( $attributes, $content, $block = null ) {
			if ( empty( $attributes['id'] ) ) {
				return $content;
			}

			$unique_id = sanitize_html_class( $attributes['id'] );
			$context   = ( $block instanceof WP_Block && is_array( $block->context ) ) ? $block->context : array();

			$breakpoints = $this->resolve_breakpoints(
				array(
					'breakpoint'       => isset( $context['rmp/breakpoint'] ) ? $context['rmp/breakpoint'] : null,
					'tabletBreakpoint' => isset( $context['rmp/tabletBreakpoint'] ) ? $context['rmp/tabletBreakpoint'] : null,
					'mobileBreakpoint' => isset( $context['rmp/mobileBreakpoint'] ) ? $context['rmp/mobileBreakpoint'] : null,
				)
			);

			$css = $this->build_responsive_css(
				'.rmp-block-menu-items-' . $unique_id,
				isset( $attributes['blockStyles'] ) ? $attributes['blockStyles'] : array(),
				$breakpoints
			);

			$this->print_block_css( 'rmp-block-menu-items-' . $unique_id, $css, $attributes );

			return $content;
		}

		/**
		 * Breakpoints for one menu, clamped so the mobile query is always
		 * narrower than the tablet one — otherwise the tiers would fight.
		 *
		 * @param array $attributes Attributes (or context values) to read.
		 * @return array `[ desktop, tablet, mobile ]` in px.
		 */
		private function resolve_breakpoints( $attributes ) {
			$desktop = isset( $attributes['breakpoint'] ) ? absint( $attributes['breakpoint'] ) : 0;
			$tablet  = isset( $attributes['tabletBreakpoint'] ) ? absint( $attributes['tabletBreakpoint'] ) : 0;
			$mobile  = isset( $attributes['mobileBreakpoint'] ) ? absint( $attributes['mobileBreakpoint'] ) : 0;

			$desktop = $desktop ? $desktop : self::DEFAULT_BREAKPOINT;
			$tablet  = $tablet ? $tablet : self::DEFAULT_TABLET_BREAKPOINT;
			$mobile  = $mobile ? $mobile : self::DEFAULT_MOBILE_BREAKPOINT;

			return array(
				'desktop' => $desktop,
				'tablet'  => $tablet,
				'mobile'  => min( $mobile, $tablet - 1 ),
			);
		}

		/**
		 * Build the rule set for one block: desktop, then the tablet and mobile
		 * overrides inside `max-width` queries.
		 *
		 * At mobile width both queries match, so the mobile tier only has to
		 * carry what differs from tablet — that is why the editor stores diffs.
		 *
		 * @param string $selector    CSS selector for this block.
		 * @param array  $styles      Saved `blockStyles`.
		 * @param array  $breakpoints Resolved breakpoints.
		 * @return string CSS.
		 */
		private function build_responsive_css( $selector, $styles, $breakpoints ) {
			if ( empty( $styles ) || ! is_array( $styles ) ) {
				return '';
			}

			// Blocks saved before the responsive tiers stored a flat property bag.
			$has_tiers = isset( $styles['base'] ) || isset( $styles['tablet'] ) || isset( $styles['mobile'] );
			$base      = $has_tiers
				? ( isset( $styles['base'] ) ? $styles['base'] : array() )
				: $styles;

			$css = $this->rule( $selector, $base );

			if ( $has_tiers ) {
				foreach ( array( 'tablet', 'mobile' ) as $tier ) {
					if ( empty( $styles[ $tier ] ) || ! is_array( $styles[ $tier ] ) ) {
						continue;
					}

					$rule = $this->rule( $selector, $styles[ $tier ] );

					if ( '' !== $rule ) {
						$css .= sprintf(
							'@media (max-width:%1$dpx){%2$s}',
							$breakpoints[ $tier ],
							$rule
						);
					}
				}
			}

			return $css;
		}

		/**
		 * One CSS rule from a property bag, dropping anything that does not
		 * look like one of our own custom properties.
		 *
		 * @param string $selector   CSS selector.
		 * @param array  $properties Property map.
		 * @return string CSS rule, or an empty string when nothing survived.
		 */
		private function rule( $selector, $properties ) {
			if ( empty( $properties ) || ! is_array( $properties ) ) {
				return '';
			}

			$declarations = array();

			foreach ( $properties as $property => $value ) {
				$property = $this->sanitize_css_property( $property );
				$value    = $this->sanitize_css_value( $value );

				if ( '' === $property || '' === $value ) {
					continue;
				}

				$declarations[] = $property . ':' . $value;
			}

			if ( empty( $declarations ) ) {
				return '';
			}

			return $selector . '{' . implode( ';', $declarations ) . '}';
		}

		/**
		 * Only the block's own custom properties are allowed through, which
		 * rules out arbitrary declarations arriving via post content.
		 *
		 * @param string $property Property name.
		 * @return string The property, or an empty string when rejected.
		 */
		private function sanitize_css_property( $property ) {
			if ( ! is_string( $property ) ) {
				return '';
			}

			return preg_match( '/^--rmp--[a-z0-9-]+$/i', $property ) ? $property : '';
		}

		/**
		 * Values come from post content, so they are treated as untrusted: no
		 * markup, nothing that can close the declaration or the rule, and no
		 * script-bearing url() or expression().
		 *
		 * @param mixed $value Raw value.
		 * @return string Safe value, or an empty string when rejected.
		 */
		private function sanitize_css_value( $value ) {
			if ( is_bool( $value ) || is_null( $value ) || is_array( $value ) ) {
				return '';
			}

			$value = wp_strip_all_tags( (string) $value );
			$value = str_replace( array( ';', '{', '}', '<', '>', '\\', '"' ), '', $value );
			$value = trim( $value );

			if ( '' === $value || strlen( $value ) > 500 ) {
				return '';
			}

			if ( preg_match( '/(expression\s*\(|behaviou?r\s*:|javascript\s*:|@import|<\/)/i', $value ) ) {
				return '';
			}

			// url( … ) is legitimate for the panel background image; run the URL
			// itself through WordPress' own validator.
			if ( preg_match( '/^url\((.*)\)$/i', $value, $matches ) ) {
				$url = esc_url_raw( trim( $matches[1], " '" ) );
				return $url ? 'url(' . $url . ')' : '';
			}

			return $value;
		}

		/**
		 * A small, breakpoint-aware rule set that paints the desktop layout
		 * before the runtime has added `.rmp-desktop-mode`.
		 *
		 * Without it a wide viewport shows the hamburger and the off-canvas
		 * panel for as long as it takes the view script to run. The full desktop
		 * styling still comes from the stylesheet; this is only the part that
		 * would otherwise flash.
		 *
		 * @param string $unique_id  Sanitised block id.
		 * @param int    $breakpoint Desktop breakpoint in px.
		 * @return string CSS.
		 */
		private function desktop_fallback_css( $unique_id, $breakpoint ) {
			if ( ! $breakpoint ) {
				return '';
			}

			$selector = '.rmp-block-navigator-' . $unique_id;

			return sprintf(
				'@media (min-width:%1$dpx){' .
					'%2$s .rmp-block-menu-trigger{display:none !important;}' .
					'%2$s .rmp-block-container{' .
						'position:static !important;height:auto !important;width:auto !important;' .
						'max-width:none !important;min-width:0 !important;overflow:visible !important;' .
						'visibility:visible !important;opacity:1 !important;z-index:auto !important;' .
						'left:auto !important;right:auto !important;top:auto !important;bottom:auto !important;' .
						'background:transparent !important;padding:0 !important;transition:none !important;' .
						'display:flex !important;align-items:center !important;' .
					'}' .
					'%2$s .wp-block-rmp-menu-items{' .
						'display:flex !important;flex-direction:row !important;flex-wrap:nowrap !important;' .
						'margin:0 !important;padding:0 !important;list-style:none !important;' .
					'}' .
					'%2$s .wp-block-rmp-menu-items > .wp-block-navigation-item{' .
						'flex:0 0 auto !important;position:relative !important;min-height:auto !important;' .
					'}' .
					'%2$s .wp-block-rmp-menu-items .wp-block-navigation__submenu-container{' .
						'display:none;position:absolute !important;' .
					'}' .
				'}',
				$breakpoint,
				$selector
			);
		}

		/**
		 * Print a block's CSS once per block instance.
		 *
		 * @param string $style_id   Handle for the generated stylesheet.
		 * @param string $css        CSS to print.
		 * @param array  $attributes Block attributes, passed to the filter.
		 */
		private function print_block_css( $style_id, $css, $attributes ) {
			if ( '' === $css ) {
				return;
			}

			/**
			 * Filter whether a block prints its generated CSS.
			 *
			 * Themes that compile the block styles themselves can turn this off.
			 *
			 * @since 4.8.0
			 *
			 * @param bool  $print      Whether to print the CSS.
			 * @param array $attributes Block attributes.
			 */
			if ( ! apply_filters( 'rmp_block_render_head_css', true, $attributes ) ) {
				return;
			}

			if ( wp_style_is( $style_id, 'enqueued' ) ) {
				return;
			}

			$this->render_inline_css( $css, $style_id, true );
		}

		/**
		 * Register, enqueue and — when the head has already been printed —
		 * immediately output an inline stylesheet.
		 *
		 * @param string $css        CSS for this block instance.
		 * @param string $style_id   Unique handle.
		 * @param bool   $in_content Whether the block is rendering inside content.
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
