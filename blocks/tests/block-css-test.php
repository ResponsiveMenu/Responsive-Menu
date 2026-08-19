<?php
/**
 * Stand-alone check for the block's server-side CSS generation.
 *
 * There is no PHPUnit/WordPress harness in this repo, and the interesting parts
 * of `blocks/block.php` — the responsive tiering and the CSS sanitiser — are
 * pure functions of their input. So this stubs the handful of WordPress
 * functions the class touches and asserts against them directly:
 *
 *     php blocks/tests/block-css-test.php
 *
 * Exits non-zero on the first failing expectation.
 *
 * @package responsive-menu
 */

define( 'ABSPATH', __DIR__ );

// phpcs:disable Squiz.Commenting, Generic.Commenting, WordPress.NamingConventions

class WP_Block {
	public $context = array();
}

$GLOBALS['enqueued'] = array();
$GLOBALS['inline']   = array();

function add_action() {}
function add_filter() {}
function register_block_type() {}
function sanitize_html_class( $class ) {
	return preg_replace( '/[^A-Za-z0-9_-]/', '', $class );
}
function absint( $number ) {
	return abs( (int) $number );
}
function wp_strip_all_tags( $string ) {
	return trim( strip_tags( $string ) );
}
function esc_url_raw( $url ) {
	return preg_match( '#^(https?:|/)#i', $url ) ? $url : '';
}
function apply_filters( $tag, $value ) {
	return $value;
}
function wp_style_is( $handle ) {
	return in_array( $handle, $GLOBALS['enqueued'], true );
}
function wp_register_style() {}
function wp_enqueue_style( $handle ) {
	$GLOBALS['enqueued'][] = $handle;
}
function wp_add_inline_style( $handle, $css ) {
	$GLOBALS['inline'][ $handle ] = ( isset( $GLOBALS['inline'][ $handle ] ) ? $GLOBALS['inline'][ $handle ] : '' ) . $css;
}
function did_action() {
	return 0;
}
function wp_print_styles() {}

require dirname( __DIR__ ) . '/block.php';

$rmp        = RMPBlock::get_instance();
$reflection = new ReflectionClass( $rmp );

/**
 * Call a private method on the block class.
 */
$call = function ( $method, ...$args ) use ( $rmp, $reflection ) {
	$callable = $reflection->getMethod( $method );
	$callable->setAccessible( true );
	return $callable->invoke( $rmp, ...$args );
};

$failures = 0;

/**
 * Assert equality and report.
 */
function check( $name, $got, $want ) {
	global $failures;

	if ( $got === $want ) {
		echo "ok   $name\n";
		return;
	}

	++$failures;
	echo "FAIL $name\n  got:  " . var_export( $got, true ) . "\n  want: " . var_export( $want, true ) . "\n";
}

$breakpoints = array(
	'desktop' => 768,
	'tablet'  => 1024,
	'mobile'  => 767,
);

// ── Responsive tiering ──────────────────────────────────────────────────────
check(
	'desktop, tablet and mobile become one rule and two queries',
	$call(
		'build_responsive_css',
		'.sel',
		array(
			'base'   => array(
				'--rmp--menu-item-color'      => '#fff',
				'--rmp--menu-container-width' => '75%',
			),
			'tablet' => array( '--rmp--menu-container-width' => '90%' ),
			'mobile' => array( '--rmp--menu-container-width' => '100%' ),
		),
		$breakpoints
	),
	'.sel{--rmp--menu-item-color:#fff;--rmp--menu-container-width:75%}'
	. '@media (max-width:1024px){.sel{--rmp--menu-container-width:90%}}'
	. '@media (max-width:767px){.sel{--rmp--menu-container-width:100%}}'
);

check(
	'a flat bag saved before the tiers still renders',
	$call( 'build_responsive_css', '.sel', array( '--rmp--menu-item-color' => '#000' ), $breakpoints ),
	'.sel{--rmp--menu-item-color:#000}'
);

check(
	'a tier that overrides nothing emits no query',
	$call(
		'build_responsive_css',
		'.sel',
		array(
			'base'   => array( '--rmp--menu-item-color' => '#000' ),
			'tablet' => array(),
			'mobile' => array(),
		),
		$breakpoints
	),
	'.sel{--rmp--menu-item-color:#000}'
);

// ── Sanitising ──────────────────────────────────────────────────────────────
check( 'a foreign property is rejected', $call( 'sanitize_css_property', 'color' ), '' );
check( 'a property carrying a breakout is rejected', $call( 'sanitize_css_property', '--rmp--x;}body{display:none' ), '' );
check( 'our own property is kept', $call( 'sanitize_css_property', '--rmp--menu-item-color' ), '--rmp--menu-item-color' );

