<?php
/**
 * This is admin notice template file.
 * 
 * @version 4.0.0
 */
?>

<div class="notice-responsive-menu notice error is-dismissible">
    <div class="notice-responsive-menu-logo">
        <img src="<?php echo esc_url( RMP_PLUGIN_URL_V4 .'/assets/images/rmp-logo.png' ); ?>" width="60" height="60" alt="logo" />
    </div>

    <div class="notice-responsive-menu-message">
        <h4 style="font-weight: 700;"> <?php esc_html_e( 'Welcome to Responsive Menu Pro', 'responsive-menu-pro' ); ?></h4>
        <p><?php _e( 'Please activate your license to get feature updates, premium support and unlimited access to the menu setings.', 'responsive-menu-pro' ); ?></p>
    </div>

    <div class="notice-responsive-menu-action">
        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=rmp_menu&page=settings' ) ); ?>" data-toggle="tab">
            <span class="dashicons dashicons-update-alt"></span>
            <?php esc_html_e( 'Connect & Activate', 'responsive-menu-pro' );?>
        </a>
    </div>
</div>
