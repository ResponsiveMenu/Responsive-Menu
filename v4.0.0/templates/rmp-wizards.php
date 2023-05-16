<?php
/**
 * This is file contain the new menu creation settings markups.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

// If theme list is cached then access it.
$cached_data      = get_transient( 'rmp_theme_api_response' );
$pro_plugin_url   = 'https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile';
$rmp_browse_class = '';
if ( empty( $cached_data ) ) {
	$rmp_browse_class = 'rmp-call-theme-api-button';
}

?>
<!--- This is icon picker wizard markups -->
<section class="rmp-dialog-overlay rmp-menu-icons-dialog" style="display:none">
	<div class="rmp-dialog-backdrop"></div>
	<div class="rmp-dialog-wrap wp-clearfix">
		<div class="rmp-dialog-header">
			<div class="title">
				<img alt="logo" width="34" height="34" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>" />
				<span> <?php esc_html_e( 'Select Icon', 'responsive-menu' ); ?> </span>
			</div>
			<button class="close dashicons dashicons-no"></button>
		</div>
		<div class="rmp-dialog-contents wp-clearfix">
			<div id="tabs" class="tabs icon-tabs">
				<ul class="nav-tab-wrapper">
					<li><a class="nav-tab-active nav-tab" href="#dashicons"><?php esc_html_e( 'Dashicons', 'responsive-menu' ); ?></a></li>
					<li>
						<a class="nav-tab" href="#material-icon">
							<?php esc_html_e( 'Material Icons (mdi)', 'responsive-menu' ); ?>
							<span class="upgrade-tooltip"> <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </span>
						</a>
					</li>
					<li>
						<a class="nav-tab" href="#fas">
							<?php esc_html_e( 'FontAwesome Solid (fas)', 'responsive-menu' ); ?>
							<span class="upgrade-tooltip"> <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </span>
						</a>
					</li>
					<li>
						<a class="nav-tab" href="#fab">
							<?php esc_html_e( 'FontAwesome Brand (fab)', 'responsive-menu' ); ?>
							<span class="upgrade-tooltip"> <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </span>
						</a>
					</li>
					<li>
						<a class="nav-tab" href="#far">
							<?php esc_html_e( 'FontAwesome Regular (far)', 'responsive-menu' ); ?>
							<span class="upgrade-tooltip"> <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </span>
						</a>
					</li>
					<li>
						<a class="nav-tab" href="#glyphicons">
							<?php esc_html_e( 'GlyphIcon', 'responsive-menu' ); ?>
							<span class="upgrade-tooltip"> <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </span>
						</a>
					</li>
				</ul>
				<div class="rmp-icon-tab-contents">
					<div id="dashicons" style="padding: 20px;">
						<p> <input type="text" class="medium-text" id="rmp-icon-search" placeholder="Search icons"/> </p>
						<?php rmp_dashicon_selector(); ?>
					</div>
					<div id="fab">
						<div class="upgrade-options">
							<div class="upgrade-notes">
								<p><?php esc_html_e( 'FontAwesome brand icons are not available in free version.', 'responsive-menu' ); ?> <br/> <?php esc_html_e( 'Upgrade now to use', 'responsive-menu' ); ?></p>
								<a target="_blank" rel="noopener" href="<?php echo esc_url( $pro_plugin_url ); ?>" class="button"> <?php esc_html_e( 'Upgrade to Pro', 'responsive-menu' ); ?></a>
							</div>
						</div>
					</div>
					<div id="fas">
						<div class="upgrade-options">
							<div class="upgrade-notes">
								<p><?php esc_html_e( 'FontAwesome solid icons are not available in free version.', 'responsive-menu' ); ?> <br/> <?php esc_html_e( 'Upgrade now to use', 'responsive-menu' ); ?></p>
								<a target="_blank" rel="noopener" href="<?php echo esc_url( $pro_plugin_url ); ?>" class="button"><?php esc_html_e( 'Upgrade to Pro', 'responsive-menu' ); ?></a>
							</div>
						</div>
					</div>
					<div id="glyphicons">
						<div class="upgrade-options">
							<div class="upgrade-notes">
								<p><?php esc_html_e( 'The glyphicons are not available in free version.', 'responsive-menu' ); ?> <br/> <?php esc_html_e( 'Upgrade now to use', 'responsive-menu' ); ?></p>
								<a target="_blank" rel="noopener" href="<?php echo esc_url( $pro_plugin_url ); ?>" class="button"><?php esc_html_e( 'Upgrade to Pro', 'responsive-menu' ); ?></a>
							</div>
						</div>
					</div>
					<div id="material-icon">
						<div class="upgrade-options">
							<div class="upgrade-notes">
								<p><?php esc_html_e( 'Material icons are not available in free version.', 'responsive-menu' ); ?> <br/> <?php esc_html_e( 'Upgrade now to use', 'responsive-menu' ); ?></p>
								<a target="_blank" rel="noopener" href="<?php echo esc_url( $pro_plugin_url ); ?>" class="button"><?php esc_html_e( 'Upgrade to Pro', 'responsive-menu' ); ?></a>
							</div>
						</div>
					</div>
					<div id="far">
						<div class="upgrade-options">
							<div class="upgrade-notes">
								<p><?php esc_html_e( 'FontAwesome regular icons are not available in free version.', 'responsive-menu' ); ?> <br/> <?php esc_html_e( 'Upgrade now to use', 'responsive-menu' ); ?></p>
								<a target="_blank" rel="noopener" href="<?php echo esc_url( $pro_plugin_url ); ?>" class="button"><?php esc_html_e( 'Upgrade to Pro', 'responsive-menu' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="rmp-dialog-footer">
			<a class="button button-secondary button-large" id="rmp-icon-dialog-clear"><?php esc_html_e( 'Clear', 'responsive-menu' ); ?></a>
			<a class="button button-primary button-large" id="rmp-icon-dialog-select"><?php esc_html_e( 'Select', 'responsive-menu' ); ?></a>
		</div>
	</div>
</section>

<!--- This is theme saving form wizard markups -->
<section id="rmp-menu-save-theme-wizard" class="rmp-dialog-overlay" style="display:none">
	<div class="rmp-dialog-backdrop"></div>
	<div class="rmp-dialog-wrap wp-clearfix">
		<span class="close dashicons dashicons-no"></span>
		<div class="rmp-dialog-contents wp-clearfix">
			<span class="rmp-menu-library-blank-icon  fas fa-save"></span>
			<h3 class="rmp-menu-library-title"><?php esc_html_e( 'Save menu options as theme template', 'responsive-menu' ); ?></h3>
			<p class="rmp-menu-library-message"><?php esc_html_e( 'Your designs will be available for export and reuse on any menu or website.', 'responsive-menu' ); ?></p>
			<div class="rmp-save-menu-input">
				<input type="text" id="rmp-save-theme-name" name="rmp_theme_name" placeholder="Enter Template Name"/>
				<button type="button" class="button save-button" id="rmp-save-theme"><?php esc_html_e( 'Save Theme', 'responsive-menu' ); ?></button>
			</div>
		</div>
	</div>
</section>

<!-- Theme wizard in customizer page. -->
<section id="rmp-new-menu-wizard" class="rmp-dialog-overlay rmp-new-menu-wizard" style="display:none">
	<div class="rmp-dialog-backdrop"></div>
	<div class="rmp-dialog-wrap wp-clearfix">
		<div class="rmp-dialog-header">
			<div class="title">
				<img alt="logo" width="34" height="34" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>" />
				<span> <?php esc_html_e( 'Use Theme', 'responsive-menu' ); ?> </span>
			</div>

			<button class="close dashicons dashicons-no"></button>
		</div>
		<div class="rmp-dialog-contents wp-clearfix tabs" id="tabs" >
			<div id="select-themes" class="rmp-new-menu-themes">
				<div id="tabs" class="tabs">
					<ul class="nav-tab-wrapper">
						<li><a class="nav-tab rmp-v-divider" href="#tabs-1"><?php esc_html_e( 'Installed Themes', 'responsive-menu' ); ?></a></li>
						<li><a class="nav-tab rmp-v-divider <?php echo esc_attr( $rmp_browse_class ); ?>" href="#tabs-2"><?php esc_html_e( 'Marketplace', 'responsive-menu' ); ?></a></li>
						<li><a class="nav-tab" href="#tabs-3"><?php esc_html_e( 'Saved Templates', 'responsive-menu' ); ?></a></li>
						<li style="float:right;"><button id="rmp-upload-new-theme" class="button btn-import-theme"><?php esc_html_e( 'Import', 'responsive-menu' ); ?></button></li>
					</ul>

					<!-- This is menu theme upload section -->
					<div id="rmp-menu-library-import" class="rmp-theme-upload-container hide" >
						<p><?php esc_html_e( 'If you have a menu theme in a .zip format, you can upload here.', 'responsive-menu' ); ?></p>
						<form method="post" enctype="multipart/form-data" id="rmp-menu-theme-upload-form" class="wp-upload-form">
							<label class="screen-reader-text" for="themezip">Upload zip</label>
							<input type="file" accept=".zip" id="rmp_menu_theme_zip" name="rmp_menu_theme_zip" />
							<button id="rmp-theme-upload" class="button" type="button"> Upload Theme </button>
						</form>
					</div>

					<div id="tabs-2" class="rmp-themes">
						<ul class="rmp_theme_grids">
							<?php
							if ( ! empty( $cached_data ) ) {
								$theme_manager->get_themes_from_theme_store( true );
							} else {
								?>
								<div class="rmp-page-loader" style="display:flex;">
								<img class="rmp-loader-image" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>"/>
								<h3 class="rmp-loader-message">
									<?php esc_html_e( 'Just a moment', 'responsive-menu' ); ?>
									<br/>
									<?php esc_html_e( 'Getting data from the server.', 'responsive-menu' ); ?>
								</h3>
								</div>
								<?php
							}
							?>
						</ul>
					</div>

					<div id="tabs-1" class="rmp-themes">
						<?php $theme_manager->get_available_themes( true ); ?>
					</div>

					<div id="tabs-3" class="rmp-themes">
						<?php $theme_manager->rmp_saves_theme_template_list( true ); ?>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
