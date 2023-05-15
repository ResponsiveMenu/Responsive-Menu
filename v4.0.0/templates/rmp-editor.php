<?php
/**
 * This is menu editor page where we can customize the menu with
 * all dynamic option and also the preview.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $_GET['editor'] ) && 'rmp_menu' == $_GET['editor'] ) {
	exit;
}

global $wp_version;
use RMP\Features\Inc\Option_Manager;
use RMP\Features\Inc\Control_Manager;
use RMP\Features\Inc\UI_Manager;
use RMP\Features\Inc\Theme_Manager;
use RMP\Features\Inc\Editor;

$body_classes = array(
	'rmp-editor-active',
	'wp-version-' . str_replace( '.', '-', $wp_version ),
	'rmp-version-' . str_replace( '.', '-', RMP_PLUGIN_VERSION ),
);

$body_classes = array_merge( get_body_class(), $body_classes );
if ( is_rtl() ) {
	$body_classes[] = 'rtl';
}

// Check dark mode options.
$global_settings = get_option( 'rmp_global_setting_options' );
if ( ! empty( $global_settings['rmp_dark_mode'] ) ) {
	$body_classes[] = 'rmp-dark-mode';
}

$option_manager  = Option_Manager::get_instance();
$control_manager = Control_Manager::get_instance();
$ui_manager      = UI_Manager::get_instance();
$theme_manager   = Theme_Manager::get_instance();
$editor          = Editor::get_instance();
$menu_id         = get_the_ID();
$options         = $option_manager->get_options( $menu_id );
$menu_to_use = $option_manager->get_option( $menu_id, 'menu_to_use' );
$current_wp_menu = wp_get_nav_menu_object($menu_to_use);
global $wp_filesystem;
if ( empty( $wp_filesystem ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}
WP_Filesystem();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo esc_html__( 'rmp-menu-editor', 'responsive-menu' ) . ' | ' . esc_html( get_the_title() ); ?></title>
</head>
	<body class="wp-admin wp-core-ui js post-php post-type-rmp_menu <?php echo esc_attr( implode( ' ', $body_classes ) ); ?>">
		<div id="rmp-editor-wrapper" class="rmp-editor-overlay expanded rmp-preview-mobile">
			<form method="post" enctype="multipart/form-data" id="rmp-editor-form" class="rmp-editor-sidebar">
				<input type="hidden" name="rmp_device_mode" id="rmp_device_mode" value="mobile"/>
				<input type="hidden" id="menu_id" name="menu_id" value="<?php echo esc_attr( get_the_ID() ); ?>"/>
				<?php
					$editor->header_section( $options['menu_name'] );
				?>

				<div id="rmp-editor-main">
					<div id="rmp-editor-nav" class="rmp-editor-controls-nav" role="navigation" aria-label="<?php echo esc_attr( $options['menu_name'] ); ?>">
						<ul id="rmp-editor-pane" class="rmp-editor-pane-parent">
							<li id="rmp-tab-item-mobile-menu" class="rmp-tab-item" aria-owns="tab-mobile-menu">
								<span class="rmp-tab-item-icon">
									<?php
									$svg_mobile = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/mobile.svg' );
									if ( $svg_mobile ) {
										echo wp_kses( $svg_mobile, rmp_allow_svg_html_tags() );
									}
									?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Mobile Menu', 'responsive-menu' ); ?></h3>
							</li>

							<li id="rmp-tab-item-desktop-menu" class="rmp-tab-item" aria-owns="tab-desktop-menu">
								<span class="rmp-tab-item-icon">
									<?php
									$svg_desktop = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/desktop.svg' );
									if ( $svg_desktop ) {
										echo wp_kses( $svg_desktop, rmp_allow_svg_html_tags() );
									}
									?>
								</span>
								<h3 class="rmp-tab-item-title">
									<span> <?php esc_html_e( 'Desktop Menu', 'responsive-menu' ); ?></span>
									<a target="_blank" rel="noopener" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php echo esc_html__( 'Pro', 'responsive-menu' ); ?> </a>
								</h3>
							</li>

							<li id="rmp-tab-item-dropdowns" class="rmp-tab-item" aria-owns="tab-menu-styling">
								<span class="rmp-tab-item-icon">
									<?php
									$svg_dropdowns = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/dropdowns.svg' );
									if ( $svg_dropdowns ) {
										echo wp_kses( $svg_dropdowns, rmp_allow_svg_html_tags() );
									}
									?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Menu Styling', 'responsive-menu' ); ?></h3>
							</li>

							<li id="rmp-tab-item-header-bar" class="rmp-tab-item" aria-owns="tab-header-bar">
								<span class="rmp-tab-item-icon">
									<?php
									$svg_header = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/header.svg' );
									if ( $svg_header ) {
										echo wp_kses( $svg_header, rmp_allow_svg_html_tags() );
									}
									?>
								</span>
								<h3 class="rmp-tab-item-title">
									<span><?php esc_html_e( 'Header Bar', 'responsive-menu' ); ?></span>
									<a target="_blank" rel="noopener" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php echo esc_html__( 'Pro', 'responsive-menu' ); ?> </a>
								</h3>
							</li>

							<li id="rmp-tab-item-themes" class="rmp-tab-item" aria-owns="tab-themes">
								<span class="rmp-tab-item-icon">
									<?php
									$svg_advanced = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/advanced.svg' );
									if ( $svg_advanced ) {
										echo wp_kses( $svg_advanced, rmp_allow_svg_html_tags() );
									}
									?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Themes', 'responsive-menu' ); ?></h3>
							</li>

							<li id="rmp-tab-item-settings" class="rmp-tab-item" aria-owns="tab-settings">
								<span class="rmp-tab-item-icon">
									<?php
									$svg_general = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/general.svg' );
									if ( $svg_general ) {
										echo wp_kses( $svg_general, rmp_allow_svg_html_tags() );
									}
									?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Settings', 'responsive-menu' ); ?></h3>
							</li>
						</ul>
					</div>

					<div id="tab-themes" class="rmp-accordions" aria-label="Themes">
						<ul class="rmp-accordion-container" id="rmp-theme-items">
						<?php
							$ui_manager->start_accordion_item(
								array(
									'item_header' => array(
										'item_title' => esc_html__( 'Theme Details', 'responsive-menu' ),
									),
								)
							);

							$theme_name = 'Default';
							if ( ! empty( $options['menu_theme'] ) ) {
								$theme_name = $options['menu_theme'];
							}

							$control_manager->add_hidden_control(
								array(
									'value' => $theme_name,
									'name'  => 'menu[menu_theme]',
								)
							);

							$theme_type = 'default';
							if ( ! empty( $options['theme_type'] ) ) {
								$theme_type = $options['theme_type'];
							}

							$control_manager->add_hidden_control(
								array(
									'value' => $theme_type,
									'name'  => 'menu[theme_type]',
								)
							);

							$control_manager->add_sub_heading(
								array( 'text' => 'Theme Name - ' . $theme_name )
							);

							$theme_manager->get_theme_thumbnail( $theme_name, $theme_type );


							$control_manager->add_button_control(
								array(
									'label'         => esc_html__( 'Change Theme', 'responsive-menu' ),
									'id'            => 'rmp-change-theme-action',
									'group_classes' => 'full-size',
									'class'         => 'rmp-theme-change-button',
								)
							);
							$ui_manager->accordion_divider();

							$control_manager->add_button_control(
								array(
									'label'         => esc_html__( 'Save As Theme', 'responsive-menu' ),
									'id'            => 'rmp-theme-save-action',
									'group_classes' => 'full-size',
									'class'         => 'rmp-theme-save-button',
								)
							);


							$ui_manager->end_accordion_item();

							/**
							 * Fires after prepare the theme setting section.
							 *
							 * @since 4.1.0
							 *
							 * @param int   $menu_id
							 * @param array $options
							 */
							do_action( 'rmp_tab_themes', $menu_id, $options );

							?>
						</ul>
					</div>

					<div id="tab-header-bar" class="rmp-accordions" aria-label="Header Bar">
						<?php
							$control_manager->upgrade_notice();
						?>
					</div>

					<div id="tab-advanced-settings" class="rmp-accordions" aria-label="Advanced">
						<ul class="rmp-accordion-container">
							<?php

								// Device Breakpoints
								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Menu Breakpoint', 'responsive-menu' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Breakpoint', 'responsive-menu' ),
										'type'     => 'number',
										'id'       => 'rmp-menu-tablet-breakpoint',
										'name'     => 'menu[tablet_breakpoint]',
										'value'    => rmp_get_value( $options, 'tablet_breakpoint' ),
										'tool_tip' => array(
											'text' => esc_html__( 'Set the breakpoint below which you want hamburger menu', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'px',
										),
									)
								);
								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Animation Speeds', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->start_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Colours', 'responsive-menu' ),
										'type'     => 'text',
										'id'       => 'rmp-menu-transition-speed',
										'name'     => 'menu[transition_speed]',
										'value'    => rmp_get_value( $options, 'transition_speed' ),

										'tool_tip' => array(
											'text' => esc_html__( 'Specify the speed at which colours transition from standard to active or hover states.', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 's',
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Sub Menus', 'responsive-menu' ),
										'type'     => 'text',
										'id'       => 'rmp-sub-menu-speed',
										'name'     => 'menu[sub_menu_speed]',
										'value'    => rmp_get_value( $options, 'sub_menu_speed' ),

										'tool_tip' => array(
											'text' => esc_html__( 'Specify the speed at which the sub menus transition.', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 's',
										),
									)
								);

								$ui_manager->end_group_controls();


								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Technical', 'responsive-menu' ),
										),
										'feature_type' => 'pro',
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Trigger Menu on page load', 'responsive-menu' ),
										'id'           => 'rmp-show-menu-on-page-load',
										'class'        => 'rmp-show-menu-on-page-load',
										'tool_tip'     => array(
											'text' => esc_html__( 'The menu will appear in expanded state when the page loads.', 'responsive-menu' ),
										),
										'feature_type' => 'pro',
										'name'         => 'menu[show_menu_on_page_load]',
										'is_checked'   => '',

									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Disable Background Scrolling', 'responsive-menu' ),
										'id'           => 'rmp-menu-disable-scrolling',
										'class'        => 'rmp-menu-disable-scrolling',
										'feature_type' => 'pro',
										'tool_tip'     => array(
											'text' => esc_html__( 'This will disable the background page scrolling.', 'responsive-menu' ),
										),
										'name'         => 'menu[menu_disable_scrolling]',
										'is_checked'   => '',
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Enable Smooth Scrolling', 'responsive-menu' ),
										'id'           => 'rmp-menu-smooth-scroll-on',
										'class'        => 'rmp-menu-smooth-scroll-on',
										'tool_tip'     => array(
											'text' => esc_html__( 'The webpage will scroll smoothly to their target sections on same page.', 'responsive-menu' ),
										),
										'name'         => 'smooth_scroll_on',
										'feature_type' => 'pro',
										'is_checked'   => '',
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'        => esc_html__( 'Scroll Speed', 'responsive-menu' ),
										'type'         => 'number',
										'id'           => 'rmp-menu-smooth-scroll-speed',
										'name'         => 'smooth_scroll_speed',
										'feature_type' => 'pro',
										'value'        => '0',
										'has_unit'     => array(
											'unit_type' => 'ms',
										),
									)
								);

								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Page Overlay', 'responsive-menu' ),
										),
										'tool_tip'     => array(
											'text' => esc_html__( 'Put a backdrop when menu is active.', 'responsive-menu' ),
										),
										'feature_type' => 'pro',
										'item_content' => array(
											'content_class' => 'upgrade-notice-contents',
										),
									)
								);

									$control_manager->upgrade_notice();

								$ui_manager->end_accordion_item();

								?>
						</ul>
					</div>

					<div id="tab-desktop-menu" class="rmp-accordions" aria-label="Desktop Menu">
						<?php
							$control_manager->upgrade_notice();
						?>
					</div>

					<div id="tab-mobile-menu" class="rmp-accordions" aria-label="Mobile Menu">
						<ul class="rmp-editor-pane-parent">
						<?php
							$ui_manager->add_editor_menu_item(
								array(
									'item_class'  => 'is-child-item',
									'aria_owns'   => 'tab-container',
									'item_header' => array(
										'item_svg_icon' => RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/container.svg',
										'item_title'    => esc_html__( 'Container', 'responsive-menu' ),
									),
								)
							);

							$ui_manager->add_editor_menu_item(
								array(
									'item_class'  => 'is-child-item',
									'aria_owns'   => 'tab-toggle-button',
									'item_header' => array(
										'item_svg_icon' => RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/toggle.svg',
										'item_title'    => esc_html__( 'Toggle button', 'responsive-menu' ),
									),
								)
							);
							?>
						</ul>
					</div>

					<div id="tab-settings" class="rmp-accordions" aria-label="Settings">
						<ul class="rmp-editor-pane-parent">
						<?php
							$ui_manager->add_editor_menu_item(
								array(
									'item_class'  => 'is-child-item rmp-tab-item-general-settings',
									'aria_owns'   => 'tab-general-settings',
									'item_header' => array(
										'item_svg_icon' => RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/general.svg',
										'item_title'    => esc_html__( 'General Settings', 'responsive-menu' ),
									),
								)
							);

							$ui_manager->add_editor_menu_item(
								array(
									'item_class'  => 'is-child-item rmp-tab-item-advanced-settings',
									'aria_owns'   => 'tab-advanced-settings',
									'item_header' => array(
										'item_svg_icon' => RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/advanced.svg',
										'item_title'    => esc_html__( 'Advanced Settings', 'responsive-menu' ),
									),
								)
							);

							?>
						</ul>
					</div>

					<div id="tab-general-settings" class="rmp-accordions" aria-label="General Settings">
						<ul class="rmp-accordion-container">
							<?php

								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Menu Settings', 'responsive-menu' ),
										),
										'feature_type' => 'semi-pro',
									)
								);

								$ui_manager->start_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label' => esc_html__( 'Name', 'responsive-menu' ),
										'type'  => 'text',
										'id'    => 'rmp-menu-name',
										'name'  => 'menu[menu_name]',
										'value' => rmp_get_value( $options, 'menu_name' ),
									)
								);

								$label = sprintf(
									esc_html__( 'If no options appear here, make sure you have set them up under', 'responsive-menu' ) . '<strong>' . esc_html__( 'Appearance > Menus or', 'responsive-menu' ) . '</strong> <a href="' . esc_url( admin_url() . 'nav-menus.php' ) . '" target="_blank" rel="noopener"> ' . esc_html__( 'here', 'responsive-menu' ) . ' </a>
										<br/> <strong> ' . esc_html__( 'Please note that the', 'responsive-menu' ) . ' <a href="' . esc_url( admin_url() . 'nav-menus.php' ) . '" target="_blank" rel="noopener"> ' . esc_html__( 'Theme Location', 'responsive-menu' ) . ' </a> ' . esc_html__( 'option will take precedence over this.', 'responsive-menu' ) . ' </strong>'
								);

								$nav_menus    = wp_get_nav_menus();
								$wp_menu_list = array();
								foreach ( $nav_menus as $nav_menu ) {
									$wp_menu_list[ $nav_menu->term_id ] = $nav_menu->name;
								}
								$control_manager->add_select_control(
									array(
										'label'    => esc_html__( 'Choose WP Menu', 'responsive-menu' ),
										'id'       => 'rmp-menu-to-use',
										'tool_tip' => array(
											'text' => $label,
										),
										'name'     => 'menu[menu_to_use]',
										'options'  => $wp_menu_list,
										'value'    => $current_wp_menu->term_id,
									)
								);
								$ui_manager->end_group_controls();

								$ui_manager->accordion_divider();

								$control_manager->add_switcher_control(
									array(
										'label'         => esc_html__( ' Use different menu for mobile & tablet ', 'responsive-menu' ),
										'group_classes' => 'full-size',
										'id'            => 'rmp-menu-different-menu-for-mobile',
										'class'         => 'rmp-menu-different-menu-for-mobile',
										'feature_type'  => 'pro',
										'name'          => 'mobile_menu_to_use',
										'is_checked'    => false,
									)
								);

								$ui_manager->accordion_divider();
								$control_manager->add_device_visibility_control( $options );

								$ui_manager->accordion_divider();
								$control_manager->add_select_control(
									array(
										'label'   => esc_html__( 'Display condition', 'responsive-menu' ),
										'id'      => 'rmp-menu-display-condition',
										'name'    => 'menu[menu_display_on]',
										'options' => array(
											'all-pages' => esc_html__( 'Show on all pages ', 'responsive-menu' ),
											'shortcode' => esc_html__( 'Use as shortcode', 'responsive-menu' ),
											'exclude-pages' => esc_html__( 'Exclude some pages (PRO) ', 'responsive-menu' ),
											'include-pages' => esc_html__( 'Include only pages (PRO)', 'responsive-menu' ),
										),
										'value'   => rmp_get_value( $options, 'menu_display_on' ),
									)
								);

								$ui_manager->accordion_divider();
								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Hide Theme Menu', 'responsive-menu' ),
										'type'          => 'text',
										'group_classes' => 'full-size',
										'id'            => 'rmp-menu-to-hide',
										'name'          => 'menu[menu_to_hide]',
										'tool_tip'      => array(
											'text' => esc_html__( 'To hide your current theme menu you need to put the CSS selector here. Any legal CSS selection criteria is valid.', 'responsive-menu' ),
										),
										'value'         => rmp_get_value( $options, 'menu_to_hide' ),
									)
								);

								$ui_manager->end_accordion_item();
								?>
						</ul>
					</div>

					<div id="tab-menu-styling" class="rmp-accordions" aria-label="Menu Styling">
						<ul class="rmp-accordion-container">
							<?php


							$ui_manager->start_accordion_item(
								array(
									'item_header'  => array(
										'item_title' => esc_html__( 'Menu Settings', 'responsive-menu' ),
									),
									'feature_type' => 'semi-pro',
								)
							);


							$control_manager->add_text_input_control(
								array(
									'label'         => esc_html__( 'Custom Walker', 'responsive-menu' ),
									'group_classes' => 'full-size',
									'type'          => 'text',
									'id'            => 'rmp-custom-walker',
									'tool_tip'      => array(
										'text' => esc_html__( 'Modify the HTML output by using a custom Walker class.', 'responsive-menu' ),
									),
									'name'          => 'menu[custom_walker]',
									'value'         => rmp_get_value( $options, 'custom_walker' ),
								)
							);


								$ui_manager->start_group_controls();
								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Menu Background', 'responsive-menu' ),
										'id'    => 'rmp-menu-background-colour',
										'name'  => 'menu[menu_background_colour]',
										'value' => rmp_get_value( $options, 'menu_background_colour' ),
									)
								);

								$control_manager->add_select_control(
									array(
										'label'    => esc_html__( 'Depth Level', 'responsive-menu' ),
										'id'       => 'rmp-menu-depth',
										'tool_tip' => array(
											'text' => esc_html__( 'Set the level of nesting for sub menus.', 'responsive-menu' ),
										),
										'name'     => 'menu[menu_depth]',
										'options'  => array(
											'1' => 1,
											'2' => 2,
											'3' => 3,
											'4' => 4,
											'5' => 5,
										),
										'value'    => rmp_get_value( $options, 'menu_depth' ),
									)
								);

								$ui_manager->end_group_controls();

								$control_manager->add_group_text_control(
									array(
										'label'         => esc_html__( 'Padding', 'responsive-menu' ),
										'type'          => 'text',
										'class'         => 'rmp-menu-section-padding',
										'name'          => 'menu[menu_section_padding]',
										'input_options' => array( 'top', 'right', 'bottom', 'left' ),
										'value_options' => ! empty( $options['menu_section_padding'] ) ? $options['menu_section_padding'] : '',
									)
								);

								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Item Icon', 'responsive-menu' ),
										),
										'feature_type' => 'pro',
										'item_content' => array(
											'content_class' => 'upgrade-notice-contents',
										),
									)
								);
								$control_manager->upgrade_notice();
								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Item Styling', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->start_tabs_controls_panel(
									array(
										'tab_classes' => 'rmp-tab-content',
										'tab_items'   =>
										array(
											0 => array(
												'item_class' => 'nav-tab-active',
												'item_target' => 'top-level-item-styling',
												'item_text' => esc_html__( 'Top Level', 'responsive-menu' ),
											),
											1 => array(
												'item_class' => '',
												'item_target' => 'sub-level-item-styling',
												'item_text' => esc_html__( 'Sub Menu', 'responsive-menu' ),
											),
										),
									)
								);

								$ui_manager->start_tab_item(
									array(
										'item_id'    => 'top-level-item-styling',
										'item_class' => 'title-contents',
									)
								);
								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Item Height', 'responsive-menu' ),
										'type'          => 'number',
										'id'            => 'rmp-menu-links-height',
										'class'         => 'no-updates',
										'name'          => 'menu[menu_links_height]',
										'value'         => rmp_get_value( $options, 'menu_links_height' ),
										'group_classes' => 'full-size',
										'multi_device'  => true,
										'has_unit'      => array(
											'unit_type'    => 'all',
											'id'           => 'rmp-menu-links-height-unit',
											'name'         => 'menu[menu_links_height_unit]',
											'classes'      => 'is-unit no-updates',
											'default'      => 'px',
											'value'        => rmp_get_value( $options, 'menu_links_height_unit' ),
											'multi_device' => true,
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Line Height', 'responsive-menu' ),
										'type'          => 'number',
										'id'            => 'rmp-menu-links-line-height',
										'class'         => 'no-updates',
										'name'          => 'menu[menu_links_line_height]',
										'value'         => rmp_get_value( $options, 'menu_links_line_height' ),
										'group_classes' => 'full-size',
										'multi_device'  => true,
										'has_unit'      => array(
											'unit_type'    => 'all',
											'id'           => 'rmp-menu-links-line-height-unit',
											'name'         => 'menu[menu_links_line_height_unit]',
											'classes'      => 'is-unit no-updates',
											'default'      => 'px',
											'value'        => rmp_get_value( $options, 'menu_links_line_height_unit' ),
											'multi_device' => true,
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Padding', 'responsive-menu' ),
										'type'          => 'number',
										'id'            => 'rmp-menu-depth-level-0',
										'class'         => 'no-updates',
										'name'          => 'menu[menu_depth_0]',
										'value'         => rmp_get_value( $options, 'menu_depth_0' ),
										'group_classes' => 'full-size',
										'has_unit'      => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-depth-level-0-unit',
											'name'      => 'menu[menu_depth_0_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => '%',
											'value'     => rmp_get_value( $options, 'menu_depth_0_unit' ),
										),
									)
								);

								$ui_manager->start_sub_accordion();

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Typography', 'responsive-menu' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Font Size', 'responsive-menu' ),
										'type'          => 'number',
										'id'            => 'rmp-menu-font-size',
										'name'          => 'menu[menu_font_size]',
										'class'         => 'no-updates',
										'value'         => rmp_get_value( $options, 'menu_font_size' ),
										'group_classes' => 'full-size',
										'multi_device'  => true,
										'has_unit'      => array(
											'unit_type'    => 'all',
											'id'           => 'rmp-menu-font-size-unit',
											'name'         => 'menu[menu_font_size_unit]',
											'default'      => 'px',
											'classes'      => 'is-unit no-updates',
											'value'        => rmp_get_value( $options, 'menu_font_size_unit' ),
											'multi_device' => true,
										),
									)
								);



								$control_manager->add_text_input_control(
									array(
										'label'        => esc_html__( 'Font Family', 'responsive-menu' ),
										'type'         => 'text',
										'id'           => 'rmp-menu-font',
										'name'         => 'menu[menu_font]',
										'class'        => 'no-updates',
										'value'        => rmp_get_value( $options, 'menu_font' ),
										'multi_device' => true,
									)
								);

								$control_manager->add_select_control(
									array(
										'label'         => esc_html__( 'Font Weight', 'responsive-menu' ),
										'id'            => 'rmp-menu-font-weight',
										'class'         => 'no-updates',
										'name'          => 'menu[menu_font_weight]',
										'options'       => rmp_font_weight_options(),
										'value'         => rmp_get_value( $options, 'menu_font_weight' ),
										'group_classes' => 'full-size',
									)
								);

								$control_manager->add_text_alignment_control(
									array(
										'label'         => esc_html__( 'Text Alignment', 'responsive-menu' ),
										'class'         => 'rmp-menu-text-alignment',
										'name'          => 'menu[menu_text_alignment]',
										'options'       => array( 'left', 'center', 'right', 'justify' ),
										'value'         => rmp_get_value( $options, 'menu_text_alignment' ),
										'group_classes' => 'full-size',
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Letter Spacing', 'responsive-menu' ),
										'type'          => 'number',
										'id'            => 'rmp-menu-text-letter-spacing',
										'class'         => 'no-updates',
										'name'          => 'menu[menu_text_letter_spacing]',
										'value'         => rmp_get_value( $options, 'menu_text_letter_spacing' ),
										'group_classes' => 'full-size',
										'has_unit'      => array(
											'unit_type' => 'px',
										),
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'      => esc_html__( 'Word Wrap', 'responsive-menu' ),
										'id'         => 'rmp-menu-word-wrap',
										'class'      => 'rmp-menu-word-wrap',
										'tool_tip'   => array(
											'text' => esc_html__( 'Allow the menu items to wrap around to the next line.', 'responsive-menu' ),
										),
										'name'       => 'menu[menu_word_wrap]',
										'is_checked' => is_rmp_option_checked( 'on', $options, 'menu_word_wrap' ),

									)
								);

								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Text Color', 'responsive-menu' ),
										),
									)
								);


								$control_manager->add_color_control(
									array(
										'label'        => esc_html__( 'Normal', 'responsive-menu' ),
										'id'           => 'rmp-menu-link-color',
										'name'         => 'menu[menu_link_colour]',
										'value'        => rmp_get_value( $options, 'menu_link_colour' ),
										'multi_device' => true,
									)
								);

								$control_manager->add_color_control(
									array(
										'label'        => esc_html__( 'Hover', 'responsive-menu' ),
										'id'           => 'rmp-menu-link-hover-color',
										'name'         => 'menu[menu_link_hover_colour]',
										'value'        => rmp_get_value( $options, 'menu_link_hover_colour' ),

										'multi_device' => true,
									)
								);

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Active Item', 'responsive-menu' ),
										'id'    => 'rmp-menu-current-link-active-color',
										'name'  => 'menu[menu_current_link_colour]',
										'value' => rmp_get_value( $options, 'menu_current_link_colour' ),

									)
								);

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Active Item Hover', 'responsive-menu' ),
										'id'    => 'rmp-menu-current-link-active-hover-color',
										'name'  => 'menu[menu_current_link_hover_colour]',
										'value' => rmp_get_value( $options, 'menu_current_link_hover_colour' ),

									)
								);

								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Background Color', 'responsive-menu' ),
										),
									)
								);

								$control_manager->add_color_control(
									array(
										'label'        => esc_html__( 'Background', 'responsive-menu' ),
										'id'           => 'rmp-menu-item-background-colour',
										'name'         => 'menu[menu_item_background_colour]',
										'value'        => rmp_get_value( $options, 'menu_item_background_colour' ),

										'multi_device' => true,

									)
								);

								$control_manager->add_color_control(
									array(
										'label'        => esc_html__( 'Background Hover', 'responsive-menu' ),
										'id'           => 'rmp-menu-item-background-hover-color',
										'name'         => 'menu[menu_item_background_hover_colour]',
										'value'        => rmp_get_value( $options, 'menu_item_background_hover_colour' ),

										'multi_device' => true,
									)
								);

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Active Item Background', 'responsive-menu' ),
										'id'    => 'rmp-menu-current-item-background-color',
										'name'  => 'menu[menu_current_item_background_colour]',
										'value' => rmp_get_value( $options, 'menu_current_item_background_colour' ),

									)
								);

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Active Item Background Hover', 'responsive-menu' ),
										'id'    => 'rmp-menu-current-item-background-hover-color',
										'name'  => 'menu[menu_current_item_background_hover_colour]',
										'value' => rmp_get_value( $options, 'menu_current_item_background_hover_colour' ),

									)
								);
								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Border', 'responsive-menu' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Border Width', 'responsive-menu' ),
										'type'     => 'number',
										'id'       => 'rmp-menu-border-width',
										'name'     => 'menu[menu_border_width]',
										'value'    => rmp_get_value( $options, 'menu_border_width' ),
										'class'    => 'no-updates',
										'tool_tip' => array(
											'text' => esc_html__( 'Set the border size for each menu link and it\'s unit.', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-border-width-unit',
											'name'      => 'menu[menu_border_width_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'menu_border_width_unit' ),
										),
									)
								);

								$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Normal', 'responsive-menu' ),
											'id'    => 'rmp-menu-item-border-colour',
											'name'  => 'menu[menu_item_border_colour]',
											'value' => rmp_get_value( $options, 'menu_item_border_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-item-border-colour-hover',
											'name'  => 'menu[menu_item_border_colour_hover]',
											'value' => rmp_get_value( $options, 'menu_item_border_colour_hover' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item', 'responsive-menu' ),
											'id'    => 'rmp-menu-item-border-colour-active',
											'name'  => 'menu[menu_current_item_border_colour]',
											'value' => rmp_get_value( $options, 'menu_current_item_border_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label'    => esc_html__( 'Active Hover', 'responsive-menu' ),
											'id'       => 'rmp-menu-current-item-border-hover-colour',
											'tool_tip' => array(
												'text' => esc_html__( 'Set the border colour when the mouse rolls over the current menu item.', 'responsive-menu' ),
											),
											'name'     => 'menu[menu_current_item_border_hover_colour]',
											'value'    => rmp_get_value( $options, 'menu_current_item_border_hover_colour' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();

									$ui_manager->end_sub_accordion();
									$ui_manager->end_tab_item();

									$ui_manager->start_tab_item(
										array(
											'item_id'    => 'sub-level-item-styling',
											'item_class' => 'title-contents',
										)
									);
									$control_manager->add_text_input_control(
										array(
											'label'        => esc_html__( 'Item Height', 'responsive-menu' ),
											'type'         => 'number',
											'id'           => 'rmp-submenu-links-height',
											'class'        => 'no-updates',
											'name'         => 'menu[submenu_links_height]',
											'value'        => rmp_get_value( $options, 'submenu_links_height' ),
											'multi_device' => true,
											'group_classes' => 'full-size',
											'has_unit'     => array(
												'unit_type' => 'all',
												'id'      => 'rmp-submenu-links-height-unit',
												'name'    => 'menu[submenu_links_height_unit]',
												'classes' => 'is-unit no-updates',
												'default' => 'px',
												'value'   => rmp_get_value( $options, 'submenu_links_height_unit' ),
												'multi_device' => true,
											),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'        => esc_html__( 'Line Height', 'responsive-menu' ),
											'type'         => 'number',
											'id'           => 'rmp-submenu-links-line-height',
											'class'        => 'no-updates',
											'name'         => 'menu[submenu_links_line_height]',
											'value'        => rmp_get_value( $options, 'submenu_links_line_height' ),
											'multi_device' => true,
											'group_classes' => 'full-size',
											'has_unit'     => array(
												'unit_type' => 'all',
												'id'      => 'rmp-submenu-links-line-height-unit',
												'name'    => 'menu[submenu_links_line_height_unit]',
												'classes' => 'is-unit no-updates',
												'default' => 'px',
												'value'   => rmp_get_value( $options, 'submenu_links_line_height_unit' ),
												'multi_device' => true,
											),
										)
									);

									$ui_manager->start_sub_accordion();
									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Indentation', 'responsive-menu' ),
											),
										)
									);
									$control_manager->add_select_control(
										array(
											'label'    => esc_html__( 'Side', 'responsive-menu' ),
											'id'       => 'rmp-menu-depth-side',
											'tool_tip' => array(
												'text' => esc_html__( 'You can set which side of the menu items the padding should be on.', 'responsive-menu' ),
											),
											'name'     => 'menu[menu_depth_side]',
											'options'  => array(
												'right' => 'Right',
												'left'  => 'Left',
											),
											'value'    => rmp_get_value( $options, 'menu_depth_side' ),
										)
									);


									$ui_manager->start_group_controls();
									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Child Level 1', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-menu-depth-level-1',
											'name'     => 'menu[menu_depth_1]',
											'value'    => rmp_get_value( $options, 'menu_depth_1' ),

											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-menu-depth-level-1-unit',
												'name'    => 'menu[menu_depth_1_unit]',
												'classes' => 'is-unit',
												'default' => '%',
												'value'   => rmp_get_value( $options, 'menu_depth_1_unit' ),
											),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Child Level 2', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-menu-depth-level-2',
											'name'     => 'menu[menu_depth_2]',
											'value'    => rmp_get_value( $options, 'menu_depth_2' ),

											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-menu-depth-level-2-unit',
												'name'    => 'menu[menu_depth_2_unit]',
												'classes' => 'is-unit',
												'default' => '%',
												'value'   => rmp_get_value( $options, 'menu_depth_2_unit' ),
											),
										)
									);

									$ui_manager->end_group_controls();

									$ui_manager->start_group_controls();
									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Child Level 3', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-menu-depth-level-3',
											'name'     => 'menu[menu_depth_3]',
											'value'    => rmp_get_value( $options, 'menu_depth_3' ),

											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-menu-depth-level-3-unit',
												'name'    => 'menu[menu_depth_3_unit]',
												'classes' => 'is-unit',
												'default' => '%',
												'value'   => rmp_get_value( $options, 'menu_depth_3_unit' ),
											),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Child Level 4', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-menu-depth-level-4',
											'name'     => 'menu[menu_depth_4]',
											'value'    => rmp_get_value( $options, 'menu_depth_4' ),

											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-menu-depth-level-4-unit',
												'name'    => 'menu[menu_depth_4_unit]',
												'classes' => 'is-unit',
												'default' => '%',
												'value'   => rmp_get_value( $options, 'menu_depth_4_unit' ),
											),
										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();
									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Background Color', 'responsive-menu' ),
											),
										)
									);
									$control_manager->add_color_control(
										array(
											'label'        => esc_html__( 'Normal', 'responsive-menu' ),
											'id'           => 'rmp-submenu-item-background-color',
											'name'         => 'menu[submenu_item_background_colour]',
											'value'        => rmp_get_value( $options, 'submenu_item_background_colour' ),
											'multi_device' => true,
										)
									);

									$control_manager->add_color_control(
										array(
											'label'        => esc_html__( 'Hover', 'responsive-menu' ),
											'id'           => 'rmp-submenu-item-background-hover-color',
											'name'         => 'menu[submenu_item_background_hover_colour]',
											'value'        => rmp_get_value( $options, 'submenu_item_background_hover_colour' ),

											'multi_device' => true,
										)
									);

									$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item', 'responsive-menu' ),
											'id'    => 'rmp-submenu-current-item-background-color',
											'name'  => 'menu[submenu_current_item_background_colour]',
											'value' => rmp_get_value( $options, 'submenu_current_item_background_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item Hover', 'responsive-menu' ),
											'id'    => 'rmp-submenu-current-item-background-hover-color',
											'name'  => 'menu[submenu_current_item_background_hover_colour]',
											'value' => rmp_get_value( $options, 'submenu_current_item_background_hover_colour' ),

										)
									);
									$ui_manager->end_group_controls();
									$ui_manager->end_accordion_item();
									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Border', 'responsive-menu' ),
											),
										)
									);
									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Border Width', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-submenu-border-width',
											'class'    => 'no-updates',
											'name'     => 'menu[submenu_border_width]',
											'value'    => rmp_get_value( $options, 'submenu_border_width' ),
											'tool_tip' => array(
												'text' => esc_html__( 'Set the border size for each menu link and it\'s unit.', 'responsive-menu' ),
											),
											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-submenu-border-width-unit',
												'name'    => 'menu[submenu_border_width_unit]',
												'classes' => 'is-unit no-updates',
												'default' => 'px',
												'value'   => rmp_get_value( $options, 'submenu_border_width_unit' ),
											),
										)
									);

									$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Normal', 'responsive-menu' ),
											'id'    => 'rmp-submenu-item-border-colour',
											'name'  => 'menu[submenu_item_border_colour]',
											'value' => rmp_get_value( $options, 'submenu_item_border_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Hover', 'responsive-menu' ),
											'id'    => 'rmp-submenu-item-border-colour-hover',
											'name'  => 'menu[submenu_item_border_colour_hover]',
											'value' => rmp_get_value( $options, 'submenu_item_border_colour_hover' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item', 'responsive-menu' ),
											'id'    => 'rmp-submenu-item-border-colour-active',
											'name'  => 'menu[submenu_current_item_border_colour]',
											'value' => rmp_get_value( $options, 'submenu_current_item_border_colour' ),
										)
									);

									$control_manager->add_color_control(
										array(
											'label'    => esc_html__( 'Active Item Hover', 'responsive-menu' ),
											'id'       => 'rmp-submenu-current-item-border-hover-colour',
											'tool_tip' => array(
												'text' => esc_html__( 'Set the border colour when the mouse rolls over the current submenu item.', 'responsive-menu' ),
											),
											'name'     => 'menu[submenu_current_item_border_hover_colour]',
											'value'    => rmp_get_value( $options, 'submenu_current_item_border_hover_colour' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();
									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Typography', 'responsive-menu' ),
											),
										)
									);
									$control_manager->add_text_input_control(
										array(
											'label'        => esc_html__( 'Font Size', 'responsive-menu' ),
											'type'         => 'number',
											'id'           => 'rmp-submenu-font-size',
											'class'        => 'no-updates',
											'name'         => 'menu[submenu_font_size]',
											'value'        => rmp_get_value( $options, 'submenu_font_size' ),
											'multi_device' => true,
											'has_unit'     => array(
												'unit_type' => 'all',
												'id'      => 'rmp-submenu-font-size-unit',
												'name'    => 'menu[submenu_font_size_unit]',
												'classes' => 'is-unit no-updates',
												'default' => 'px',
												'value'   => rmp_get_value( $options, 'submenu_font_size_unit' ),
												'multi_device' => true,
											),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'        => esc_html__( 'Font Family', 'responsive-menu' ),
											'type'         => 'text',
											'id'           => 'rmp-submenu-font',
											'name'         => 'menu[submenu_font]',
											'class'        => 'no-updates',
											'value'        => rmp_get_value( $options, 'submenu_font' ),
											'multi_device' => true,
										)
									);

									$ui_manager->start_group_controls();

									$control_manager->add_select_control(
										array(
											'label'   => esc_html__( 'Font Weight', 'responsive-menu' ),
											'id'      => 'rmp-submenu-font-weight',
											'name'    => 'menu[submenu_font_weight]',
											'class'   => 'no-updates',
											'options' => rmp_font_weight_options(),
											'value'   => rmp_get_value( $options, 'submenu_font_weight' ),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Letter Spacing', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-submenu-text-letter-spacing',
											'class'    => 'no-updates',
											'name'     => 'menu[submenu_text_letter_spacing]',
											'value'    => rmp_get_value( $options, 'submenu_text_letter_spacing' ),
											'has_unit' => array(
												'unit_type' => 'px',
											),
										)
									);

									$ui_manager->end_group_controls();


									$control_manager->add_text_alignment_control(
										array(
											'label'   => esc_html__( 'Text Alignment', 'responsive-menu' ),
											'class'   => 'rmp-submenu-text-alignment',
											'name'    => 'menu[submenu_text_alignment]',
											'options' => array( 'left', 'center', 'right', 'justify' ),
											'value'   => rmp_get_value( $options, 'submenu_text_alignment' ),
										)
									);


									$ui_manager->end_accordion_item();
									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Text Color', 'responsive-menu' ),
											),
										)
									);
									$control_manager->add_color_control(
										array(
											'label'        => esc_html__( 'Color', 'responsive-menu' ),
											'id'           => 'rmp-submenu-link-color',
											'name'         => 'menu[submenu_link_colour]',
											'value'        => rmp_get_value( $options, 'submenu_link_colour' ),
											'multi_device' => true,

										)
									);

									$control_manager->add_color_control(
										array(
											'label'        => esc_html__( 'Hover Color', 'responsive-menu' ),
											'id'           => 'rmp-submenu-link-hover-color',
											'name'         => 'menu[submenu_link_hover_colour]',
											'value'        => rmp_get_value( $options, 'submenu_link_hover_colour' ),
											'multi_device' => true,
										)
									);

									$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item Color', 'responsive-menu' ),
											'id'    => 'rmp-submenu-link-colour-active',
											'name'  => 'menu[submenu_current_link_colour]',
											'value' => rmp_get_value( $options, 'submenu_current_link_colour' ),
										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item Hover', 'responsive-menu' ),
											'id'    => 'rmp-submenu-link-active-hover-color',
											'name'  => 'menu[submenu_current_link_hover_colour]',
											'value' => rmp_get_value( $options, 'submenu_current_link_hover_colour' ),
										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();
									$ui_manager->end_sub_accordion();

									$ui_manager->end_tab_item();

									$ui_manager->end_tabs_controls_panel();

									$ui_manager->end_accordion_item();


									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Trigger Icon', 'responsive-menu' ),
											),
										)
									);

									$ui_manager->start_tabs_controls_panel(
										array(
											'tab_classes' => 'rmp-tab-content',
											'tab_items'   =>
											array(
												0 => array(
													'item_class'  => 'nav-tab-active',
													'item_target' => 'menu-item-arrow-text',
													'item_text'   => esc_html__( 'Text', 'responsive-menu' ),
												),
												1 => array(
													'item_class'  => '',
													'item_target' => 'menu-item-arrow-icon',
													'item_text'   => esc_html__( 'Icon', 'responsive-menu' ),
												),
												2 => array(
													'item_class'  => '',
													'item_target' => 'menu-item-arrow-image',
													'item_text'   => esc_html__( 'Image', 'responsive-menu' ),
												),
											),
										)
									);

									$ui_manager->start_tab_item(
										array(
											'item_id'    => 'menu-item-arrow-text',
											'item_class' => 'title-contents',
										)
									);
									$ui_manager->start_group_controls();
									$control_manager->add_text_input_control(
										array(
											'label' => esc_html__( 'Text Shape', 'responsive-menu' ),
											'type'  => 'text',
											'id'    => 'rmp-menu-inactive-arrow-shape',
											'name'  => 'menu[inactive_arrow_shape]',
											'value' => rmp_get_value( $options, 'inactive_arrow_shape' ),

										)
									);

									$control_manager->add_text_input_control(
										array(
											'label' => esc_html__( 'Active Text Shape', 'responsive-menu' ),
											'type'  => 'text',
											'id'    => 'rmp-menu-active-arrow-shape',
											'name'  => 'menu[active_arrow_shape]',
											'value' => rmp_get_value( $options, 'active_arrow_shape' ),

										)
									);
									$ui_manager->end_group_controls();
									$ui_manager->end_tab_item();

									$ui_manager->start_tab_item(
										array(
											'item_id'    => 'menu-item-arrow-icon',
											'item_class' => 'title-contents',
										)
									);


									$control_manager->add_icon_picker_control(
										array(
											'label'        => esc_html__( 'Font Icon', 'responsive-menu' ),
											'id'           => 'rmp-menu-inactive-arrow-font-icon',
											'group_classes' => 'full-size',
											'class'        => 'no-updates',
											'picker_class' => 'rmp-menu-font-icon-picker-button',
											'picker_id'    => 'rmp-menu-inactive-arrow-font-icon-selector',
											'name'         => 'menu[inactive_arrow_font_icon]',
											'value'        => rmp_get_value( $options, 'inactive_arrow_font_icon' ),

										)
									);

									$control_manager->add_icon_picker_control(
										array(
											'label'        => esc_html__( 'Active Font Icon', 'responsive-menu' ),
											'id'           => 'rmp-menu-active-arrow-font-icon',
											'group_classes' => 'full-size',
											'picker_class' => 'rmp-menu-font-icon-picker-button',
											'picker_id'    => 'rmp-menu-active-arrow-font-icon-selector',
											'name'         => 'menu[active_arrow_font_icon]',
											'value'        => rmp_get_value( $options, 'active_arrow_font_icon' ),

										)
									);

									$ui_manager->end_tab_item();


									$ui_manager->start_tab_item(
										array(
											'item_id'    => 'menu-item-arrow-image',
											'item_class' => 'title-style',
										)
									);

									$control_manager->add_image_control(
										array(
											'label'        => esc_html__( 'Image', 'responsive-menu' ),
											'group_classes' => 'full-size',
											'id'           => 'rmp-menu-inactive-arrow-image',
											'picker_class' => 'rmp-menu-inactive-arrow-image-selector',
											'picker_id'    => 'rmp-menu-inactive-arrow-image-selector',
											'name'         => 'menu[inactive_arrow_image]',
											'value'        => rmp_get_value( $options, 'inactive_arrow_image' ),

										)
									);

									$control_manager->add_image_control(
										array(
											'label'        => esc_html__( 'Active Image', 'responsive-menu' ),
											'group_classes' => 'full-size',
											'id'           => 'rmp-menu-active-arrow-image',
											'picker_class' => 'rmp-menu-active-arrow-image-selector',
											'picker_id'    => 'rmp-menu-active-arrow-image-selector',
											'name'         => 'menu[active_arrow_image]',
											'value'        => rmp_get_value( $options, 'active_arrow_image' ),

										)
									);
									$ui_manager->end_tab_item();

									$ui_manager->end_tabs_controls_panel();

									$ui_manager->accordion_divider();

									$ui_manager->start_group_controls();

									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Width', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-submenu-arrow-width',
											'name'     => 'menu[submenu_arrow_width]',
											'value'    => rmp_get_value( $options, 'submenu_arrow_width' ),
											'class'    => 'no-updates',
											'tool_tip' => array(
												'text' => esc_html__( 'Set the width of the menu trigger items and their units.', 'responsive-menu' ),
											),
											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-submenu-arrow-width-unit',
												'name'    => 'menu[submenu_arrow_width_unit]',
												'classes' => 'is-unit',
												'default' => 'px',
												'value'   => rmp_get_value( $options, 'submenu_arrow_width_unit' ),
											),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Height', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-submenu-arrow-height',
											'name'     => 'menu[submenu_arrow_height]',
											'value'    => rmp_get_value( $options, 'submenu_arrow_height' ),
											'class'    => 'no-updates',
											'tool_tip' => array(
												'text' => esc_html__( 'Set the height of the menu trigger items and their units.', 'responsive-menu' ),
											),
											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-submenu-arrow-height-unit',
												'name'    => 'menu[submenu_arrow_height_unit]',
												'classes' => 'is-unit',
												'default' => 'px',
												'value'   => rmp_get_value( $options, 'submenu_arrow_height_unit' ),
											),
										)
									);

									$ui_manager->end_group_controls();

									$control_manager->add_select_control(
										array(
											'label'   => esc_html__( 'Position', 'responsive-menu' ),
											'id'      => 'rmp-menu-arrow-position',
											'class'   => 'rmp-menu-arrow-position',
											'name'    => 'menu[arrow_position]',
											'options' => array(
												'right' => 'Right',
												'left'  => 'Left',
											),
											'value'   => rmp_get_value( $options, 'arrow_position' ),
										)
									);

									$ui_manager->start_sub_accordion();

									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Text Color', 'responsive-menu' ),
											),
										)
									);

									$ui_manager->start_group_controls();

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Normal', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-shape-colour',
											'name'  => 'menu[menu_sub_arrow_shape_colour]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_shape_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-shape-hover-colour',
											'name'  => 'menu[menu_sub_arrow_shape_hover_colour]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_shape_hover_colour' ),

										)
									);

									$ui_manager->end_group_controls();

									$ui_manager->start_group_controls();

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-shape-colour-active',
											'name'  => 'menu[menu_sub_arrow_shape_colour_active]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_shape_colour_active' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-shape-hover-colour-active',
											'name'  => 'menu[menu_sub_arrow_shape_hover_colour_active]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_shape_hover_colour_active' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();

									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Border Color', 'responsive-menu' ),
											),
										)
									);

									$control_manager->add_text_input_control(
										array(
											'label'    => esc_html__( 'Border Width', 'responsive-menu' ),
											'type'     => 'number',
											'id'       => 'rmp-menu-sub-arrow-border-width',
											'name'     => 'menu[menu_sub_arrow_border_width]',
											'value'    => rmp_get_value( $options, 'menu_sub_arrow_border_width' ),
											'class'    => 'no-updates',
											'has_unit' => array(
												'unit_type' => 'all',
												'id'      => 'rmp-menu-sub-arrow-border-width-unit',
												'name'    => 'menu[menu_sub_arrow_border_width_unit]',
												'classes' => 'is-unit no-updates',
												'value'   => rmp_get_value( $options, 'menu_sub_arrow_border_width_unit' ),
											),
										)
									);

									$ui_manager->start_group_controls();

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Normal', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-border-colour',
											'name'  => 'menu[menu_sub_arrow_border_colour]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_border_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-border-hover-colour',
											'name'  => 'menu[menu_sub_arrow_border_hover_colour]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_border_hover_colour' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->start_group_controls();
									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-border-colour-active',
											'name'  => 'menu[menu_sub_arrow_border_colour_active]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_border_colour_active' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-border-hover-colour-active',
											'name'  => 'menu[menu_sub_arrow_border_hover_colour_active]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_border_hover_colour_active' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();

									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Background Color', 'responsive-menu' ),
											),
										)
									);

									$ui_manager->start_group_controls();

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Normal', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-background-color',
											'name'  => 'menu[menu_sub_arrow_background_colour]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_background_colour' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-background-hover-colour',
											'name'  => 'menu[menu_sub_arrow_background_hover_colour]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_background_hover_colour' ),

										)
									);
									$ui_manager->end_group_controls();
									$ui_manager->start_group_controls();

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-background-colour-active',
											'name'  => 'menu[menu_sub_arrow_background_colour_active]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_background_colour_active' ),

										)
									);

									$control_manager->add_color_control(
										array(
											'label' => esc_html__( 'Active Item Hover', 'responsive-menu' ),
											'id'    => 'rmp-menu-sub-arrow-background-hover-colour-active',
											'name'  => 'menu[menu_sub_arrow_background_hover_colour_active]',
											'value' => rmp_get_value( $options, 'menu_sub_arrow_background_hover_colour_active' ),

										)
									);
									$ui_manager->end_group_controls();

									$ui_manager->end_accordion_item();

									$ui_manager->end_sub_accordion();



									$ui_manager->start_accordion_item(
										array(
											'item_header'  => array(
												'item_title' => esc_html__( 'Animation', 'responsive-menu' ),
											),
											'item_content' => array(
												'content_class' => 'upgrade-notice-contents',
											),
											'feature_type' => 'pro',
										)
									);

									$control_manager->upgrade_notice();
									$ui_manager->end_accordion_item();


									$ui_manager->start_accordion_item(
										array(
											'item_header' => array(
												'item_title' => esc_html__( 'Behaviour', 'responsive-menu' ),
											),
										)
									);

									$control_manager->add_switcher_control(
										array(
											'label'      => esc_html__( 'Item Descriptions', 'responsive-menu' ),
											'id'         => 'rmp-menu-submenu-descriptions-on',
											'tool_tip'   => array(
												'text' => esc_html__( 'Show the description text of menu items. Description text should be set while creating WordPress menus.', 'responsive-menu' ),
											),
											'name'       => 'menu[submenu_descriptions_on]',
											'is_checked' => is_rmp_option_checked( 'on', $options, 'submenu_descriptions_on' ),
										)
									);

									$control_manager->add_switcher_control(
										array(
											'label'      => esc_html__( 'Use Accordion', 'responsive-menu' ),
											'id'         => 'rmp-menu-accordion-animation',
											'class'      => 'rmp-menu-accordion-animation',
											'name'       => 'menu[accordion_animation]',
											'is_checked' => is_rmp_option_checked( 'on', $options, 'accordion_animation' ),

										)
									);

									$control_manager->add_switcher_control(
										array(
											'label'      => esc_html__( 'Auto Expand All Sub Menus', 'responsive-menu' ),
											'id'         => 'rmp-menu-auto-expand-all-submenus',
											'class'      => 'rmp-menu-auto-expand-all-submenus',
											'name'       => 'menu[auto_expand_all_submenus]',
											'is_checked' => is_rmp_option_checked( 'on', $options, 'auto_expand_all_submenus' ),

										)
									);

									$control_manager->add_switcher_control(
										array(
											'label'      => esc_html__( 'Auto Expand Current Sub Menus', 'responsive-menu' ),
											'id'         => 'rmp-menu-auto-expand-current-submenus',
											'class'      => 'rmp-menu-auto-expand-current-submenus',
											'name'       => 'menu[auto_expand_current_submenus]',
											'is_checked' => is_rmp_option_checked( 'on', $options, 'auto_expand_current_submenus' ),

										)
									);

									$control_manager->add_switcher_control(
										array(
											'label'      => esc_html__( 'Expand Sub items on Parent Item Click', 'responsive-menu' ),
											'id'         => 'rmp-menu-menu-item-click-to-trigger-submenu',
											'class'      => 'rmp-menu-menu-item-click-to-trigger-submenu',
											'name'       => 'menu[menu_item_click_to_trigger_submenu]',
											'is_checked' => is_rmp_option_checked( 'on', $options, 'menu_item_click_to_trigger_submenu' ),

										)
									);


									$ui_manager->end_accordion_item();
									?>
						</ul>
					</div>

					<div id="tab-toggle-button" class="rmp-accordions" aria-label="Toggle Button">
						<ul class="rmp-accordion-container">
							<?php
								// Toggle Box

								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Button Style', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->start_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Container Width', 'responsive-menu' ),
										'type'     => 'number',
										'id'       => 'rmp-menu-button-width',
										'name'     => 'menu[button_width]',
										'class'    => 'no-updates',
										'value'    => rmp_get_value( $options, 'button_width' ),

										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-width-unit',
											'name'      => 'menu[button_width_unit]',
											'default'   => 'px',
											'classes'   => 'is-unit no-updates',
											'value'     => rmp_get_value( $options, 'button_width_unit' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Container Height', 'responsive-menu' ),
										'type'     => 'number',
										'id'       => 'rmp-menu-button-height',
										'name'     => 'menu[button_height]',
										'class'    => 'no-updates',
										'value'    => rmp_get_value( $options, 'button_height' ),

										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-height-unit',
											'default'   => 'px',
											'name'      => 'menu[button_height_unit]',
											'classes'   => 'is-unit no-updates',
											'value'     => rmp_get_value( $options, 'button_height_unit' ),
										),
									)
								);

								$ui_manager->end_group_controls();

								$ui_manager->accordion_divider();


								$ui_manager->start_group_controls();
								$control_manager->add_color_control(
									array(
										'label'    => esc_html__( 'Background Color', 'responsive-menu' ),
										'id'       => 'rmp-menu-button-background-colour',
										'tool_tip' => array(
											'text' => esc_html__( 'Set the background colour of the button container.', 'responsive-menu' ),
										),
										'name'     => 'menu[button_background_colour]',
										'value'    => rmp_get_value( $options, 'button_background_colour' ),

									)
								);

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Background Hover', 'responsive-menu' ),
										'id'    => 'rmp-menu-button-background-colour-hover',
										'name'  => 'menu[button_background_colour_hover]',
										'value' => rmp_get_value( $options, 'button_background_colour_hover' ),

									)
								);

								$ui_manager->end_group_controls();

								$ui_manager->start_group_controls();
								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Active Color', 'responsive-menu' ),
										'id'    => 'rmp-menu-button-background-colour-active',
										'name'  => 'menu[button_background_colour_active]',
										'value' => rmp_get_value( $options, 'button_background_colour_active' ),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Border Radius', 'responsive-menu' ),
										'type'     => 'number',
										'class'    => 'no-updates',
										'id'       => 'rmp-menu-toggle-border-radius',
										'name'     => 'menu[toggle_button_border_radius]',
										'value'    => rmp_get_value( $options, 'toggle_button_border_radius' ),
										'has_unit' => array(
											'unit_type' => 'px',
										),
									)
								);
								$ui_manager->end_group_controls();

								$control_manager->add_switcher_control(
									array(
										'label'      => esc_html__( 'Transparent Background', 'responsive-menu' ),
										'id'         => 'rmp-menu-button-transparent-background',
										'class'      => 'rmp-menu-button-transparent-background',
										'tool_tip'   => array(
											'text' => esc_html__( 'Set the button container to a transparent background.', 'responsive-menu' ),
										),
										'name'       => 'menu[button_transparent_background]',
										'is_checked' => is_rmp_option_checked( 'on', $options, 'button_transparent_background' ),

									)
								);

								$ui_manager->end_accordion_item();

								// Toggle Positioning
								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Button Position', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->start_group_controls();

								$control_manager->add_select_control(
									array(
										'label'    => esc_html__( 'Side', 'responsive-menu' ),
										'id'       => 'rmp-menu-button-left-or-right',
										'class'    => 'rmp-menu-button-left-or-right no-updates',
										'tool_tip' => array(
											'text' => esc_html__( 'Specify which side of the page you want the button to be displayed on.', 'responsive-menu' ),
										),
										'name'     => 'menu[button_left_or_right]',
										'options'  => array(
											'right' => 'Right',
											'left'  => 'Left',
										),
										'value'    => rmp_get_value( $options, 'button_left_or_right' ),
									)
								);


								$control_manager->add_select_control(
									array(
										'label'    => esc_html__( 'Position', 'responsive-menu' ),
										'id'       => 'rmp-menu-button-position-type',
										'class'    => 'no-updates',
										'tool_tip' => array(
											'text' => esc_html__( 'Specify how you want the button to stick to your page.', 'responsive-menu' ),
										),
										'name'     => 'menu[button_position_type]',
										'options'  => array(
											'fixed'    => 'Fixed',
											'absolute' => 'Absolute',
											'relative' => 'Relative',
											'inside-element' => 'Custom Selector',
										),
										'value'    => rmp_get_value( $options, 'button_position_type' ),
									)
								);
								$ui_manager->end_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Element selector', 'responsive-menu' ),
										'id'            => 'rmp-menu-hamburger-selector',
										'type'          => 'text',
										'group_classes' => 'full-size rmp-menu-hamburger-selector-div',
										'class'         => '',
										'placeholder'   => esc_html__( 'e.g. #header, .header', 'responsive-menu' ),
										'name'          => 'menu[hamburger_position_selector]',
										'value'         => rmp_get_value( $options, 'hamburger_position_selector' ),
										'tool_tip'      => array(
											'text' => esc_html__( 'Show hamburger inside element.', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->start_group_controls();
								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Distance from Side', 'responsive-menu' ),
										'type'     => 'number',
										'class'    => 'no-updates',
										'id'       => 'rmp-menu-button-distance-from-side',
										'name'     => 'menu[button_distance_from_side]',
										'value'    => rmp_get_value( $options, 'button_distance_from_side' ),
										'tool_tip' => array(
											'text' => esc_html__( 'Specify how far across from the side you want the button to display and it\'s unit.', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-distance-from-side-unit',
											'name'      => 'menu[button_distance_from_side_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => '%',
											'value'     => rmp_get_value( $options, 'button_distance_from_side_unit' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Distance from Top', 'responsive-menu' ),
										'type'     => 'number',
										'id'       => 'rmp-menu-button-top',
										'name'     => 'menu[button_top]',
										'value'    => rmp_get_value( $options, 'button_top' ),
										'class'    => 'no-updates',
										'tool_tip' => array(
											'text' => esc_html__( 'Specify how far from the top you want the button to display and it\'s unit.', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-top-unit',
											'name'      => 'menu[button_top_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'button_top_unit' ),
										),
									)
								);

								$ui_manager->end_group_controls();

								$control_manager->add_switcher_control(
									array(
										'label'      => esc_html__( 'Push Button with Menu', 'responsive-menu' ),
										'id'         => 'rmp-menu-button-push-animation',
										'tool_tip'   => array(
											'text' => esc_html__( 'The toggle button will slide along with menu container.', 'responsive-menu' ),
										),
										'name'       => 'menu[button_push_with_animation]',
										'is_checked' => is_rmp_option_checked( 'on', $options, 'button_push_with_animation' ),

									)
								);


								$ui_manager->end_accordion_item();

								// Toggle Type
								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Button Type', 'responsive-menu' ),
										),
										'feature_type' => 'semi-pro',
									)
								);

								$ui_manager->start_tabs_controls_panel(
									array(
										'tab_classes' => 'rmp-tab-content',
										'tab_items'   =>
										array(
											0 => array(
												'item_class'  => 'nav-tab-active',
												'item_target' => 'hamburger-type-line',
												'item_text'   => esc_html__( 'Hamburger', 'responsive-menu' ),
											),
											1 => array(
												'item_class' => '',
												'item_target' => 'hamburger-type-icon',
												'item_text' => esc_html__( 'Icon', 'responsive-menu' ),
											),
											2 => array(
												'item_class' => '',
												'item_target' => 'hamburger-type-image',
												'item_text' => esc_html__( 'Image', 'responsive-menu' ),
											),
										),
									)
								);

								$ui_manager->start_tab_item(
									array(
										'item_id'    => 'hamburger-type-line',
										'item_class' => 'title-contents',
									)
								);

								$ui_manager->start_group_controls();
								$control_manager->add_select_control(
									array(
										'label'   => esc_html__( 'Animation', 'responsive-menu' ),
										'id'      => 'rmp-menu-button-click-animation',
										'class'   => 'no-updates',
										'name'    => 'menu[button_click_animation]',
										'options' => rmp_hamburger_type_animation_options(),
										'value'   => rmp_get_value( $options, 'button_click_animation' ),
									)
								);


								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Line Spacing', 'responsive-menu' ),
										'type'     => 'number',
										'id'       => 'rmp-menu-button-line-margin',
										'name'     => 'menu[button_line_margin]',
										'value'    => rmp_get_value( $options, 'button_line_margin' ),

										'tool_tip' => array(
											'text' => esc_html__( 'Set the margin between each individual button line and it\'s unit', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-line-margin-unit',
											'name'      => 'menu[button_line_margin_unit]',
											'classes'   => 'is-unit',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'button_line_margin_unit' ),
										),
									)
								);
								$ui_manager->end_group_controls();

								$ui_manager->start_group_controls();
								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Line Width', 'responsive-menu' ),
										'type'     => 'number',
										'class'    => 'no-updates',
										'id'       => 'rmp-menu-button-line-width',
										'name'     => 'menu[button_line_width]',
										'value'    => rmp_get_value( $options, 'button_line_width' ),
										'tool_tip' => array(
											'text' => esc_html__( 'Set the width of each individual button line and it\'s unit', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-line-width-unit',
											'name'      => 'menu[button_line_width_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'button_line_width_unit' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Line Height', 'responsive-menu' ),
										'type'     => 'number',
										'class'    => 'no-updates',
										'id'       => 'rmp-menu-button-line-height',
										'name'     => 'menu[button_line_height]',
										'value'    => rmp_get_value( $options, 'button_line_height' ),

										'tool_tip' => array(
											'text' => esc_html__( 'Set the height of each individual button line and it\'s unit', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-line-height-unit',
											'name'      => 'menu[button_line_height_unit]',
											'classes'   => 'is-unit',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'button_line_height_unit' ),
										),
									)
								);

								$ui_manager->end_group_controls();

								$ui_manager->start_group_controls();
								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Line Color', 'responsive-menu' ),
										'id'    => 'rmp-menu-button-line-colour',
										'name'  => 'menu[button_line_colour]',
										'value' => rmp_get_value( $options, 'button_line_colour' ),

									)
								);

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Line Hover', 'responsive-menu' ),
										'id'    => 'rmp-menu-button-line-colour-hover',
										'name'  => 'menu[button_line_colour_hover]',
										'value' => rmp_get_value( $options, 'button_line_colour_hover' ),

									)
								);
								$ui_manager->end_group_controls();


								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Line Active', 'responsive-menu' ),
										'id'    => 'rmp-menu-button-line-colour-active',
										'name'  => 'menu[button_line_colour_active]',
										'value' => rmp_get_value( $options, 'button_line_colour_active' ),

									)
								);

								$ui_manager->end_tab_item();


								$ui_manager->start_tab_item(
									array(
										'item_id'    => 'hamburger-type-icon',
										'item_class' => 'title-contents',
									)
								);

								$control_manager->add_icon_picker_control(
									array(
										'label'         => esc_html__( 'Font Icon', 'responsive-menu' ),
										'id'            => 'rmp-menu-button-font-icon',
										'class'         => 'no-updates',
										'group_classes' => 'full-size',
										'picker_class'  => 'rmp-menu-font-icon-picker-button',
										'picker_id'     => 'rmp-menu-button-font-icon-selector',
										'name'          => 'menu[button_font_icon]',
										'tool_tip'      => array(
											'text' => esc_html__( 'Use a custom font icon instead of standard hamburger lines', 'responsive-menu' ),
										),
										'value'         => rmp_get_value( $options, 'button_font_icon' ),

									)
								);

								$control_manager->add_icon_picker_control(
									array(
										'label'         => esc_html__( 'Active Font Icon', 'responsive-menu' ),
										'id'            => 'rmp-menu-button-font-icon-when-clicked',
										'group_classes' => 'full-size',
										'picker_class'  => 'rmp-menu-font-icon-picker-button',
										'picker_id'     => 'rmp-menu-button-font-icon-when-clicked-selector',
										'name'          => 'menu[button_font_icon_when_clicked]',
										'value'         => rmp_get_value( $options, 'button_font_icon_when_clicked' ),
									)
								);


								$ui_manager->end_tab_item();

								$ui_manager->start_tab_item(
									array(
										'item_id'    => 'hamburger-type-image',
										'item_class' => 'title-contents',
									)
								);

								$control_manager->add_image_control(
									array(
										'label'         => esc_html__( 'Image', 'responsive-menu' ),
										'group_classes' => 'full-size',
										'id'            => 'rmp-menu-button-image',
										'picker_class'  => 'rmp-menu-button-image-selector',
										'picker_id'     => 'rmp-menu-button-image-selector',
										'name'          => 'menu[button_image]',
										'tool_tip'      => array(
											'text' => esc_html__( 'Use a custom image instead of standard hamburger lines.', 'responsive-menu' ),
										),
										'value'         => rmp_get_value( $options, 'button_image' ),
									)
								);

								$control_manager->add_image_control(
									array(
										'label'         => esc_html__( 'Active Image', 'responsive-menu' ),
										'group_classes' => 'full-size',
										'id'            => 'rmp-menu-button-image-when-clicked',
										'picker_class'  => 'rmp-menu-button-image-when-clicked-selector',
										'picker_id'     => 'rmp-menu-button-image-when-clicked-selector',
										'name'          => 'menu[button_image_when_clicked]',
										'value'         => rmp_get_value( $options, 'button_image_when_clicked' ),
									)
								);

								$ui_manager->end_tab_item();

								$ui_manager->end_accordion_item();

								// Toggle Title
								$ui_manager->start_accordion_item(
									array(
										'item_header' => array(
											'item_title' => esc_html__( 'Button Text', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->start_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label'       => esc_html__( 'Text', 'responsive-menu' ),
										'id'          => 'rmp-menu-button-title',
										'type'        => 'text',
										'class'       => 'no-updates',
										'placeholder' => esc_html__( 'Enter text', 'responsive-menu' ),
										'name'        => 'menu[button_title]',
										'value'       => rmp_get_value( $options, 'button_title' ),
										'tool_tip'    => array(
											'text' => esc_html__( 'Add text along with hamburger icon/image when button is in active state.', 'responsive-menu' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'       => esc_html__( 'Active Text', 'responsive-menu' ),
										'id'          => 'rmp-menu-button-title-open',
										'name'        => 'menu[button_title_open]',
										'class'       => 'no-updates',
										'placeholder' => esc_html__( 'Enter text', 'responsive-menu' ),
										'type'        => 'text',
										'value'       => rmp_get_value( $options, 'button_title_open' ),

									)
								);

								$ui_manager->end_group_controls();

								$ui_manager->start_group_controls();
								$control_manager->add_select_control(
									array(
										'label'   => esc_html__( 'Text Position', 'responsive-menu' ),
										'id'      => 'rmp-menu-button-title-position',
										'class'   => 'no-updates',
										'class'   => 'rmp-menu-button-title-position',
										'name'    => 'menu[button_title_position]',
										'options' => array(
											'top'    => 'Top',
											'left'   => 'Left',
											'bottom' => 'Bottom',
											'right'  => 'Right',
										),
										'value'   => rmp_get_value( $options, 'button_title_position' ),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'       => esc_html__( 'Font Family', 'responsive-menu' ),
										'id'          => 'rmp-menu-button-font',
										'class'       => 'no-updates',
										'name'        => 'menu[button_font]',
										'placeholder' => esc_html__( 'Enter font', 'responsive-menu' ),
										'type'        => 'text',
										'value'       => rmp_get_value( $options, 'button_font' ),
									)
								);
								$ui_manager->end_group_controls();

								$ui_manager->start_group_controls();
								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Font Size', 'responsive-menu' ),
										'type'     => 'number',
										'class'    => 'no-updates',
										'id'       => 'rmp-menu-button-font-size',
										'name'     => 'menu[button_font_size]',
										'value'    => rmp_get_value( $options, 'button_font_size' ),
										'class'    => 'no-updates',
										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-font-size-unit',
											'name'      => 'menu[button_font_size_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'button_font_size_unit' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Line Height', 'responsive-menu' ),
										'type'     => 'number',
										'class'    => 'no-updates',
										'id'       => 'rmp-menu-button-title-line-height',
										'name'     => 'menu[button_title_line_height]',
										'value'    => rmp_get_value( $options, 'button_title_line_height' ),

										'has_unit' => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-button-title-line-height-unit',
											'name'      => 'menu[button_title_line_height_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'button_title_line_height_unit' ),
										),
									)
								);

								$ui_manager->end_group_controls();

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Text Color', 'responsive-menu' ),
										'id'    => 'rmp-menu-button-text-colour',
										'name'  => 'menu[button_text_colour]',
										'value' => rmp_get_value( $options, 'button_text_colour' ),

									)
								);

								$ui_manager->end_accordion_item();

								// Toggle behaviour
								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Button Behaviour', 'responsive-menu' ),
										),
										'feature_type' => 'semi-pro',
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Toggle menu on click', 'responsive-menu' ),
										'id'           => 'rmp-menu-button-trigger-type-click',
										'class'        => 'rmp-menu-button-trigger-type',
										'name'         => 'menu[button_trigger_type_click]',
										'feature_type' => 'pro',
										'is_checked'   => 'checked',
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Toggle menu on hover', 'responsive-menu' ),
										'id'           => 'rmp-menu-button-trigger-type-hover',
										'class'        => 'rmp-menu-button-trigger-type',
										'name'         => 'menu[button_trigger_type_hover]',
										'feature_type' => 'pro',
										'is_checked'   => is_rmp_option_checked( 'on', $options, 'button_trigger_type_hover' ),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Custom Toggle Selector', 'responsive-menu' ),
										'type'          => 'text',
										'group_classes' => 'full-size',
										'id'            => 'rmp-menu-button-click-trigger',
										'name'          => 'menu[button_click_trigger]',
										'value'         => rmp_get_value( $options, 'button_click_trigger' ),
										'tool_tip'      => array(
											'text' => esc_html__( 'If you don\'t want to use the button that comes with the menu, you can specify your own container trigger here. Any CSS selector is accepted.', 'responsive-menu' ),
										),
									)
								);

								$ui_manager->end_accordion_item();
								?>
						</ul>
					</div>

					<div id="tab-container" class="rmp-accordions" aria-label="Container">
						<div class="rmp-order-item rmp-order-item-description rmp-ignore-accordion">
							<?php echo esc_html__( 'Drag the container items up and down to re-order their appearance on the front end.', 'responsive-menu' ); ?>
						</div>
						<ul class="rmp-accordion-container" id="rmp-menu-ordering-items">

							<?php

							if ( ! empty( $options['items_order'] ) ) {
								foreach ( $options['items_order'] as $key => $value ) {
									if ( 'menu' === $key ) {
										include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/menu.php';
									} elseif ( 'title' === $key ) {
										include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/title.php';
									} elseif ( 'search' === $key ) {
										include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/search.php';
									} else {
										include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/additional-content.php';
									}
								}
							}

								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Appearance', 'responsive-menu' ),
										),
										'feature_type' => 'semi-pro',
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Width', 'responsive-menu' ),
										'type'          => 'number',
										'id'            => 'rmp-menu-container-width',
										'class'         => 'no-updates',
										'name'          => 'menu[menu_width]',
										'value'         => rmp_get_value( $options, 'menu_width' ),
										'multi_device'  => true,
										'group_classes' => 'full-size',
										'placeholder'   => esc_html__( 'Enter value', 'responsive-menu' ),
										'has_unit'      => array(
											'unit_type'    => 'all',
											'id'           => 'rmp-menu-container-width-unit',
											'name'         => 'menu[menu_width_unit]',
											'default'      => '%',
											'classes'      => 'is-unit no-updates',
											'value'        => rmp_get_value( $options, 'menu_width_unit' ),
											'multi_device' => true,
										),
									)
								);

								$ui_manager->start_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label'       => esc_html__( 'Maximum Width', 'responsive-menu' ),
										'type'        => 'number',
										'class'       => 'no-updates',
										'id'          => 'rmp-menu-container-max-width',
										'name'        => 'menu[menu_maximum_width]',
										'value'       => rmp_get_value( $options, 'menu_maximum_width' ),
										'placeholder' => esc_html__( 'Enter value', 'responsive-menu' ),
										'has_unit'    => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-container-max-width-unit',
											'name'      => 'menu[menu_maximum_width_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'menu_maximum_width_unit' ),
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'       => esc_html__( 'Minimum Width', 'responsive-menu' ),
										'type'        => 'number',
										'class'       => 'no-updates',
										'id'          => 'rmp-menu-container-min-width',
										'name'        => 'menu[menu_minimum_width]',
										'value'       => rmp_get_value( $options, 'menu_minimum_width' ),
										'placeholder' => esc_html__( 'Enter value', 'responsive-menu' ),
										'has_unit'    => array(
											'unit_type' => 'all',
											'id'        => 'rmp-menu-container-min-width-unit',
											'name'      => 'menu[menu_minimum_width_unit]',
											'classes'   => 'is-unit no-updates',
											'default'   => 'px',
											'value'     => rmp_get_value( $options, 'menu_minimum_width_unit' ),
										),
									)
								);

								$ui_manager->end_group_controls();

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Auto Height', 'responsive-menu' ),
										'id'           => 'rmp-menu-container-height',
										'class'        => 'rmp-menu-container-height',
										'tool_tip'     => array(
											'text' => esc_html__( 'Limit container height upto last container element', 'responsive-menu' ),
										),
										'feature_type' => 'pro',
										'name'         => 'menu_auto_height',
										'is_checked'   => '',
									)
								);

								$control_manager->add_group_text_control(
									array(
										'label'         => esc_html__( 'Padding', 'responsive-menu' ),
										'type'          => 'text',
										'class'         => 'rmp-menu-container-padding',
										'name'          => 'menu[menu_container_padding]',
										'input_options' => array( 'top', 'right', 'bottom', 'left' ),
										'value_options' => ! empty( $options['menu_container_padding'] ) ? $options['menu_container_padding'] : '',
									)
								);

								$ui_manager->accordion_divider();

								$control_manager->add_color_control(
									array(
										'label' => esc_html__( 'Container Background', 'responsive-menu' ),
										'id'    => 'rmp-container-background-colour',
										'name'  => 'menu[menu_container_background_colour]',
										'value' => rmp_get_value( $options, 'menu_container_background_colour' ),
									)
								);

								$control_manager->add_shortcut_link(
									array(
										'label'        => 'Change Menu Background',
										'target'       => 'tab-menu-styling',
										'accordion_id' => 'ui-id-36',
									)
								);

								$control_manager->add_shortcut_link(
									array(
										'label'            => 'Style Menu Items',
										'target'           => 'tab-menu-styling',
										'accordion_id'     => 'ui-id-40',
										'sub_accordion_id' => 'ui-id-52',
									)
								);

								$control_manager->add_image_control(
									array(
										'label'         => esc_html__( 'Background Image', 'responsive-menu' ),
										'group_classes' => 'full-size',
										'id'            => 'rmp-menu-background-image',
										'picker_class'  => 'rmp-menu-background-image-selector',
										'picker_id'     => 'rmp-menu-background-image-selector',
										'name'          => 'menu[menu_background_image]',
										'value'         => rmp_get_value( $options, 'menu_background_image' ),

									)
								);

								$ui_manager->accordion_divider();

								$control_manager->add_sub_heading(
									array( 'text' => esc_html__( 'Animation', 'responsive-menu' ) )
								);

								$ui_manager->start_group_controls();

								$control_manager->add_select_control(
									array(
										'label'   => esc_html__( 'Type', 'responsive-menu' ),
										'id'      => 'rmp-animation-type',
										'name'    => 'menu[animation_type]',
										'options' => array(
											'slide' => 'Slide',
											'push'  => 'Push',
											'fade'  => 'Fade',
										),
										'value'   => rmp_get_value( $options, 'animation_type' ),
									)
								);

								$control_manager->add_select_control(
									array(
										'label'    => esc_html__( 'Direction', 'responsive-menu' ),
										'id'       => 'rmp-menu-appear-from',
										'tool_tip' => array(
											'text' => esc_html__( 'Set the viewport side for container entry.', 'responsive-menu' ),
										),
										'name'     => 'menu[menu_appear_from]',
										'options'  => array(
											'left'   => 'Left',
											'right'  => 'Right',
											'top'    => 'Top',
											'bottom' => 'Bottom',
										),
										'value'    => rmp_get_value( $options, 'menu_appear_from' ),
									)
								);

								$ui_manager->end_group_controls();

								$control_manager->add_text_input_control(
									array(
										'label'    => esc_html__( 'Transition delay', 'responsive-menu' ),
										'type'     => 'text',
										'id'       => 'rmp-menu-animation-speed',
										'name'     => 'menu[animation_speed]',
										'value'    => rmp_get_value( $options, 'animation_speed' ),
										'tool_tip' => array(
											'text' => esc_html__( 'Control the speed of animation for container entry and exit.', 'responsive-menu' ),
										),
										'has_unit' => array(
											'unit_type' => 's',
										),
									)
								);

								$control_manager->add_text_input_control(
									array(
										'label'         => esc_html__( 'Push Wrapper', 'responsive-menu' ),
										'group_classes' => 'full-size',
										'type'          => 'text',
										'tool_tip'      => array(
											'text' => esc_html__( 'Mention the CSS selector of the main element which should be pushed when using push animations.', 'responsive-menu' ),
										),
										'placeholder'   => esc_html__( 'CSS Selector', 'responsive-menu' ),
										'id'            => 'rmp-page-wrapper',
										'name'          => 'menu[page_wrapper]',
										'value'         => rmp_get_value( $options, 'page_wrapper' ),
									)
								);

								$ui_manager->end_accordion_item();

								$ui_manager->start_accordion_item(
									array(
										'item_header'  => array(
											'item_title' => esc_html__( 'Behaviour', 'responsive-menu' ),
										),
										'feature_type' => 'semi-pro',
									)
								);

								$control_manager->add_sub_heading(
									array( 'text' => esc_html__( 'Hide Menu On', 'responsive-menu' ) )
								);

								$control_manager->add_switcher_control(
									array(
										'label'      => esc_html__( 'Page Clicks', 'responsive-menu' ),
										'id'         => 'rmp-menu-close-on-page-click',
										'name'       => 'menu[menu_close_on_body_click]',
										'is_checked' => is_rmp_option_checked( 'on', $options, 'menu_close_on_body_click' ),
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'      => esc_html__( 'Link Clicks', 'responsive-menu' ),
										'id'         => 'rmp-menu-close-on-link-click',
										'name'       => 'menu[menu_close_on_link_click]',
										'is_checked' => is_rmp_option_checked( 'on', $options, 'menu_close_on_link_click' ),
									)
								);

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Page Scroll', 'responsive-menu' ),
										'id'           => 'rmp-menu-close-on-page-scroll',
										'name'         => 'menu_close_on_scroll',
										'feature_type' => 'pro',
										'is_checked'   => '',
									)
								);

								$ui_manager->accordion_divider();

								$control_manager->add_switcher_control(
									array(
										'label'        => esc_html__( 'Enable Touch Gestures', 'responsive-menu' ),
										'id'           => 'rmp-menu-touch-gestures',
										'tool_tip'     => array(
											'text' => esc_html__( 'This will enable you to drag or swipe to close the container on touch devices.', 'responsive-menu' ),
										),
										'feature_type' => 'pro',
										'name'         => 'enable_touch_gestures',
										'is_checked'   => '',
									)
								);

								$ui_manager->accordion_divider();

								$control_manager->add_sub_heading(
									array(
										'text'     => esc_html__( 'Keyboard Controls', 'responsive-menu' ),
										'tool_tip' => array(
											'text' => esc_html__( 'Select keystrokes to control the menu via keyboard.', 'responsive-menu' ),
										),
									)
								);

								$keys = rmp_get_menu_open_close_keys();

								$control_manager->add_select_control(
									array(
										'label'         => esc_html__( 'Hide Menu', 'responsive-menu' ),
										'id'            => 'rmp-keyboard-shortcut-close-menu',
										'class'         => 'rmp-keyboard-shortcut-close-menu',
										'group_classes' => 'full-size',
										'multiple'      => true,
										'name'          => 'keyboard_shortcut_open_menu[]',
										'options'       => $keys,
										'value'         => array(
											0 => '27',
											1 => 40,
										),
										'feature_type'  => 'pro',
									)
								);

								$control_manager->add_select_control(
									array(
										'label'         => esc_html__( 'Show Menu ', 'responsive-menu' ),
										'id'            => 'rmp-keyboard-shortcut-open-menu',
										'class'         => 'rmp-keyboard-shortcut-open-menu',
										'group_classes' => 'full-size',
										'multiple'      => true,
										'name'          => 'keyboard_shortcut_open_menu[]',
										'value'         => array(
											0 => '13',
											1 => 38,
										),
										'options'       => $keys,
										'feature_type'  => 'pro',
									)
								);

								$ui_manager->end_accordion_item();
								?>
						</ul>
					</div>
				</div>
				<?php
					// RMP Customize-Footer
					$editor->footer_section();
				?>
			</form>

			<main id="rmp-editor-preview" class="rmp-editor-preview-main">
				<div id="rmp-preview-wrapper" class="rmp-preview-wrapper">
					<div id="rmp-preview-iframe-loader">
						<img src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/giphy.webp' ); ?>" alt="loading" />
					</div>
					<iframe id="rmp-preview-iframe" src="<?php echo esc_url( get_site_url() . '?rmp_preview_mode=true' ); ?>"></iframe>
				</div>
			</main>

			<?php $editor->sidebar_drawer(); ?>
		</div>
		<?php
			require_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-wizards.php';
			do_action( 'admin_print_footer_scripts' );
		?>

		<div id="rmp-required-footer">
			<?php wp_footer(); ?>
		</div>

		<!-- Page loader -->
		<div class="rmp-page-loader">
			<img class="rmp-loader-image large" src="<?php echo esc_url( RMP_PLUGIN_URL_V4 . '/assets/images/rmp-logo.png' ); ?>"/>
			<h3 class="rmp-loader-message"><?php esc_html_e( 'Just a moment, the theme is applying...', 'responsive-menu' ); ?> </h3>
		</div>

	</body>
</html>
