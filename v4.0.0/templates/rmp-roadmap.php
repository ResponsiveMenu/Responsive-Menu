<?php
/**
 * Class Displays the message What's Next.
 *
 * @since      4.1.5
 *
 * @package    responsive_menu_pro
 */

class RMNextUpdateMessage {

	/**
	 * Main Construct Function
	 *
	 * Adds the notice check to init and then check to display message
	 *
	 * @since 4.1.5
	 */
	function __construct() {

		if ( isset( $_GET['rm_next_update_notice_close'] ) && $_GET['rm_next_update_notice_close'] == 'close' ) {
			add_action( 'admin_init', array( $this, 'close_next_updates_message' ) );
		}
		if ( $this->trigger !== -1 && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'rmp_menu' ) {
			add_action( 'in_admin_footer', array( $this, 'display_next_updates_message' ) );
		}
	}


	/**
	 * Check if next update message closed
	 *
	 * @since 4.1.5
	 * @return int The amount of menus needed to display message
	 */
	public function close_next_updates_message() {
		add_user_meta( get_current_user_id(), 'rm_next_update_notice_close', true, true );

	}

	/**
	 * Displays the next update message for next updates
	 *
	 *
	 */
	public function display_next_updates_message() {
        if( get_user_meta( get_current_user_id(), 'rm_next_update_notice_close' ) ){
            return;
        }
        $update_url     = 'https://next.expresstech.io/responsive-menu/updates';
        $roadmap_url    = 'https://next.expresstech.io/responsive-menu';
        $ideas_url      = 'https://next.expresstech.io/responsive-menu#/ideas';
		$close_url      =  add_query_arg( 'rm_next_update_notice_close', 'close' );
        ?>
        <div class=' notice-responsive-menu-next notice-info is-dismissible'>
            <h3><?php echo esc_html__("What's Next", "responsive-menu-pro"); ?></h3>
            <p><?php echo esc_html__("This page shows what has been planned for the Responsive Menu plugin. You can vote on the roadmap cards or add your own idea.", "responsive-menu-pro"); ?></p>
            <p><strong><em><?php echo esc_html__("~ RM Team", "responsive-menu-pro"); ?></em></strong></p>
            <a target="_blank" href="<?php echo esc_url( $roadmap_url ) ;?>" class="rm-btn-link" rel="noopener"><?php echo esc_html__("Roadmap", "responsive-menu-pro"); ?></a>
            <span class="rm-btn-link-seprate">|</span>
            <a target="_blank" href="<?php echo esc_url( $update_url ) ;?>" class="rm-btn-link" rel="noopener"><?php echo esc_html__("Updates", "responsive-menu-pro"); ?></a>
            <span class="rm-btn-link-seprate">|</span>
            <a target="_blank" href="<?php echo esc_url( $ideas_url ) ;?>" class="rm-btn-link" rel="noopener"><?php echo esc_html__("Ideas", "responsive-menu-pro"); ?></a>
            <a href="<?php echo esc_url( $close_url ) ;?>" class="notice-dismiss"><span class="screen-reader-text"><?php echo esc_html__("Dismiss this notice.", "responsive-menu-pro"); ?></span></a>
        </div>
    <?php
    }

}

$rm_xext_update_message = new RMNextUpdateMessage();