check( 'a value cannot close the rule', $call( 'sanitize_css_value', 'red;}body{background:blue' ), 'redbodybackground:blue' );
check( 'expression() is dropped', $call( 'sanitize_css_value', 'expression(alert(1))' ), '' );
check( 'a javascript: url is dropped', $call( 'sanitize_css_value', 'url(javascript:alert(1))' ), '' );
check( '@import is dropped', $call( 'sanitize_css_value', '@import "evil.css"' ), '' );
check( 'a real url survives', $call( 'sanitize_css_value', 'url(https://example.test/a.png)' ), 'url(https://example.test/a.png)' );
check( 'a colour survives', $call( 'sanitize_css_value', 'rgba(0, 0, 0, 0.5)' ), 'rgba(0, 0, 0, 0.5)' );
check( 'booleans are dropped', $call( 'sanitize_css_value', true ), '' );
check( 'arrays are dropped', $call( 'sanitize_css_value', array( 'a' ) ), '' );
check( 'absurdly long values are dropped', $call( 'sanitize_css_value', str_repeat( 'a', 501 ) ), '' );

$evil = $call(
	'build_responsive_css',
	'.sel',
	array(
		'base' => array(
			'--rmp--menu-item-color' => 'red;}body{display:none',
			'background'             => 'url(javascript:alert(1))',
			'--rmp--evil'            => 'expression(alert(1))',
		),
	),
	$breakpoints
);
check( 'nothing dangerous survives a fully hostile bag', (bool) preg_match( '/(javascript|expression|\}body)/i', $evil ), false );

// ── Breakpoints ─────────────────────────────────────────────────────────────
check(
	'mobile is clamped below tablet',
	$call( 'resolve_breakpoints', array( 'breakpoint' => 900, 'tabletBreakpoint' => 800, 'mobileBreakpoint' => 900 ) ),
	array( 'desktop' => 900, 'tablet' => 800, 'mobile' => 799 )
);
check( 'defaults are used when nothing is set', $call( 'resolve_breakpoints', array() ), $breakpoints );

// ── Render callbacks ────────────────────────────────────────────────────────
check(
	'the menu render callback returns its content untouched',
	$rmp->render_block( array( 'id' => 'abc', 'breakpoint' => 768, 'blockStyles' => array( 'base' => array( '--rmp--menu-item-color' => '#fff' ) ) ), '<nav></nav>' ),
	'<nav></nav>'
);
check( 'it enqueues exactly one stylesheet', count( $GLOBALS['enqueued'] ), 1 );
check( 'it emits the anti-flash desktop query', (bool) strpos( $GLOBALS['inline']['rmp-block-navigator-abc'], '@media (min-width:768px)' ), true );

$block          = new WP_Block();
$block->context = array(
	'rmp/tabletBreakpoint' => 900,
	'rmp/mobileBreakpoint' => 500,
);
$rmp->render_menu_items_block(
	array(
		'id'          => 'xyz',
		'blockStyles' => array(
			'base'   => array( '--rmp--menu-item-color' => '#fff' ),
			'mobile' => array( '--rmp--menu-item-color' => '#000' ),
		),
	),
	'<ul></ul>',
	$block
);
check( 'the list takes its breakpoints from block context', (bool) strpos( $GLOBALS['inline']['rmp-block-menu-items-xyz'], '@media (max-width:500px)' ), true );

$before = count( $GLOBALS['enqueued'] );
check( 'a block with no id passes its content through', $rmp->render_block( array(), '<nav/>' ), '<nav/>' );
check( 'a block with no id emits no CSS', count( $GLOBALS['enqueued'] ), $before );

// ── Allowing core blocks as menu items ──────────────────────────────────────
check( 'a supported core block gains the parent', $rmp->allow_core_blocks_in_menu_items( array(), 'core/navigation-link' ), array( 'parent' => array( 'rmp/menu-items' ) ) );
check( 'an existing parent list is appended to', $rmp->allow_core_blocks_in_menu_items( array( 'parent' => array( 'core/navigation' ) ), 'core/navigation-link' ), array( 'parent' => array( 'core/navigation', 'rmp/menu-items' ) ) );
check( 'the filter is idempotent', $rmp->allow_core_blocks_in_menu_items( array( 'parent' => array( 'rmp/menu-items' ) ), 'core/navigation-link' ), array( 'parent' => array( 'rmp/menu-items' ) ) );
check( 'every other block is left alone', $rmp->allow_core_blocks_in_menu_items( array(), 'core/paragraph' ), array() );

echo $failures ? "\n$failures failure(s)\n" : "\nall passed\n";
exit( $failures ? 1 : 0 );
