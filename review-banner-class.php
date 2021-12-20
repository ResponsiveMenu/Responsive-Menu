<?php

/**
 * Class that handles displaying of review notice
 *
 * @since 4.1.3
 */
class RM_Review_Message {


	/**
	 * Variable to hold how many results needed to show message
	 *
	 * @since 4.1.3
	 */
	public $trigger = -1;

	/**
	 * Main Construct Function
	 *
	 * Adds the notice check to init and then check to display message
	 *
	 * @since 4.1.3
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Adds check message to admin_init hook
	 *
	 * @since 4.1.3
	 */
	public function add_hooks() {
		add_action( 'admin_init', array( $this, 'check_message_display' ) );
	}

	/**
	 * Checks if message should be displayed
	 *
	 * @since 4.1.3
	 */
	public function check_message_display() {
		$this->admin_notice_check();
		$this->trigger = $this->check_message_trigger();
		if ( -1 !== $this->trigger ) {
			$amount = $this->check_results_amount();
			if ( $amount >= $this->trigger ) {
				add_action( 'admin_notices', array( $this, 'display_admin_message' ) );
			}
		}
	}

	/**
	 * Retrieves what the next trigger value is
	 *
	 * @since 4.1.3
	 * @return int The amount of menus needed to display message
	 */
	public function check_message_trigger() {
		$trigger = get_option( 'rm_review_message_trigger' );
		if ( empty( $trigger ) || is_null( $trigger ) ) {
			add_option( 'rm_review_message_trigger', 1 );
			return 1;
		}
		return intval( $trigger );
	}

	/**
	 * Checks the amount of Menus
	 *
	 * @return int The amount of menus
	 */
	public function check_results_amount() {
		global $wpdb;
		$amount = $wpdb->get_var(
			'SELECT COUNT(*) FROM `' . $wpdb->prefix . "posts`
        WHERE post_type = 'rmp_menu'"
		);
		return $amount;
	}

	/**
	 * Displays the message
	 *
	 * Displays the message asking for review
	 */
	public function display_admin_message() {
		?>
		<div class='updated'><br />
		<?php
		/* translators: %d: Result count */
		echo sprintf( esc_html__( 'Greetings! I just noticed that you have created %d Menus. That is awesome! Could you please help me out by giving this plugin a 5-star rating on WordPress? This will help us by helping other users discover this plugin.', 'responsive-menu' ), esc_html( $this->check_results_amount() ) );
		?>
		<br/><strong><em>~ <?php esc_html_e( 'RM Team', 'responsive-menu' ); ?></em></strong><br/><br/>
		<a target="_blank" rel="noopener" href="https://wordpress.org/support/plugin/responsive-menu/reviews/#new-topic-0" class="button-primary"><?php esc_html_e( 'Yeah, you deserve it!', 'responsive-menu' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( 'rm_review_notice_check', 'already_did' ) ); ?>" class="button-secondary"><?php esc_html_e( 'I already did!', 'responsive-menu' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( 'rm_review_notice_check', 'remove_message' ) ); ?>" class="button-secondary"><?php esc_html_e( 'No, this plugin is not good enough', 'responsive-menu' ); ?></a>
		<br /><br /></div>
		<?php
	}

	/**
	 * Checks if a link in the admin message has been clicked
	 *
	 * @since 4.1.3
	 */
	public function admin_notice_check() {
		if ( isset( $_GET['rm_review_notice_check'] ) && 'remove_message' === $_GET['rm_review_notice_check'] ) {
			$this->trigger  = $this->check_message_trigger();
			$update_trigger = -1;
			if ( -1 === $this->trigger ) {
				exit;
			} elseif ( 1 === $this->trigger ) {
				$update_trigger = 5;
			} elseif ( 5 === $this->trigger ) {
				$update_trigger = 10;
			} elseif ( 10 === $this->trigger ) {
				$update_trigger = -1;
			}
			update_option( 'rm_review_message_trigger', $update_trigger );
		}
		if ( isset( $_GET['rm_review_notice_check'] ) && 'already_did' === $_GET['rm_review_notice_check'] ) {
			update_option( 'rm_review_message_trigger', -1 );
		}
	}
}

$rm_review_message = new RM_Review_Message();
