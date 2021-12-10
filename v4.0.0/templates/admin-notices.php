<?php
/**
 * This is admin notice template file.
 *
 * @since 4.1.0
 */
?>

<div class="notice-responsive-menu notice error is-dismissible">
	<div class="notice-responsive-menu-logo">
		<img src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>" width="60" height="60" alt="logo" />
	</div>

	<div class="notice-responsive-menu-message">
		<h4 style="font-weight: 700;"> <?php esc_html_e( 'Welcome to Responsive Menu', 'responsive-menu' ); ?></h4>
		<p><?php esc_html_e( 'Upgrade to the pro version to get feature updates, premium support and unlimited access to the menu settings.', 'responsive-menu' ); ?></p>
	</div>

	<div class="notice-responsive-menu-action">
		<a target="_blank" rel="noopener" href="https://responsive.menu/pricing/" data-toggle="tab">
			<span class="dashicons dashicons-update-alt"></span>
			<?php esc_html_e( 'Upgrade To Pro', 'responsive-menu' ); ?>
		</a>
	</div>
</div>
