<?php
/**
 * Admin class.
 * This is core class which is responsible for admin functionality.
 *
 * @version 4.0.0
 * @author  Expresstech System
 *
 * @package responsive-menu
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;
use RMP\Features\Inc\RMP_Menu;
use RMP\Features\Inc\Theme_Manager;
use RMP\Features\Inc\Option_Manager;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin
 */
class Admin {

	use Singleton;

	/**
	 * Instance of Option Manager class.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      object.
	 */
	protected static $option_manager;

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
		add_action( 'wp_ajax_rmp_save_global_settings', array( $this, 'save_menu_global_settings' ) );
		add_action( 'wp_ajax_rmp_create_new_menu', array( $this, 'create_new_menu' ) );
		add_action( 'wp_ajax_rmp_export_menu', array( $this, 'rmp_export_menu' ) );
		add_action( 'wp_ajax_rmp_import_menu', array( $this, 'rmp_import_menu' ) );

		add_shortcode( 'rmp_menu', array( $this, 'register_menu_shortcode' ) );
		add_shortcode( 'responsive_menu', array( $this, 'responsive_menu_shortcode' ) );
		add_action( 'init', array( $this, 'rmp_menu_cpt' ), 0 );

		add_filter( 'post_row_actions', array( $this, 'rmp_menu_row_actions' ), 10, 2 );
		add_filter( 'get_edit_post_link', array( $this, 'rmp_edit_post_link' ), 10, 2 );

