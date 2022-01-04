<?php
/**
 * Plugin manifest class.
 *
 * @package responsive-menu
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Elementor\Elementor_Manager;
use RMP\Features\Inc\Traits\Singleton;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		// Load plugin classes.
		Admin::get_instance();
		Assets::get_instance();
		Editor::get_instance();
		Editor_Manager::get_instance();
		Preview::get_instance();
		Control_Manager::get_instance();
		Theme_Manager::get_instance();
		Option_Manager::get_instance();
		Style_Manager::get_instance();
		UI_Manager::get_instance();
		RMP_Migration::get_instance();
		Elementor_Manager::get_instance();

		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_action( 'plugins_loaded', array( $this, 'rmp_load_plugin_text_domain' ) );
		add_action( 'admin_notices', array( $this, 'rmp_deactivate_paid_version_notice' ) );

		//Hiding upgrade to pro notice for v3 users
		global $wpdb;
		$table_name = $wpdb->prefix . 'responsive_menu';
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
		if ( ! $wpdb->get_var( $query ) == $table_name ) {
			add_action( 'admin_notices', array( $this, 'rmp_upgrade_pro_admin_notice' ) );
		}
		add_action( 'plugin_action_links_' . plugin_basename( RMP_PLUGIN_FILE ), array( $this, 'rmp_upgrade_pro_plugin_link' ) );
		add_action( 'wp_ajax_rmp_upgrade_admin_notice_dismiss', array( $this, 'rmp_upgrade_pro_notice_dismiss' ) );
		add_action( 'admin_notices', array( $this, 'no_menu_admin_notice' ) );

		// Check current config and environment support wp_body_open or not.
		if ( $this->has_support( 'wp_body_open' ) ) {
			add_action( 'wp_body_open', array( $this, 'menu_render_on_frontend' ) );
		} else {
			add_action( 'wp_footer', array( $this, 'menu_render_on_frontend' ) );
		}
	}

	/**
	 * Function to show the admin notice when no menu exist.
	 *
	 * @since 4.1.0
	 *
	 * @return void
	 */
	public function no_menu_admin_notice() {

		// Check post type.
		$post_type = get_post_type();
		if ( empty( $post_type ) && ! empty( $_GET['post_type'] ) ) {
			$post_type = intval( $_GET['post_type'] );
		}

		if ( 'rmp_menu' !== $post_type || ! empty( $_GET['page'] ) ) {
			return;
		}

		// Count all post which are in list except trash.
		$post_count = 0;
		foreach ( wp_count_posts( 'rmp_menu' ) as $status => $count ) {
			if ( 'trash' == $status ) {
				continue;
			}

			$post_count += $count;
		}

		if ( $post_count >= 1 ) {
			return;
		} ?>
		<div class="notice notice-error">
			<p>
				<?php
				/* translators: %s: HTML tag */
				echo sprintf( esc_html__( 'Responsive menu list is empty. Create a menu by clicking the %1$sCreate New Menu%2$s button. For more details visit ', 'responsive-menu' ), '<b>', '</b>' );
				?>
				<a target="_blank" rel="noopener" href="<?php echo esc_url( 'https://responsive.menu/knowledgebase/responsive-menu-4-0-overview/' ); ?>"> <?php esc_html_e( 'Documentation', 'responsive-menu' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Add plugin upgrade link.
	 *
	 * Add a link to the settings page on the responsive menu page.
	 *
	 * @param  array $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	public function rmp_upgrade_pro_plugin_link( $links ) {
		$links = array_merge(
			$links,
			array( '<a class="responsive-menu-license-upgrade-link" target="_blank" rel="noopener" href="https://responsive.menu/pricing/">' . esc_html__( 'Upgrade', 'responsive-menu' ) . '</a>' )
		);

		return $links;
	}

	/**
	 * Function to add the admin notice to upgrade as pro.
	 *
	 * @version 4.1.0
	 */
	public function rmp_upgrade_pro_admin_notice() {
		$post_type = get_post_type();
		if ( empty( $post_type ) && ! empty( $_GET['post_type'] ) ) {
			$post_type = intval( $_GET['post_type'] );
		}

		if ( 'rmp_menu' !== $post_type ) {
			return;
		}

		$user_id = get_current_user_id();
		if ( ! empty( get_user_meta( $user_id, 'rmp_upgrade_pro_admin_notice' ) ) ) {
			return;
		}

		include_once RMP_PLUGIN_PATH_V4 . '/templates/admin-notices.php';
	}

	/**
	 * Function to hide the admin notice permanent.
	 */
	public function rmp_upgrade_pro_notice_dismiss() {
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'rmp_upgrade_pro_admin_notice', true );
	}

	/**
	 * Function to show the admin notice if plugin deactivate.
	 *
	 * @return void
	 */
	public function rmp_deactivate_paid_version_notice() {
		if ( get_transient( 'og-admin-notice-activation' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'Responsive Menu has been deactivated', 'responsive-menu' ); ?></p>
			</div>
			<?php
			delete_transient( 'og-admin-notice-activation-pro' );
		}
	}

	/**
	 * Load plugin text domain.
	 *
	 * @version 4.0.0
	 *
	 * @return void
	 */
	public function rmp_load_plugin_text_domain() {
		load_plugin_textdomain( 'responsive-menu', false, RMP_PLUGIN_DIR_NAME . '/v4.0.0/languages' );
	}

	/**
	 * Function to render the nenu on frontend.
	 *
	 * @version 4.0.0
	 */
	public function menu_render_on_frontend() {
		$option_manager = Option_Manager::get_instance();
		$menu_ids       = get_all_rmp_menu_ids();

		if ( empty( $menu_ids ) ) {
			return;
		}

		foreach ( $menu_ids as $menu_id ) {
			$menu_show_on = $option_manager->get_option( $menu_id, 'menu_display_on' );

			if ( ! empty( $menu_show_on ) && 'shortcode' === $menu_show_on ) {
				continue;
			}

			$menu = new \RMP\Features\Inc\RMP_Menu( $menu_id );
			$menu->build_menu();
		}
	}

	/**
	 * Check support of wp_body_open for plugins and themes.
	 *
	 * @since 4.0.0
	 *
	 * @param string $hook Name of hook.
	 * @return boolean
	 */
	public function has_support( $hook ) {

		// Check wp footer option is enabled.
		$option_manager = Option_Manager::get_instance();
		if ( 'wp_body_open' === $hook && 'on' === $option_manager->get_global_option( 'rmp_wp_footer_hook' ) ) {
			return false;
		}

		// Check wp core support wp_body_open hook or not.
		if ( ! has_action( $hook ) ) {
			return false;
		}

		// If is_plugin_active function not exist then add plugin.php file from core.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// List of unsupported themes and plugins.
		$unsupported_extensions = array(
			'oxygen/functions.php',
		);

		foreach ( $unsupported_extensions as $extension ) {
			if ( is_plugin_active( $extension ) ) {
				return false;
			}
		}

		return true;
	}
}
