<?php
/**
 * Elementor Manager class will handle the elementor related features of responsive menu.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @author     Expresstech System
 * @since      4.0.2
 */

namespace RMP\Features\Inc\Elementor;

use RMP\Features\Inc\Traits\Singleton;
use RMP\Features\Inc\Elementor\Widgets\RMP_Widget;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Class Elementor_Manager
 *
 * @since 4.0.2
 */
class Elementor_Manager {

	use Singleton;

	/**
	 * Minimum Elementor Version
	 *
	 * @since 4.0.2
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 4.0.2
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_action( 'plugins_loaded', array( $this, 'elementor_init' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'elementor_enqueue_scripts' ) );
	}

	/**
	 * To enqueue scripts and styles in elementor admin.
	 *
	 * @param string $hook_suffix Admin page name.
	 *
	 * @return void
	 */
	public function elementor_enqueue_scripts( $hook_suffix ) {
		wp_enqueue_script(
			'rmp_elementor_scripts',
			RMP_PLUGIN_URL_V4 . '/assets/admin/js/rmp-elementor.js',
			array( 'jquery' ),
			RMP_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 4.0.2
	 * @access public
	 */
	public function elementor_init() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 4.0.2
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		return sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> to be installed and activated.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Responsive menu elementor widget',
			'Elementor'
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 4.0.2
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		return sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Responsive menu elementor widget',
			'Elementor',
			self::MINIMUM_ELEMENTOR_VERSION
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 4.0.2
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		return sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Responsive menu elementor widget',
			'Elementor',
			self::MINIMUM_ELEMENTOR_VERSION
		);
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 4.0.2
	 * @access private
	 */
	private function include_widgets_files() {
		require_once 'widgets/class-rmp-widget.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 4.0.2
	 * @access public
	 */
	public function register_widgets() {

		// It's now safe to include Widgets files.
		$this->include_widgets_files();

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RMP_Widget() );
	}
}