		add_filter( 'manage_rmp_menu_posts_columns', array( $this, 'set_custom_edit_menu_columns' ) );
		add_action( 'manage_rmp_menu_posts_custom_column', array( $this, 'add_custom_columns' ), 10, 2 );
		add_action( 'admin_footer', array( $this, 'add_new_menu_widget' ) );
		add_action( 'admin_menu', array( $this, 'rmp_register_submenu_page' ) );
		add_action( 'admin_menu', array( $this, 'remove_default_add_cpt_page' ) );
		add_action( 'rmp_create_new_menu', array( $this, 'set_global_options' ), 10, 0 );
	}

	/**
	 * Function to save the global settings of setting page.
	 *
	 * @return json
	 */
	public function save_menu_global_settings() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( __( 'You can\'t edit global settings!', 'responsive-menu' ) );
		}

		$options   = array();
		$form_data = isset( $_POST['form'] ) ? rm_sanitize_rec_array( wp_unslash( $_POST['form'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		wp_parse_str( $form_data, $options );

		foreach ( $options as $key => $value ) {
			$options[ $key ] = sanitize_text_field( $value );
		}

		update_option( 'rmp_global_setting_options', $options );

		/**
		 * Fires after global settings is saved.
		 *
		 * @since 4.0.0
		 *
		 * @param array $option List of global settings.
		 */
		do_action( 'rmp_save_global_settings', $options );

		wp_send_json_success( __( 'Saved', 'responsive-menu' ) );
	}

	/**
	 * Function to create a new theme.
	 *
	 * @since   4.0.0
	 *
	 * @return json
	 */
	public function create_new_menu() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( array( 'message' => __( 'You can not create menu !', 'responsive-menu' ) ) );
		}

		$menu_name = isset( $_POST['menu_name'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_name'] ) ) : '';
		if ( empty( $menu_name ) ) {
			wp_send_json_error( array( 'message' => __( 'Enter the Menu name !', 'responsive-menu' ) ) );
		}

		$menu_to_use = isset( $_POST['menu_to_use'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_to_use'] ) ) : '';
		if ( empty( $menu_to_use ) ) {
			wp_send_json_error( array( 'message' => __( 'Select menu to use !', 'responsive-menu' ) ) );
		}

		$menu_to_hide = isset( $_POST['menu_to_hide'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_to_hide'] ) ) : '';

		$menu_theme = isset( $_POST['menu_theme'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_theme'] ) ) : '';

		$theme_type = isset( $_POST['theme_type'] ) ? sanitize_text_field( wp_unslash( $_POST['theme_type'] ) ) : '';

		$menu_show_on = isset( $_POST['menu_show_on'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_show_on'] ) ) : '';

		$menu_show_on_pages = array();
		if ( ! empty( $_POST['menu_show_on_pages'] ) && is_array( $_POST['menu_show_on_pages'] ) ) {
			$menu_show_on_pages = rm_sanitize_rec_array( wp_unslash( $_POST['menu_show_on_pages'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		$theme_options = array();

		// Get appropriate theme as per theme type and theme name.
		if ( ! empty( $theme_type ) && 'downloaded' == $theme_type ) {
			$theme_manager = Theme_Manager::get_instance();
			$theme_options = $theme_manager->get_available_theme_settings( $menu_theme );
		} elseif ( ! empty( $theme_type ) && 'template' == $theme_type ) {
			$theme_manager = Theme_Manager::get_instance();
			$theme_options = $theme_manager->get_saved_theme_options( $menu_theme );
		} else {
			$theme_options = rmp_get_default_options();
		}

		// Create menu as post with rmp_menu cpt.
		$new_menu = array(
			'post_title'  => wp_strip_all_tags( $menu_name ),
			'post_author' => get_current_user_id(),
			'post_status' => 'publish',
			'post_type'   => 'rmp_menu',
		);

		$menu_id = wp_insert_post( $new_menu );

		$new_options = array(
			'menu_name'          => $menu_name,
			'menu_to_use'        => $menu_to_use,
			'menu_theme'         => $menu_theme,
			'theme_type'         => $theme_type,
			'menu_display_on'    => $menu_show_on,
			'menu_show_on_pages' => $menu_show_on_pages,
			'menu_id'            => $menu_id,
			'menu_to_hide'       => $menu_to_hide,
		);

		$new_options = array_merge( $theme_options, $new_options );

		if ( ! empty( $menu_id ) ) {
			update_post_meta( $menu_id, 'rmp_menu_meta', $new_options );

			/**
			 * Fires when menu is created and options is saved.
			 *
			 * @param int $menu_id Menu ID.
			 */
			do_action( 'rmp_create_new_menu', $menu_id );

			wp_send_json_success(
				array(
					'message'       => __( 'Menu is created successfully', 'responsive-menu' ),
					'customize_url' => sprintf(
						'%spost.php?post=%s&action=edit&editor=true',
						get_admin_url(),
						$menu_id
					),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Unable to create new Menu !', 'responsive-menu' ) ) );
		}
	}

	/**
	 * This function register the shortcode for menu.
	 *
	 * @since  4.0.0
	 *
	 * @param  Array $atts    Attributes List.
	 *
	 * @return HTML   $output  Menu contents.
	 */
	public function register_menu_shortcode( $attrs = array() ) {
		$attrs = shortcode_atts( array( 'id' => '' ), $attrs );

		$attrs = array_change_key_case( (array) $attrs, CASE_LOWER );

		// Check given id is valid.
		if ( empty( $attrs['id'] ) ) {
			return __( 'Please pass menu id as attribute.', 'responsive-menu' );
		}

		$menu_id = $attrs['id'];
		if ( 'publish' !== get_post_status( $menu_id ) ) {
			/* translators: %d: Menu id */
			return sprintf( __( 'Shortcode with menu id %d is not published.', 'responsive-menu' ), esc_html( $menu_id ) );
		}

		// Check shortcode option is activated or not.
		$option_manager = Option_Manager::get_instance();
		$option         = $option_manager->get_option( $menu_id, 'menu_display_on' );

		if ( 'shortcode' !== $option ) {
			return __( 'Shortcode deactivated', 'responsive-menu' );
		}

		ob_start();

		$menu = new RMP_Menu( $menu_id );
		$menu->build_menu();

		return ob_get_clean();
	}

	/**
	 * This function register the shortcode for responsive_menu.
	 *
	 * @since  4.1.7
	 *
	 * @return HTML   $output  Menu contents.
	 */
	public function responsive_menu_shortcode() {

		// Check shortcode option is activated or not.
		$options  = Option_Manager::get_instance();
		$menu_ids = get_all_rmp_menu_ids();

		if ( ! empty( $menu_ids ) ) {
			foreach ( $menu_ids as $menu_id ) {

				$menu_show_on = $options->get_option( $menu_id, 'menu_display_on' );
				if ( ! empty( $menu_show_on ) && 'shortcode' !== $menu_show_on ) {
					continue;
				}

				ob_start();
				$menu = new RMP_Menu( $menu_id );
				$menu->build_menu();
				return ob_get_clean();
			}
		} else {
			return __( 'Shortcode deactivated', 'responsive-menu' );
		}
	}

	/**
	 * Function to update the global options.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function set_global_options() {
		$global_settings = get_option( 'rmp_global_setting_options' );
		if ( empty( $global_settings ) ) {
			$default_options = rmp_global_default_setting_options();
			update_option( 'rmp_global_setting_options', $default_options );
		}
	}

	/**
	 * Add sub menu pages in responsive menu admin.
	 *
	 * @since   4.0.0
	 */
	public function rmp_register_submenu_page() {
		add_submenu_page(
			'edit.php?post_type=rmp_menu',
			__( 'Settings', 'responsive-menu' ),
			__( 'Settings', 'responsive-menu' ),
			'manage_options',
			'settings',
			array( $this, 'rmp_global_settings_page' )
		);

		add_submenu_page(
			'edit.php?post_type=rmp_menu',
			__( 'Themes', 'responsive-menu' ),
			__( 'Themes', 'responsive-menu' ),
			'manage_options',
			'themes',
			array( $this, 'rmp_theme_admin_page' )
		);
	}

	/**
	 * Add template to the themes page.
	 *
	 * @since   4.0.0
	 */
	public function rmp_theme_admin_page() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-themes.php';
	}

	/**
	 * Add template to the setting page.
	 *
	 * @since   4.0.0
	 *
	 * @return void
	 */
	public function rmp_global_settings_page() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-settings.php';
	}

	/**
	 * Remove create new menu default link of rmp_menu post type.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function remove_default_add_cpt_page() {
		remove_submenu_page( 'edit.php?post_type=rmp_menu', 'post-new.php?post_type=rmp_menu' );
	}

	/**
	 * Function to add the new menu wizard template.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function add_new_menu_widget() {
		$screen = get_current_screen();
		if ( 'edit-rmp_menu' === $screen->id ) {
			include_once RMP_PLUGIN_PATH_V4 . '/templates/new-menu-wizard.php';
		}
	}

	/**
	 * Function to change the edit label and url.
	 *
	 * @since 4.0.0
	 *
	 * @param array  $actions List of post row actions.
	 * @param Object $post Post object
	 *
	 * @return array $actions
	 */
	public function rmp_menu_row_actions( $actions, $post ) {
		if ( 'rmp_menu' == $post->post_type ) {
			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="Edit">%s</a>',
				esc_url( get_edit_post_link( $post->ID ) ),
				__( 'Customize', 'responsive-menu' )
			);
		}

		return $actions;
	}

	/**
	 * Function to add the custom column.
	 *
	 * @since 4.0.0
	 *
	 * @param array $columns List of columns.
	 *
	 * @return array $columns Edited columns list.
	 */
	public function set_custom_edit_menu_columns( $columns ) {
		unset( $columns['date'] );
		$columns['shortcode_place'] = __( 'Shortcode', 'responsive-menu' );
		$columns['actions']         = __( 'Actions', 'responsive-menu' );
		$columns['date']            = __( 'Date', 'responsive-menu' );

		return $columns;
	}

	/**
	 * Function to change the edit url of post type rmp_menu
	 *
	 * @since 4.0.0
	 *
	 * @param string $url     Post edit URL.
	 * @param int    $post_id Post ID
	 *
	 * @return string $url    Edited post url URL
	 */
	public function rmp_edit_post_link( $url, $post_id ) {
		if ( 'rmp_menu' === get_post_type() && current_user_can( 'edit_post', $post_id ) ) {
			$url = get_admin_url() . 'post.php?post=' . $post_id . '&action=edit&editor=true';
		}

		return $url;
	}

	/**
	 * Function to add the data to the custom columns for the rmp_menu post type.
	 *
	 * @since 4.0.0
	 *
	 * @param string $column  Column Name
	 * @param int    $post_id Post ID
	 *
	 * @return void
	 */
	public function add_custom_columns( $column, $post_id ) {
		$option_manager = Option_Manager::get_instance();

		switch ( $column ) {

			case 'actions':
				?><a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>" class="button" aria-label="Customize"><?php esc_html_e( 'Customize', 'responsive-menu' ); ?></a>
				<?php
				break;
			case 'shortcode_place':
				$option = $option_manager->get_option( $post_id, 'menu_display_on' );
				if ( 'shortcode' === $option ) {
					?>
					<code>[rmp_menu id="<?php echo esc_attr( $post_id ); ?>"]</code>
					<?php
				} else {
					esc_html_e( 'Shortcode deactivated', 'responsive-menu' );
				}
				break;
		}
	}

	/**
	 * Register rmp_menu custom post type.
	 *
	 * @since 4.0.0
	 */
	public function rmp_menu_cpt() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$labels = array(
			'name'               => __( 'Responsive Menu', 'responsive-menu' ),
			'singular_name'      => __( 'Responsive Menu', 'responsive-menu' ),
			'menu_name'          => __( 'Responsive Menu', 'responsive-menu' ),
			'parent_item_colon'  => __( 'Parent Menu', 'responsive-menu' ),
			'all_items'          => __( 'Menus', 'responsive-menu' ),
			'view_item'          => __( 'View Menu', 'responsive-menu' ),
			'add_new_item'       => __( 'Add New Menu', 'responsive-menu' ),
			'add_new'            => __( 'Create New Menu', 'responsive-menu' ),
			'edit_item'          => __( 'Edit Menu', 'responsive-menu' ),
			'update_item'        => __( 'Update Menu', 'responsive-menu' ),
			'search_items'       => __( 'Search Menu', 'responsive-menu' ),
			'not_found'          => __( 'Not Found', 'responsive-menu' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'responsive-menu' ),
		);

		$args = array(
			'label'               => __( 'Responsive Menu', 'responsive-menu' ),
			'description'         => __( 'Responsive Menu', 'responsive-menu' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author' ),
			'public'              => false,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'has_archive'         => false,
			'can_export'          => false,
			'exclude_from_search' => true,
			'taxonomies'          => array(),
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'menu_icon'           => RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png',
		);

		register_post_type( 'rmp_menu', $args );

		/**
		 * This action will be useful when need hooks after cpt register.
		 *
		 * @param CPT rmp_menu
		 */
		do_action( 'rmp_after_cpt_registered', 'rmp_menu' );
	}

	/**
	 * Function to export the menu
	 *
	 * @since   4.0.0
	 *
	 * @return json
	 */
	public function rmp_export_menu() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		$menu_id = isset( $_POST['menu_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_id'] ) ) : '';

		if ( empty( $menu_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Select menu !', 'responsive-menu' ) ) );
		}

		if ( ! current_user_can( 'edit_post', $menu_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You can not export menu !', 'responsive-menu' ) ) );
		}

		$option_manager = Option_Manager::get_instance();
		$option         = $option_manager->get_options( $menu_id );

		wp_send_json_success( wp_json_encode( $option ) );
	}

	/**
	 * Function to import the menu settings.
	 *
	 * @since   4.0.0
	 *
	 * @return json
	 */
	public function rmp_import_menu() {
		check_ajax_referer( 'rmp_nonce', 'ajax_nonce' );

		if ( empty( $_FILES['file']['name'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Please add file !', 'responsive-menu' ) ) );
		}

		if ( empty( $_FILES['file']['type'] ) || 'application/json' != $_FILES['file']['type'] ) {
			wp_send_json_error( array( 'message' => __( 'Please add json file !', 'responsive-menu' ) ) );
		}

		$menu_id = isset( $_POST['menu_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_id'] ) ) : '';
		if ( empty( $menu_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Select menu !', 'responsive-menu' ) ) );
		}

		if ( ! current_user_can( 'edit_post', $menu_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You can not import menu !', 'responsive-menu' ) ) );
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		$file_contents  = isset( $_FILES['file']['tmp_name'] ) ? $wp_filesystem->get_contents( wp_unslash( $_FILES['file']['tmp_name'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$import_options = json_decode( $file_contents, true );

		$option_manager = Option_Manager::get_instance();
		$exist_option   = $option_manager->get_options( $menu_id );

		// Some required options replced in imported settings with existing menu settings.
		$import_options['menu_name']             = $exist_option['menu_name'];
		$import_options['theme_type']            = 'default';
		$import_options['menu_theme']            = null;
		$import_options['menu_to_use']           = $exist_option['menu_to_use'];
		$import_options['menu_to_use_in_mobile'] = $exist_option['menu_to_use_in_mobile'];

		update_post_meta( $menu_id, 'rmp_menu_meta', $import_options );
		/**
		 * Fires when menu is imported.
		 *
		 * @since 4.0.0
		 *
		 * @param int $menu_id
		 */
		do_action( 'rmp_import_menu', $menu_id );

		wp_send_json_success( array( 'message' => __( 'Menu settings imported successfully!', 'responsive-menu' ) ) );
	}
}
