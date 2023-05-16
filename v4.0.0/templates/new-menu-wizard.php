<?php
/**
 * This is file contain the new menu creation settings markups.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

$theme_manager = RMP\Features\Inc\Theme_Manager::get_instance();

// If theme list is cached then access it.
$cached_data      = get_transient( 'rmp_theme_api_response' );
$rmp_browse_class = '';
if ( empty( $cached_data ) ) {
	$rmp_browse_class = 'rmp-call-theme-api-button';
}

// Check dark mode options.
$global_settings = get_option( 'rmp_global_setting_options' );
$classes         = '';
if ( ! empty( $global_settings['rmp_dark_mode'] ) ) {
	$classes = 'rmp-dark-mode';
}

$nav_menus = wp_get_nav_menus();
?>
<div class="<?php echo esc_attr( $classes ); ?>">
<section id="rmp-new-menu-wizard" class="rmp-dialog-overlay rmp-new-menu-wizard" style="display:none">
	<div class="rmp-dialog-backdrop"></div>
	<div class="rmp-dialog-wrap wp-clearfix <?php echo empty($nav_menus) ? 'rmp-menu-empty' : ''; ?>">

		<!-- This is new new wizard header -->
		<div class="rmp-dialog-header">
			<div class="title">
				<?php if ( empty( $nav_menus ) ) { ?>
					<img alt="logo" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-warning.png' ); ?>" />
					<span class="rm-text-primary"> <?php esc_html_e( 'WordPress menu missing', 'responsive-menu' ); ?> </span>
				<?php }else { ?>
					<img alt="logo" width="34" height="34" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>" />
					<span> <?php esc_html_e( 'Create New Menu', 'responsive-menu' ); ?> </span>
				<?php } ?>
			</div>
			<?php if ( ! empty( $nav_menus ) ) : ?>
			<nav class="rmp-new-menu-step-conatiner">
				<ul class="rmp-new-menu-steps">
					<li class="rmp-new-menu-step current">
						<?php esc_html_e( 'Select Themes', 'responsive-menu' ); ?>
					</li>
					<li class="rmp-new-menu-step">
						<?php esc_html_e( 'Menu Settings', 'responsive-menu' ); ?>
					</li>
				</ul>
			</nav>
			<?php endif; ?>
			<button class="close dashicons dashicons-no"></button>
		</div>

		<!-- This is menu create wizard setting sections. -->
		<div class="rmp-dialog-contents" >
			<div id="select-themes" class="rmp-new-menu-themes rmp-menu-section current">
				<div id="tabs" class="tabs">
					<?php if ( ! empty( $nav_menus ) ) : ?>
					<!-- This is theme type list -->
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

					<!-- This is theme list from stored -->
					<div id="tabs-2" class="rmp-themes">
						<ul class="rmp_theme_grids">
							<?php
							if ( ! empty( $cached_data ) ) {
								$theme_manager->get_themes_from_theme_store();
							} else {
								?>
								<div class="rmp-page-loader" style="display:flex;">
								<img class="rmp-loader-image" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>"/>
								<h3 class="rmp-loader-message">
									<?php esc_html_e( 'Just a moment <br/> Getting data from the server..', 'responsive-menu' ); ?>
								</h3>
								</div>
								<?php
							}
							?>
						</ul>
					</div>
					<?php endif; ?>

					<!-- This is available theme list. -->
					<div id="tabs-1" class="rmp-themes">
						<?php if ( ! empty( $nav_menus ) ) :
							$theme_manager->get_available_themes();
						else : ?>
							<div class="rmp-admin-warning-notice">
                                <h2><?php esc_html_e( 'Looks like your WordPress website do not have any menus yet!', 'responsive-menu' ); ?></h2>
                                <p><?php esc_html_e( 'Responsive menu plugin requires at least one WordPress menu.', 'responsive-menu' ); ?>
								<p><?php esc_html_e( 'Please create a new WordPress menu by using following button and try again.', 'responsive-menu' ); ?></p>
								<div class="rmp-btn-group">
                                	<a class="rmp-btn-primary" href="<?php echo esc_url( admin_url() . 'nav-menus.php' ); ?>"> <?php esc_html_e( 'Create WordPress Menu', 'responsive-menu' ); ?> </a>
                                	<a class="rmp-btn-secondary" rel="noopener" target="_blank" href="<?php echo esc_url( 'https://responsive.menu/knowledgebase/create-a-wordpress-menu/' ); ?>"> <?php esc_html_e( 'Read Documention', 'responsive-menu' ); ?> </a>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $nav_menus ) ) : ?>
					<!-- This is saved template themes. -->
					<div id="tabs-3" class="rmp-themes">
						<?php $theme_manager->rmp_saves_theme_template_list(); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>


			<div id="menu-settings" class="rmp-new-menu-elements rmp-menu-section">
				<div class="input-group">
					<div for="rmp-menu-name" class="input-label">
						<h4 class="input-label-title"> <?php esc_html_e( 'Name Your Menu', 'responsive-menu' ); ?> </h4>
						<p class="input-label-description">
							<?php esc_html_e( 'Please enter a descriptive name to identify this menu later', 'responsive-menu' ); ?>
						</p>
					</div>

					<div class="input-control">
						<input type="text"  name="menu-name" id="rmp-menu-name" required>
					</div>
				</div>

				<div class="input-group">
					<div for="rmp-menu-to-use" class="input-label">
						<h4 class="input-label-title"><?php esc_html_e( 'Link WordPress Menu', 'responsive-menu' ); ?></h4>
						<p class="input-label-description">
							<?php esc_html_e( 'Map with your existing WordPress menu.', 'responsive-menu' ); ?>
						</p>
					</div>

					<div class="input-control">
						<select name="menu-to-use" id="rmp-menu-to-use">
							<?php
							foreach ( $nav_menus as $nav_menu ) {
								?>
								<option value="<?php echo esc_attr( $nav_menu->term_id ); ?>"><?php echo esc_html( $nav_menu->name ); ?></option>
								<?php
							}
							?>
						</select>

						<?php
						if ( empty( $nav_menus ) ) {
							printf(
								'<p class="rmp-admin-notice">
                                    <span class="dashicons dashicons-warning"></span>
                                    <span>%s </span>
                                    <a href="%s"> %s </a>
                                </p>',
								esc_html__( 'Notice : You don\'t have any existing WordPress menus, please ', 'responsive-menu' ),
								esc_url( admin_url() . 'nav-menus.php' ),
								esc_html__( 'create wp menu', 'responsive-menu' )
							);
						}
						?>
					</div>
				</div>

				<div class="input-group">
					<div for="rmp-menu-name" class="input-label">
						<h4 class="input-label-title"><?php esc_html_e( 'Hide Theme Menu', 'responsive-menu' ); ?></h4>
						<p class="input-label-description">
							<?php esc_html_e( 'Add any valid css selector to hide the existing menu on your website.', 'responsive-menu' ); ?>
							<a href="https://responsive.menu/knowledgebase/hiding-original-wordpress-menu/" target="_blank" rel="noopener"> Know More </a>
						</p>
					</div>

					<div class="input-control">
						<input type="text" name="rmp-hide-menu" id="rmp-hide-menu" />
					</div>
				</div>

				<div class="rmp-input-control-wrapper input-group">

					<div class="rmp-input-control-label input-label">
						<h4 class="input-label-title">
							<span> <?php esc_html_e( 'Device Visibility', 'responsive-menu' ); ?> </span>
							<a target="_blank" rel="noopener" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </a>
						</h4>

						<p class="input-label-description">
							<?php esc_html_e( 'Select devices where you want to show this menu', 'responsive-menu' ); ?>
						</p>
					</div>

					<div class="input-control">
						<div class="device-icons-group">
							<div class="device-icon">
								<input disabled checked class="rmp-menu-display-device checkbox mobile" type="checkbox" rel="&#xf120"/>
								<label for="rmp-menu-display-device-mobile" title="mobile" >
								<span class="corner-icon">
										<i class="fas fa-check-circle" aria-hidden="true"></i>
									</span>
									<span class="device">
										<svg width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M7.5 5.625C7.5 5.625 7.49251 5.625 7.47754 5.625C7.28288 5.625 7.10319 5.57259 6.93848 5.46777C6.78874 5.34798 6.68392 5.20573 6.62402 5.04102C6.59408 4.98112 6.57161 4.92122 6.55664 4.86133C6.54167 4.80143 6.53418 4.74154 6.53418 4.68164C6.53418 4.68164 6.53418 4.67415 6.53418 4.65918C6.53418 4.62923 6.53418 4.59928 6.53418 4.56934C6.54915 4.53939 6.55664 4.50195 6.55664 4.45703V4.47949C6.57161 4.44954 6.5791 4.4196 6.5791 4.38965C6.59408 4.3597 6.60905 4.32975 6.62402 4.2998C6.639 4.26986 6.64648 4.23991 6.64648 4.20996C6.66146 4.18001 6.68392 4.15007 6.71387 4.12012C6.72884 4.10514 6.74382 4.08268 6.75879 4.05273C6.77376 4.02279 6.79622 4.00033 6.82617 3.98535C6.90104 3.89551 6.99837 3.82812 7.11816 3.7832C7.23796 3.73828 7.36523 3.71582 7.5 3.71582C7.52995 3.71582 7.5599 3.71582 7.58984 3.71582C7.61979 3.71582 7.64974 3.72331 7.67969 3.73828C7.70964 3.73828 7.73958 3.74577 7.76953 3.76074C7.81445 3.76074 7.8444 3.76823 7.85938 3.7832C7.88932 3.79818 7.91927 3.81315 7.94922 3.82812C7.97917 3.8431 8.00911 3.85807 8.03906 3.87305C8.06901 3.88802 8.09147 3.91048 8.10645 3.94043C8.13639 3.9554 8.15885 3.97038 8.17383 3.98535C8.20378 4.00033 8.22624 4.02279 8.24121 4.05273C8.25618 4.08268 8.27116 4.10514 8.28613 4.12012C8.31608 4.15007 8.33105 4.18001 8.33105 4.20996C8.34603 4.23991 8.361 4.26986 8.37598 4.2998C8.39095 4.32975 8.39844 4.3597 8.39844 4.38965C8.41341 4.4196 8.42839 4.44954 8.44336 4.47949C8.44336 4.50944 8.44336 4.53939 8.44336 4.56934C8.45833 4.59928 8.46582 4.62923 8.46582 4.65918C8.46582 4.73405 8.45833 4.80143 8.44336 4.86133C8.42839 4.92122 8.40592 4.98112 8.37598 5.04102C8.361 5.10091 8.33105 5.16081 8.28613 5.2207C8.25618 5.26562 8.21875 5.31055 8.17383 5.35547C8.08398 5.43034 7.97917 5.49772 7.85938 5.55762C7.75456 5.60254 7.63477 5.625 7.5 5.625ZM9.40918 16.1592C9.40918 15.9046 9.31185 15.6875 9.11719 15.5078C8.9375 15.3132 8.72038 15.2158 8.46582 15.2158H6.53418C6.27962 15.2158 6.05501 15.3132 5.86035 15.5078C5.68066 15.6875 5.59082 15.9046 5.59082 16.1592C5.59082 16.4287 5.68066 16.6608 5.86035 16.8555C6.05501 17.0352 6.27962 17.125 6.53418 17.125H8.46582C8.72038 17.125 8.9375 17.0352 9.11719 16.8555C9.31185 16.6608 9.40918 16.4287 9.40918 16.1592ZM14.2158 16.6533V4.1875C14.2158 3.25911 13.8864 2.47298 13.2275 1.8291C12.5687 1.17025 11.7751 0.84082 10.8467 0.84082H4.15332C3.22493 0.84082 2.43132 1.17025 1.77246 1.8291C1.11361 2.47298 0.78418 3.25911 0.78418 4.1875V16.6533C0.78418 17.5667 1.11361 18.3529 1.77246 19.0117C2.43132 19.6706 3.22493 20 4.15332 20H10.8467C11.7751 20 12.5687 19.6706 13.2275 19.0117C13.8864 18.3529 14.2158 17.5667 14.2158 16.6533ZM10.8467 2.75C11.251 2.75 11.5879 2.89225 11.8574 3.17676C12.1419 3.46126 12.2842 3.79818 12.2842 4.1875V16.6533C12.2842 17.0426 12.1419 17.3796 11.8574 17.6641C11.5879 17.9486 11.251 18.0908 10.8467 18.0908H4.15332C3.74902 18.0908 3.40462 17.9486 3.12012 17.6641C2.85059 17.3796 2.71582 17.0426 2.71582 16.6533V4.1875C2.71582 3.79818 2.85059 3.46126 3.12012 3.17676C3.40462 2.89225 3.74902 2.75 4.15332 2.75H10.8467Z" fill="#56606D"/>
										</svg>
									</span>
								</label>
								<span class="device-title"> Mobile </span>
							</div>

							<div class="device-icon">
								<input disabled checked class="rmp-menu-display-device checkbox tablet"  type="checkbox" rel="&#xf120"/>
								<label for="rmp-menu-display-device-tablet" title="tablet" >
									<span class="corner-icon">
										<i class="fas fa-check-circle" aria-hidden="true"></i>
									</span>
									<span class="device">
										<svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12.125 19H3.875C2.98698 19 2.22786 18.6849 1.59766 18.0547C0.981771 17.4245 0.673828 16.6725 0.673828 15.7988V3.875C0.673828 2.98698 0.981771 2.23503 1.59766 1.61914C2.22786 0.988932 2.98698 0.673828 3.875 0.673828H12.125C13.013 0.673828 13.765 0.988932 14.3809 1.61914C15.0111 2.23503 15.3262 2.98698 15.3262 3.875V15.7988C15.3262 16.6725 15.0111 17.4245 14.3809 18.0547C13.765 18.6849 13.013 19 12.125 19ZM3.875 2.5C3.5026 2.5 3.18034 2.63607 2.9082 2.9082C2.63607 3.18034 2.5 3.5026 2.5 3.875V15.7988C2.5 16.1712 2.63607 16.4935 2.9082 16.7656C3.18034 17.0378 3.5026 17.1738 3.875 17.1738H12.125C12.4974 17.1738 12.8197 17.0378 13.0918 16.7656C13.3639 16.4935 13.5 16.1712 13.5 15.7988V3.875C13.5 3.5026 13.3639 3.18034 13.0918 2.9082C12.8197 2.63607 12.4974 2.5 12.125 2.5H3.875ZM8.64453 15.9922C8.73047 15.9062 8.79492 15.806 8.83789 15.6914C8.89518 15.5768 8.92383 15.4622 8.92383 15.3477C8.92383 15.3333 8.92383 15.3262 8.92383 15.3262C8.92383 15.0827 8.83073 14.875 8.64453 14.7031C8.47266 14.5169 8.25781 14.4238 8 14.4238C7.74219 14.4238 7.52018 14.5169 7.33398 14.7031C7.16211 14.875 7.07617 15.0827 7.07617 15.3262C7.07617 15.584 7.16211 15.806 7.33398 15.9922C7.52018 16.1641 7.74219 16.25 8 16.25C8.12891 16.25 8.24349 16.2285 8.34375 16.1855C8.45833 16.1283 8.55859 16.0638 8.64453 15.9922Z" fill="#56606D"/>
										</svg>
									</span>
								</label>
								<span class="device-title"> <?php esc_html_e( 'Tablet', 'responsive-menu' ); ?> </span>
							</div>

							<div class="device-icon">
								<input type="hidden" name="menu[use_desktop_menu]" value="off"/>
								<input disabled  class="rmp-menu-display-device checkbox desktop"  type="checkbox" rel="&#xf120"/>
								<label for="rmp-menu-display-device-desktop" title="desktop" >
									<span class="corner-icon">
										<i class="fas fa-check-circle" aria-hidden="true"></i>
									</span>
									<span class="device">
										<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M15.9512 0.673828H4.04883C3.16081 0.673828 2.40169 0.988932 1.77148 1.61914C1.14128 2.23503 0.826172 2.98698 0.826172 3.875V12.125C0.826172 13.013 1.14128 13.7721 1.77148 14.4023C2.40169 15.0182 3.16081 15.3262 4.04883 15.3262H9.07617V17.1738H6.32617C6.08268 17.1738 5.86784 17.2669 5.68164 17.4531C5.50977 17.625 5.42383 17.8327 5.42383 18.0762C5.42383 18.334 5.50977 18.556 5.68164 18.7422C5.86784 18.9141 6.08268 19 6.32617 19H13.6738C13.9173 19 14.125 18.9141 14.2969 18.7422C14.4831 18.556 14.5762 18.334 14.5762 18.0762C14.5762 17.8327 14.4831 17.625 14.2969 17.4531C14.125 17.2669 13.9173 17.1738 13.6738 17.1738H10.9238V15.3262H15.9512C16.8392 15.3262 17.5983 15.0182 18.2285 14.4023C18.8587 13.7721 19.1738 13.013 19.1738 12.125V3.875C19.1738 2.98698 18.8587 2.23503 18.2285 1.61914C17.5983 0.988932 16.8392 0.673828 15.9512 0.673828ZM17.3262 12.125C17.3262 12.4974 17.1901 12.8197 16.918 13.0918C16.6602 13.3639 16.3379 13.5 15.9512 13.5H4.04883C3.66211 13.5 3.33268 13.3639 3.06055 13.0918C2.80273 12.8197 2.67383 12.4974 2.67383 12.125V3.875C2.67383 3.5026 2.80273 3.18034 3.06055 2.9082C3.33268 2.63607 3.66211 2.5 4.04883 2.5H15.9512C16.3379 2.5 16.6602 2.63607 16.918 2.9082C17.1901 3.18034 17.3262 3.5026 17.3262 3.875V12.125ZM7.76562 3.83203C7.83724 3.90365 7.88737 3.98242 7.91602 4.06836C7.95898 4.13997 7.98047 4.22591 7.98047 4.32617C7.98047 4.42643 7.95898 4.51953 7.91602 4.60547C7.88737 4.67708 7.83724 4.7487 7.76562 4.82031L5.01562 7.57031C4.95833 7.6276 4.88672 7.67057 4.80078 7.69922C4.72917 7.72786 4.64323 7.74219 4.54297 7.74219C4.35677 7.74219 4.19206 7.67773 4.04883 7.54883C3.91992 7.41992 3.85547 7.25521 3.85547 7.05469C3.85547 6.96875 3.86979 6.88997 3.89844 6.81836C3.94141 6.73242 3.99154 6.65365 4.04883 6.58203L6.79883 3.83203C6.85612 3.77474 6.92773 3.73177 7.01367 3.70312C7.09961 3.66016 7.19271 3.63867 7.29297 3.63867C7.37891 3.63867 7.46484 3.66016 7.55078 3.70312C7.63672 3.73177 7.70833 3.77474 7.76562 3.83203Z" fill="white"/>
										</svg>
									</span>
								</label>
								<span class="device-title"> <?php esc_html_e( 'Desktop', 'responsive-menu' ); ?> </span>
							</div>

						</div>
					</div>
				</div>

				<div class="input-group">
					<div for="rmp-menu-display-on-pages" class="input-label">
						<h4 class="input-label-title">
							<span> <?php esc_html_e( 'Display Condition', 'responsive-menu' ); ?></span>
							<a target="_blank" rel="noopener" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > SEMI-PRO </a>
						</h4>

						<p class="input-label-description">
							<?php esc_html_e( 'Select specific pages where you want to show this menu.', 'responsive-menu' ); ?>
						</p>
					</div>

					<div class="input-control">
						<select name="rmp-menu-display-on" class="rmp-menu-display-option">
							<option  value="all-pages"> <?php esc_html_e( 'Show on all pages ', 'responsive-menu' ); ?></option>
							<option  value="shortcode"> <?php esc_html_e( 'Use as shortcode', 'responsive-menu' ); ?></option>
							<option  value="exclude-pages" disabled="disabled"> <?php esc_html_e( 'Exclude some pages (Pro) ', 'responsive-menu' ); ?></option>
							<option  value="include-pages" disabled="disabled"> <?php esc_html_e( 'Include only pages (Pro)', 'responsive-menu' ); ?></option>
						</select>
					</div>
				</div>

			</div>
		</div>
		<?php if ( ! empty( $nav_menus ) ) : ?>
		<!-- This is menu create wizard footer. -->
		<div class="rmp-dialog-footer">
			<span class="spinner"></span>
			<button class="button button-primary button-large  hide-if-no-js" id="rmp-menu-next-step" >
				<?php esc_html_e( 'Next', 'responsive-menu' ); ?>
			</button>

			<button class="button button-primary button-large  hide-if-no-js" id="rmp-create-new-menu" style="display:none">
				<?php esc_html_e( 'Create Menu', 'responsive-menu' ); ?>
			</button>
		</div>
		<?php endif; ?>
	</div>
</section>
</div>
