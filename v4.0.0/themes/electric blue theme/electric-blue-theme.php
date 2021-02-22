<?php
/**
 * Electric_Blue_Theme Class.
 *
 * @since      2.0.0
 * @author     Expresstech System
 * @package    responsive_menu_pro
 */

namespace RMP\Features\Theme;

use RMP\Features\Inc\Traits\Singleton;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Constant as menu theme version.
 */
if ( ! defined( 'EBT_SAT_VERSION' ) ) {
    define( 'EBT_SAT_VERSION', '2.0.0' );
}

/**
 * Class Electric_Blue_Theme
 * @since 2.0.0
 */
class Electric_Blue_Theme {

	use Singleton;

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
        add_filter( 'get_available_theme_settings', [ $this, 'update_resources' ],10, 2 );
	}

    /**
     * Function to update the dynamic optins and resource for theme.
     * 
     * @since 2.0.0
     * @return array
     */
    public function update_resources( $options, $theme_name ) {

        if ( 'Electric blue theme' == $theme_name ) {
            $options['menu_background_image'] = RMP_PLUGIN_URL_V4 . '/themes/electric blue theme/blue-background.png';
            $options['menu_title_image'] = RMP_PLUGIN_URL_V4 . '/themes/electric blue theme/person.png';
        }

        return $options;
    }

}

//Initiate the theme object.
Electric_Blue_Theme::get_instance();
