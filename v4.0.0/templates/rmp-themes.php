<?php
/**
 * This is file contain the themes.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

use RMP\Features\Inc\Theme_Manager;

$theme_manager = Theme_Manager::get_instance();

?>
<div class="wrap rmp-container">

	<!-- Theme page title -->
	<h1 class="wp-heading-inline"> <?php esc_html_e( 'Themes', 'responsive-menu' ); ?> </h1>

	<!-- New theme upload button -->
	<a href="javascript:void(0)" id="rmp-upload-new-theme">
		<?php esc_html_e( 'Upload theme', 'responsive-menu' ); ?>
	</a>

	<!-- Theme drop and upload location -->
	<div id="rmp-menu-library-import" class="hide">
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="rmp-menu-library-import-form" method="post" enctype="multipart/form-data">

			<div class="rmp-page-loader">
				<img class="rmp-loader-image" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>"/>
				<h3 class="rmp-loader-message"><?php esc_html_e( 'Uploading zip file...', 'responsive-menu' ); ?> </h3>
			</div>

			<input type="hidden" id="rmp_theme_upload_nonce" name="rmp_theme_upload_nonce" value="<?php echo esc_attr( wp_create_nonce( 'rmp_nonce' ) ); ?>"/>
			<a class="cancel">
				<span class="dashicons dashicons-no-alt "></span>
			</a>

			<span class="rmp-menu-library-blank-icon  dashicons dashicons-cloud-upload"></span>

			<h3 class="rmp-menu-library-title"> <?php esc_html_e( 'Import Menu Theme To Your Library', 'responsive-menu' ); ?> </h3>

			<p class="rmp-menu-library-message"> <?php esc_html_e( 'Drop zip files here or click to upload.', 'responsive-menu' ); ?>  </p>

			<span class="progress-text"></span>

			<label class="button upload-button"><?php esc_html_e( 'Select Files', 'responsive-menu' ); ?> </label>

			<input type='hidden' name='action' value='rmp_upload_theme_file'>
		</form>
	</div>

	<!--- Theme grids --->
	<div class="rmp-theme-page" >
		<?php
			$themes = $theme_manager->all_theme_combine_list();
		if ( empty( $themes ) ) {
			// Empty message if theme doesn't exist.
			?>
				<div class="rmp-theme-page-empty">
					<span class="rmp-menu-library-blank-icon  fas fa-save"></span>
					<h3 class="rmp-menu-library-title"> <?php esc_html_e( 'You have no theme here', 'responsive-menu' ); ?> </h3>
				</div>
				<?php
					$themes = array();
		}
		?>

		<ul class="rmp_theme_grids">
			<?php
			foreach ( $themes as $theme ) {
				$preview_url = RMP_PLUGIN_URL_V4 . '/assets/images/no-preview.jpeg';
				if ( ! empty( $theme['preview_url'] ) ) {
					$preview_url = $theme['preview_url'];
				}
				?>
				<li class="rmp_theme_grid_item">
					<div class="rmp-item-card">
						<!--- Theme preview image -->
						<figure class="rmp-item-card_image">
							<img src="<?php echo esc_url( $preview_url ); ?>" alt="" loading="lazy"/>
						</figure>

						<!--- Theme titlw -->
						<div class="rmp-item-card_contents">
							<h4> <?php echo esc_html( $theme['name'] ); ?> </h4>
						</div>

						<!-- Theme actions -->
						<div class="rmp-item-card_action">
							<button class="button rmp-theme-delete" data-theme="<?php echo esc_attr( $theme['name'] ); ?>" data-theme-type="<?php echo esc_attr( $theme['type'] ); ?> ">
							<?php esc_html_e( 'Delete', 'responsive-menu' ); ?>
							</button>
						</div>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
</div>
