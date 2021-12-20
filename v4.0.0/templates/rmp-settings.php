<?php
/**
 * This is file contain the admin setting part.
 *
 * @since 4.0.0
 *
 * @package responsive_menu_pro
 */

$global_settings = get_option( 'rmp_global_setting_options' );

if ( empty( $global_settings ) ) {
	$global_settings = rmp_global_default_setting_options();
	update_option( 'rmp_global_setting_options', $global_settings );
}

$rmp_custom_css = '';
if ( ! empty( $global_settings['rmp_custom_css'] ) ) {
	$rmp_custom_css = $global_settings['rmp_custom_css'];
}

$wp_header = 'none';
if ( ! empty( $global_settings['menu_adjust_for_wp_admin_bar'] ) ) {
	$wp_header = $global_settings['menu_adjust_for_wp_admin_bar'];
}

?>
<div class="wrap rmp-container rmp-setting-page">
	<h1 class="wp-heading-inline"> <?php esc_html_e( 'Responsive Menu', 'responsive-menu' ); ?> </h1>
	<form method="post" enctype="multipart/form-data" id="rmp-global-settings">

		<div id="rmp-setting-tabs">
			<ul class="nav-tab-wrapper">
				<li><a class="nav-tab" href="#rmp-settings-advanced"><?php esc_html_e( 'Advance', 'responsive-menu' ); ?></a></li>
				<li><a class="nav-tab" href="#rmp-settings-style"><?php esc_html_e( 'Style', 'responsive-menu' ); ?></a></li>
				<li><a class="nav-tab" href="#rmp-settings-import-and-export"><?php esc_html_e( 'Import/Export', 'responsive-menu' ); ?></a></li>
			</ul>

			<div id="rmp-settings-advanced" >
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"> <?php esc_html_e( 'Adjust for WP Admin Bar', 'responsive-menu' ); ?></th>
							<td>
								<label>
									<p>
										<select name="menu_adjust_for_wp_admin_bar" value="on" id="rmp-menu_adjust-wp-admin-bar">
											<option value="none" <?php echo esc_attr( 'none' === $wp_header ? 'selected' : '' ); ?>><?php esc_attr_e( 'None', 'responsive-menu' ); ?></option>
											<option value="adjust" <?php echo esc_attr( 'adjust' === $wp_header ? 'selected' : '' ); ?>><?php esc_attr_e( 'Adjust', 'responsive-menu' ); ?></option>
											<option value="hide" <?php echo esc_attr( 'hide' === $wp_header ? 'selected' : '' ); ?>><?php esc_attr_e( 'Hide', 'responsive-menu' ); ?></option>
										</select>
										<label for="rmp-menu_adjust-wp-admin-bar" class="description">
											<?php esc_html_e( 'If you use the WP Admin bar when logged in, this will help you to adjust the admin bar.', 'responsive-menu' ); ?>
										</label>
									</p>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"> <?php esc_html_e( 'Active Dark Mode', 'responsive-menu' ); ?></th>
							<td>
								<fieldset>
									<p>
										<input type="checkbox" name="rmp_dark_mode" value="on" id="rmp-dark-mode" <?php echo esc_attr( is_rmp_option_checked( 'on', $global_settings, 'rmp_dark_mode' ) ); ?> >
										<label for="rmp-dark-mode" class="description">
											<?php esc_html_e( 'Enable dark mode for menu editor page.', 'responsive-menu' ); ?>
										</label>
									</p>
								</fieldset>
							</td>
						</tr>

						<tr>
							<th scope="row"> <?php esc_html_e( 'Use wp_footer hook', 'responsive-menu' ); ?></th>
							<td>
								<fieldset>
									<p>
										<input type="checkbox" name="rmp_wp_footer_hook" value="on" id="rmp-wp-footer-hook" <?php echo esc_attr( is_rmp_option_checked( 'on', $global_settings, 'rmp_wp_footer_hook' ) ); ?> >
										<label for="rmp-wp-footer-hook" class="description">
											<?php esc_html_e( 'Enable this option if your theme does not support wp_body_open hook.', 'responsive-menu' ); ?>
										</label>
									</p>
								</fieldset>
							</td>
						</tr>

						<tr>
							<th scope="row"> <?php esc_html_e( 'Use external files', 'responsive-menu' ); ?></th>
							<td>
								<fieldset>
									<p>
										<input type="checkbox" name="rmp_external_files" value="on" id="rmp-use-external-files" <?php echo esc_attr( is_rmp_option_checked( 'on', $global_settings, 'rmp_external_files' ) ); ?> >
										<label for="rmp-use-external-files" class="description">
											<?php esc_html_e( 'Create external files for the CSS and JavaScript created by this plugin.', 'responsive-menu' ); ?>
										</label>
									</p>
								</fieldset>
							</td>
						</tr>

						<tr>
							<th scope="row"> <?php esc_html_e( 'Minify scripts', 'responsive-menu' ); ?> </th>
							<td>
								<fieldset>
									<p>
										<input type="checkbox" name="rmp_minify_scripts" value="on" id="rmp-use-minify-scripts" <?php echo esc_attr( is_rmp_option_checked( 'on', $global_settings, 'rmp_minify_scripts' ) ); ?>>
										<label for="rmp-use-minify-scripts" class="description">
											<?php esc_html_e( 'Minify the CSS and JavaScript created by this plugin.', 'responsive-menu' ); ?>
										</label>
									</p>
								</fieldset>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php esc_html_e( 'Place scripts in footer', 'responsive-menu' ); ?>  </th>
							<td>
								<fieldset>
									<p>
										<input type="checkbox" name="rmp_scripts_in_footer" value="on" id="rmp-footer-scripts" <?php echo esc_attr( is_rmp_option_checked( 'on', $global_settings, 'rmp_scripts_in_footer' ) ); ?>>
										<label for="rmp-footer-scripts" class="description">
											<?php esc_html_e( 'Place the JavaScript created by this plugin in the footer.', 'responsive-menu' ); ?>
										</label>
									</p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"> <?php esc_html_e( 'Remove Dashicons', 'responsive-menu' ); ?> </th>
							<td>
								<fieldset>
									<p>
										<input type="checkbox" name="rmp_remove_dashicons" value="on" id="rmp-remove-dashicons" <?php echo esc_attr( is_rmp_option_checked( 'on', $global_settings, 'rmp_remove_dashicons' ) ); ?>>
										<label for="rmp-remove-dashicons" class="description">
											<?php esc_html_e( 'Stop this plugin\'s dashicons scripts from being load at frontend.', 'responsive-menu' ); ?>
										</label>
									</p>
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>
				<button class="button button-primary button-large rmp-save-global-settings-button" type="button">
					<?php esc_html_e( 'Save Settings', 'responsive-menu' ); ?>
				</button>
				<span class="spinner"></span>
			</div>

			<div id="rmp-settings-style" >
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"> <?php esc_html_e( 'Custom CSS', 'responsive-menu' ); ?> </th>
							<td>
								<label for="rmp-custom-css" class="description">
									<?php esc_html_e( 'You can place any Custom CSS you want here. Very useful if you want to make minor tweaks to some margins, paddings or colours or even for whole new layouts or designs.', 'responsive-menu' ); ?>
								</label>
								<p>
									<textarea class="large-text code" id="rmp-custom-css" name="rmp_custom_css"><?php echo esc_attr( $rmp_custom_css ); ?></textarea>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				<button class="button button-primary button-large rmp-save-global-settings-button" type="button">
					<?php esc_html_e( 'Save Settings', 'responsive-menu' ); ?>
				</button>
				<span class="spinner"></span>
			</div>

			<div id="rmp-settings-import-and-export" >
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"> <?php esc_html_e( 'Export Menu', 'responsive-menu' ); ?> </th>
							<td>
								<select id="rmp_export_menu_list">
									<?php
									$nav_menus = rmp_get_all_menus();
									foreach ( $nav_menus as $menu_id => $menu_title ) {
										?>
										<option value="<?php echo esc_attr( $menu_id ); ?>"><?php echo esc_html( $menu_title ); ?></option>
										<?php
									}
									?>
								</select>
								<button type="button" class="button button-primary button-large" id="rmp-export-menu-button">
									<?php esc_html_e( 'Export', 'responsive-menu' ); ?>
								</button>
								<p class="description">
									<?php esc_html_e( 'This will create an export file for selected menu transferring to other sites.', 'responsive-menu' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"> <?php esc_html_e( 'Import Menu', 'responsive-menu' ); ?> </th>
							<td>

								<div class="rmp-import-file-container">
									<input type="file" id="rmp_input_import_file" />
								</div>

								<div class=rmp-menu-import-options>
									<select id="rmp_import_menu_list">
										<?php
										foreach ( $nav_menus as $menu_id => $menu_title ) {
											?>
											<option value="<?php echo esc_attr( $menu_id ); ?>"><?php echo esc_html( $menu_title ); ?></option>
											<?php
										}
										?>
									</select>

									<button type="button" class="button button-primary button-large" id="rmp-import-menu-button">
										<?php esc_html_e( 'Import', 'responsive-menu' ); ?>
									</button>
								</div>

								<p class="description">
									<?php esc_html_e( 'This will import settings in selected menu created via the export process above.', 'responsive-menu' ); ?>
								</p>

							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
