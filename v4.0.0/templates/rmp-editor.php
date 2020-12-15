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

$body_classes = [
	'rmp-editor-active',
	'wp-version-' . str_replace( '.', '-', $wp_version ),
	'rmp-version-' . str_replace( '.', '-', RMP_PLUGIN_VERSION ),
];

$body_classes = array_merge( get_body_class(), $body_classes );
if ( is_rtl() ) {
	$body_classes[] = 'rtl';
}

$option_manager  = Option_Manager::get_instance();
$control_manager = Control_Manager::get_instance();
$ui_manager      = UI_Manager::get_instance();
$theme_manager   = Theme_Manager::get_instance();
$editor          = Editor::get_instance();
$menu_id = get_the_ID();
$options = $option_manager->get_options( $menu_id );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo __( 'rmp-menu-editor', 'responsive-menu-pro' ) . ' | ' . get_the_title(); ?></title>
</head>
	<body class="wp-admin wp-core-ui js post-php post-type-rmp_menu <?php echo implode( ' ', $body_classes ); ?>">
		<div id="rmp-editor-wrapper" class="rmp-editor-overlay expanded rmp-preview-mobile">
			<form method="post" enctype="multipart/form-data" id="rmp-editor-form" class="rmp-editor-sidebar">
				<input type="hidden" name="rmp_device_mode" id="rmp_device_mode" value="mobile"/>
				<input type="hidden" id="menu_id" name="menu_id" value="<?php echo get_the_ID(); ?>"/>
				<?php
					echo $editor->header_section( $options['menu_name'] );
				?>

				<div id="rmp-editor-main">
					<div id="rmp-editor-nav" class="rmp-editor-controls-nav" role="navigation" aria-label="<?php echo $options['menu_name']; ?>">
						<ul id="rmp-editor-pane" class="rmp-editor-pane-parent">
							<li id="rmp-tab-item-mobile-menu" class="rmp-tab-item" aria-owns="tab-mobile-menu">
								<span class="rmp-tab-item-icon">
									<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/mobile.svg' ); ?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Mobile Menu', 'responsive-menu-pro' ); ?></h3>
							</li>

							<li id="rmp-tab-item-desktop-menu" class="rmp-tab-item" aria-owns="tab-desktop-menu">
								<span class="rmp-tab-item-icon">
									<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/desktop.svg' ); ?>
								</span>
								<h3 class="rmp-tab-item-title">
									<span> <?php esc_html_e( 'Desktop Menu', 'responsive-menu-pro' ); ?></span>
									<a target="_blank" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php echo __('Pro','responsive-menu-pro'); ?> </a>
								</h3>
							</li>

							<li id="rmp-tab-item-dropdowns" class="rmp-tab-item" aria-owns="tab-menu-styling">
								<span class="rmp-tab-item-icon">
									<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/dropdowns.svg' ); ?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Menu Styling', 'responsive-menu-pro' ); ?></h3>
							</li>
							
							<li id="rmp-tab-item-header-bar" class="rmp-tab-item" aria-owns="tab-header-bar">
								<span class="rmp-tab-item-icon">
									<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/header.svg' ); ?>
								</span>
								<h3 class="rmp-tab-item-title">
									<span><?php esc_html_e( 'Header Bar', 'responsive-menu-pro' ); ?></span>
									<a target="_blank" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php echo __('Pro','responsive-menu-pro'); ?> </a>
								</h3>
							</li>

							<li id="rmp-tab-item-themes" class="rmp-tab-item" aria-owns="tab-themes">
								<span class="rmp-tab-item-icon">
									<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/advanced.svg' ); ?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Themes', 'responsive-menu-pro' ); ?></h3>
							</li>

							<li id="rmp-tab-item-settings" class="rmp-tab-item" aria-owns="tab-settings">
								<span class="rmp-tab-item-icon">
									<?php echo file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/general.svg' ); ?>
								</span>
								<h3 class="rmp-tab-item-title"><?php esc_html_e( 'Settings', 'responsive-menu-pro' ); ?></h3>
							</li>
						</ul>
					</div>

					<div id="tab-themes" class="rmp-accordions" aria-label="Themes">
						<ul class="rmp-accordion-container" id="rmp-theme-items">
						<?php
							echo $ui_manager->start_accordion_item( [
								'item_header' => [
									'item_title' => __('Theme options','responsive-menu-pro'),
								]
							] );

							$theme_name = 'Default';
							if ( ! empty( $options['menu_theme'] ) ) {
								$theme_name = $options['menu_theme'];
							}

							echo $control_manager->add_hidden_control(
								[
									'value' => $theme_name,
									'name'  => 'menu[menu_theme]',
								]
							);

							$theme_type = 'default';
							if ( ! empty( $options['theme_type'] ) ) {
								$theme_type = $options['theme_type'];
							}

							echo $control_manager->add_hidden_control(
								[
									'value' => $theme_type,
									'name'  => 'menu[theme_type]',
								]
							);

							echo $control_manager->add_sub_heading(
								['text' => 'Theme Name - ' . $theme_name ]
							);

							echo $theme_manager->get_theme_thumbnail( $theme_name, $theme_type );


							echo $control_manager->add_button_control(
								[
									'label'  => __('Change Theme','responsive-menu-pro'),
									'id'     => 'rmp-change-theme-action',
									'group_classes' => 'full-size',
									'class' => 'rmp-theme-change-button',									
								] 
							);
							echo $ui_manager->accordion_divider();

							echo $control_manager->add_button_control(
								[
									'label'  => __('Save As Theme','responsive-menu-pro'),
									'id'     => 'rmp-theme-save-action',
									'group_classes' => 'full-size',
									'class' => 'rmp-theme-save-button',									
								] 
							);

						
							echo $ui_manager->end_accordion_item();
						?>
						</ul>
					</div>

					<?php 
					if ( ! empty( get_option('responsive_menu_version') ) ) {
						include_once RMP_PLUGIN_PATH_V4 . '/templates/legacy-settings.php';
					}
					?>

					<div id="tab-header-bar" class="rmp-accordions" aria-label="Header Bar">
						<?php
							echo $control_manager->upgrade_notice();
						?>
					</div>

					<div id="tab-advanced-settings" class="rmp-accordions" aria-label="Advanced">
						<ul class="rmp-accordion-container">
							<?php
								
								//Device Breakpoints
								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Menu Breakpoint','responsive-menu-pro'),
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Breakpoint','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-tablet-breakpoint',
									'name'   => 'menu[tablet_breakpoint]',
									'value'    => rmp_get_value($options,'tablet_breakpoint'),
									'tool_tip' => [
										'text' => __( 'Set the breakpoint below which you want hamburger menu', 'responsive-menu-pro' ),
									],
									'has_unit' => [
										'unit_type' => 'px',
									],
								] );
								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Animation Speeds','responsive-menu-pro'),
									]
								] );
								
								echo $ui_manager->start_group_controls();
								
								echo $control_manager->add_text_input_control( [
									'label'  => __('Colours','responsive-menu-pro'),
									'type'   => 'text',
									'id'     => 'rmp-menu-transition-speed',
									'name'   => 'menu[transition_speed]',
									'value'    => rmp_get_value($options,'transition_speed'),
									
									'tool_tip' => [
										'text' => __('Specify the speed at which colours transition from standard to active or hover states.','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 's',
									],
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Sub Menus','responsive-menu-pro'),
									'type'   => 'text',
									'id'     => 'rmp-sub-menu-speed',
									'name'   => 'menu[sub_menu_speed]',
									'value'    => rmp_get_value($options,'sub_menu_speed'),
									
									'tool_tip' => [
										'text' => __('Specify the speed at which the sub menus transition.','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 's',
									],
								] );

								echo $ui_manager->end_group_controls();

								
								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Technical','responsive-menu-pro'),
									],
									'feature_type' => 'pro'
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Trigger Menu on page load','responsive-menu-pro'),
									'id'     => 'rmp-show-menu-on-page-load',
									'class'  => 'rmp-show-menu-on-page-load',
									'tool_tip' => [
										'text' => __('The menu will appear in expanded state when the page loads.','responsive-menu-pro'),
									],
									'feature_type' => 'pro',
									'name'   => 'menu[show_menu_on_page_load]',
									'is_checked'   => '',
									
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Disable Background Scrolling','responsive-menu-pro'),
									'id'     => 'rmp-menu-disable-scrolling',
									'class'  => 'rmp-menu-disable-scrolling',
									'feature_type' => 'pro',
									'tool_tip' => [
										'text' => __('This will disable the background page scrolling.','responsive-menu-pro'),
									],
									'name'   => 'menu[menu_disable_scrolling]',
									'is_checked'   => ''
								] );

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Page Overlay','responsive-menu-pro')
									],
									'tool_tip' => [
										'text' => __('Put a backdrop when menu is active.','responsive-menu-pro'),
									],
									'feature_type' => 'pro',
									'item_content' => [
										'content_class' => 'upgrade-notice-contents'
									]
								]);

									echo $control_manager->upgrade_notice();

								echo $ui_manager->end_accordion_item();
									
							?>
						</ul>
					</div>

					<div id="tab-desktop-menu" class="rmp-accordions" aria-label="Desktop Menu">
						<?php
							echo $control_manager->upgrade_notice();
						?>
					</div>

					<div id="tab-mobile-menu" class="rmp-accordions" aria-label="Mobile Menu">
						<ul class="rmp-editor-pane-parent">
						<?php
							echo $ui_manager->add_editor_menu_item( [
								'item_class' => 'is-child-item',
								'aria_owns'  => 'tab-container',
								'item_header' => [
									'item_svg_icon'  => RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/container.svg',
									'item_title' => __('Container','responsive-menu-pro'),
								]
							] );

							echo $ui_manager->add_editor_menu_item( [
								'item_class' => 'is-child-item',
								'aria_owns'  => 'tab-toggle-button',
								'item_header' => [
									'item_svg_icon'  => RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/toggle.svg',
									'item_title' => __('Toggle button','responsive-menu-pro'),
								]
							] );
						?>
						</ul>
					</div>

					<div id="tab-settings" class="rmp-accordions" aria-label="Settings">
						<ul class="rmp-editor-pane-parent">
						<?php
							echo $ui_manager->add_editor_menu_item( [
								'item_class' => 'is-child-item rmp-tab-item-general-settings',
								'aria_owns'  => 'tab-general-settings',
								'item_header' => [
									'item_svg_icon'  => RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/general.svg',
									'item_title' => __('General Settings','responsive-menu-pro'),
								]
							] );

							echo $ui_manager->add_editor_menu_item( [
								'item_class' => 'is-child-item rmp-tab-item-advanced-settings',
								'aria_owns'  => 'tab-advanced-settings',
								'item_header' => [
									'item_svg_icon'  => RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/advanced.svg',
									'item_title' => __('Advanced Settings','responsive-menu-pro'),
								]
							] );

							if ( ! empty( get_option('responsive_menu_version') ) ) {
								echo $ui_manager->add_editor_menu_item( [
									'item_class' => 'is-child-item rmp-tab-item-legacy-settings',
									'aria_owns'  => 'tab-legacy-settings',
									'item_header' => [
										'item_svg_icon'  => RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/general.svg',
										'item_title' => __('Legacy Settings','responsive-menu-pro'),
									]
								] );
							}
						?>
						</ul>
					</div>

					<div id="tab-general-settings" class="rmp-accordions" aria-label="General Settings">
						<ul class="rmp-accordion-container">
							<?php

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Menu Settings','responsive-menu-pro'),
									],
									'feature_type' => 'semi-pro'
								]);

								echo $ui_manager->start_group_controls();
								
								echo $control_manager->add_text_input_control( [
									'label'  => __('Name','responsive-menu-pro'),
									'type'   => 'text',
									'id' => 'rmp-menu-name',
									'name'   => 'menu[menu_name]',
									'value'    => rmp_get_value( $options, 'menu_name' ),
								] );

								$label = sprintf( '%s <strong> %s </strong> <a href="%s" target="_blank"> %s </a>
										<br/> <strong> %s <a href="%s" target="_blank"> %s </a> %s </strong>',
										esc_html__( 'If no options appear here, make sure you have set them up under', 'responsive-menu-pro' ),
										esc_html__( 'Appearance > Menus or', 'responsive-menu-pro' ),
										esc_url( admin_url() . 'nav-menus.php' ),
										esc_html__('here', 'responsive-menu-pro' ),
										esc_html__('Please note that the', 'responsive-menu-pro' ),
										esc_url( admin_url() . 'nav-menus.php' ),
										esc_html__('Theme Location', 'responsive-menu-pro' ),
										esc_html__('option will take precedence over this.', 'responsive-menu-pro' )
								);

								$menus = wp_get_nav_menus();
								$wp_menu_list = [];
								foreach ( $menus as $menu ) {
									$wp_menu_list[ $menu->slug ] = $menu->name;
								}
								echo $control_manager->add_select_control( [
									'label'  => __('Choose WP Menu','responsive-menu-pro'),
									'id'     => 'rmp-menu-to-use',
									'tool_tip' => [
										'text' => $label,
									],
									'name'    => 'menu[menu_to_use]',
									'options' => $wp_menu_list,
									'value'   => rmp_get_value( $options, 'menu_to_use' ),
								] );
								echo $ui_manager->end_group_controls();

								echo $ui_manager->accordion_divider();

								echo $control_manager->add_switcher_control( [
									'label'  => __(' Use different menu for mobile & tablet ','responsive-menu-pro'),
									'group_classes' => 'full-size',
									'id'     => 'rmp-menu-different-menu-for-mobile',
									'class'  => 'rmp-menu-different-menu-for-mobile',
									'feature_type' => 'pro',
									'name'   => 'mobile_menu_to_use',
									'is_checked' => false
								] );

								echo $ui_manager->accordion_divider();
								echo $control_manager->add_device_visibility_control( $options );

								echo $ui_manager->accordion_divider();
								echo $control_manager->add_select_control( [
									'label'  => __('Display condition','responsive-menu-pro'),
									'id'     => 'rmp-menu-display-condition',
									'name'    => 'menu[menu_display_on]',
									'options' => [
										'all-pages'     => __( 'Show on all pages ', 'responsive-menu-pro' ),
										'shortcode'     => __( 'Use as shortcode', 'responsive-menu-pro' ),
										'exclude-pages' => __( 'Exclude some pages (PRO) ', 'responsive-menu-pro' ),
										'include-pages' => __( 'Include only pages (PRO)', 'responsive-menu-pro' )
									],
									'value'   => rmp_get_value( $options, 'menu_display_on' ),
								] );

								echo $ui_manager->accordion_divider();
								echo $control_manager->add_text_input_control( [
									'label'    => __('Hide Theme Menu','responsive-menu-pro'),
									'type'     => 'text',
									'group_classes' => 'full-size',
									'id'       => 'rmp-menu-to-hide',
									'name'     => 'menu[menu_to_hide]',
									'tool_tip' => [
										'text' => __('To hide your current theme menu you need to put the CSS selector here. Any legal CSS selection criteria is valid.','responsive-menu-pro' )
									],
									'value'    => rmp_get_value($options,'menu_to_hide'),
								] );

								echo $ui_manager->end_accordion_item();
							?>
						</ul>
					</div>

					<div id="tab-menu-styling" class="rmp-accordions" aria-label="Menu Styling">
						<ul class="rmp-accordion-container">
							<?php
							

							echo $ui_manager->start_accordion_item( [
								'item_header' => [
									'item_title' => __('Menu ','responsive-menu-pro'),
								],
								'feature_type' => 'semi-pro'
							] );
								echo $ui_manager->start_tabs_controls_panel(
									[ 'tab_classes' => 'rmp-tab-content',
										'tab_items'   =>
										[
											0 => [
												'item_class' => 'nav-tab-active',
												'item_target' => 'menu-item-contents',
												'item_text' => __('Contents ','responsive-menu-pro'),
											],
											1 => [
												'item_class' => '',
												'item_target' => 'menu-item-styling',
												'item_text' => __('Styling ','responsive-menu-pro'),
											]
										]
									]
								);

								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'menu-item-contents',
										'item_class' => 'title-contents',
									]
								);

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Menu Background','responsive-menu-pro'),
									'id'     => 'rmp-menu-background-colour',
									'name'    => 'menu[menu_background_colour]',
									'value'    => rmp_get_value($options,'menu_background_colour'),
								] );
					
								echo $control_manager->add_select_control( [
									'label'  => __('Depth Level','responsive-menu-pro'),
									'id'     => 'rmp-menu-depth',
									'tool_tip' => [
										'text' => __('Set the level of nesting for sub menus.','responsive-menu-pro'),
									],
									'name'    => 'menu[menu_depth]',
									'options' => array( '1'=>1, '2'=>2, '3' => 3, '4'=>4,'5'=>5 ),
									'value'   => rmp_get_value($options,'menu_depth'),
								] );
								
								echo $ui_manager->end_group_controls();

								echo $ui_manager->accordion_divider();
					
								echo $control_manager->add_switcher_control( [
									'label'  => __('Item Descriptions','responsive-menu-pro'),
									'id'     => 'rmp-menu-submenu-descriptions-on',
									'tool_tip' => [
										'text' => __('Show the description text of menu items. Description text should be set while creating WordPress menus.','responsive-menu-pro'),
									],
									'name'   => 'menu[submenu_descriptions_on]',
									'is_checked'   => is_rmp_option_checked('on', $options,'submenu_descriptions_on'),
								] );
					
								echo $ui_manager->accordion_divider();
								
							   
					
								echo $control_manager->add_text_input_control( [
									'label'  => __('Custom Walker','responsive-menu-pro'),
									'group_classes' => 'full-size',
									'type'   => 'text',
									'id'     => 'rmp-custom-walker',
									'tool_tip' => [
										'text' => __('Modify the HTML output by using a custom Walker class.','responsive-menu-pro'),
									],
									'name'    => 'menu[custom_walker]',
									'value'   => rmp_get_value($options,'custom_walker'),
								] );
								echo $ui_manager->end_tab_item();
								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'menu-item-styling',
										'item_class' => 'title-contents',
									]
								);
									echo $control_manager->add_switcher_control( [
										'label'  => __('Smooth Scroll Enabled','responsive-menu-pro'),
										'id'     => 'rmp-menu-smooth-scroll-on',
										'class'  => 'rmp-menu-smooth-scroll-on',
										'tool_tip' => [
											'text' => __('The webpage will scroll smoothly to their target sections on same page.','responsive-menu-pro'),
										],
										'name'   => 'smooth_scroll_on',
										'feature_type' => 'pro',
										'is_checked'   => ''
									] );
					
									echo $control_manager->add_text_input_control( [
										'label'  => __('Scroll Speed','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-menu-smooth-scroll-speed',
										'name'   => 'smooth_scroll_speed',
										'feature_type' => 'pro',
										'value'    => '0',
										'has_unit' => [
											'unit_type' => 'ms',
										],
									] );
								echo $ui_manager->end_tab_item();

							echo $ui_manager->end_accordion_item();

							echo $ui_manager->start_accordion_item( [
								'item_header' => [
									'item_title' => __('Item Icon','responsive-menu-pro'),
								],
								'feature_type' => 'pro',
								'item_content' => [
										'content_class' => 'upgrade-notice-contents'
								]
							] );
								echo $control_manager->upgrade_notice();
							echo $ui_manager->end_accordion_item();

							echo $ui_manager->start_accordion_item( [
								'item_header' => [
									'item_title' => __('Item Styling','responsive-menu-pro'),
								]
							] );

								echo $ui_manager->start_tabs_controls_panel(
									[ 'tab_classes' => 'rmp-tab-content',
										'tab_items'   =>
										[
											0 => [
												'item_class' => 'nav-tab-active',
												'item_target' => 'top-level-item-styling',
												'item_text' => __('Top Level','responsive-menu-pro'),
											],
											1 => [
												'item_class' => '',
												'item_target' => 'sub-level-item-styling',
												'item_text' => __('Sub Menu','responsive-menu-pro'),
											]
										]
									]
								);
	
								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'top-level-item-styling',
										'item_class' => 'title-contents',
									]
								);
								echo $control_manager->add_text_input_control( [
									'label'  => __('Item Height','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-links-height',
									'name'   => 'menu[menu_links_height]',
									'value'    => rmp_get_value($options,'menu_links_height'),
									'group_classes' => 'full-size',
									'multi_device' => true,
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-links-height-unit',
										'name' => 'menu[menu_links_height_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'menu_links_height_unit'),
										'multi_device' => true,
									],
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Line Height','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-links-line-height',
									'name'   => 'menu[menu_links_line_height]',
									'value'    => rmp_get_value($options,'menu_links_line_height'),
									'group_classes' => 'full-size',
									'multi_device' => true,
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-links-line-height-unit',
										'name' => 'menu[menu_links_line_height_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'menu_links_line_height_unit'),
										'multi_device' => true,
									],
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Padding','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-depth-level-0',
									'name'   => 'menu[menu_depth_0]',
									'value'    => rmp_get_value($options,'menu_depth_0'),
									'group_classes' => 'full-size',
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-depth-level-0-unit',
										'name' => 'menu[menu_depth_0_unit]',
										'classes' => 'is-unit',
										'default' => '%',
										'value' => rmp_get_value($options,'menu_depth_0_unit'),
									],
								] );

								echo $ui_manager->start_sub_accordion();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Typography','responsive-menu-pro')
									]
								] );
	
								echo $control_manager->add_text_input_control( [
									'label'  => __('Font Size','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-font-size',
									'name'   => 'menu[menu_font_size]',
									'class' => 'no-updates',
									'value'    => rmp_get_value($options,'menu_font_size'),
									'group_classes' => 'full-size',
									'multi_device' => true,
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-font-size-unit',
										'name' => 'menu[menu_font_size_unit]',
										'default' => 'px',
										'classes' => 'is-unit no-updates',
										'value' => rmp_get_value($options,'menu_font_size_unit'),
										'multi_device' => true,
									],
								] );

							

								echo $control_manager->add_text_input_control( [
									'label'  => __('Font Family','responsive-menu-pro'),
									'type'   => 'text',
									'id'     => 'rmp-menu-font',
									'name'   => 'menu[menu_font]',
									'class' => 'no-updates',
									'value'    => rmp_get_value($options,'menu_font'),
									'multi_device' => true,
								] );

								echo $control_manager->add_select_control( [
									'label'  => __('Font Weight','responsive-menu-pro'),
									'id'     => 'rmp-menu-font-weight',
									'class' => 'no-updates',
									'name'    => 'menu[menu_font_weight]',
									'options' => rmp_font_weight_options(),
									'value'   => rmp_get_value($options,'menu_font_weight'),
									'group_classes' => 'full-size',
								] );

								echo $control_manager->add_text_alignment_control( [
									'label'  => __('Text Alignment','responsive-menu-pro'),
									'class'   => 'rmp-menu-text-alignment',
									'name'    => 'menu[menu_text_alignment]',
									'options' => ['left','center','right','justify'],
									'value'    => rmp_get_value($options,'menu_text_alignment'),
									'group_classes' => 'full-size',
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Letter Spacing','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-text-letter-spacing',
									'name'   => 'menu[menu_text_letter_spacing]',
									'value'    => rmp_get_value($options,'menu_text_letter_spacing'),
									'group_classes' => 'full-size',
									'has_unit' => [
										'unit_type' => 'px',
									],
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Word Wrap','responsive-menu-pro'),
									'id'     => 'rmp-menu-word-wrap',
									'class'  => 'rmp-menu-word-wrap',
									'tool_tip' => [
										'text' => __('Allow the menu items to wrap around to the next line.','responsive-menu-pro'),
									],
									'name'   => 'menu[menu_word_wrap]',
									'is_checked'   => is_rmp_option_checked('on', $options,'menu_word_wrap'),
									
								] );

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Text Color','responsive-menu-pro')
									]
								] );


								echo $control_manager->add_color_control( [
									'label'  => __('Normal','responsive-menu-pro'),
									'id'     => 'rmp-menu-link-color',
									'name'    => 'menu[menu_link_colour]',
									'value'    => rmp_get_value($options,'menu_link_colour'),
									'multi_device' => true,
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-link-hover-color',
									'name'    => 'menu[menu_link_hover_colour]',
									'value'    => rmp_get_value($options,'menu_link_hover_colour'),
									
									'multi_device' => true,
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item','responsive-menu-pro'),
									'id'     => 'rmp-menu-current-link-active-color',
									'name'    => 'menu[menu_current_link_colour]',
									'value'    => rmp_get_value($options,'menu_current_link_colour'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-current-link-active-hover-color',
									'name'    => 'menu[menu_current_link_hover_colour]',
									'value'    => rmp_get_value($options,'menu_current_link_hover_colour'),
									
								] );
							
								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Background Color','responsive-menu-pro')
									]
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Background','responsive-menu-pro'),
									'id'     => 'rmp-menu-item-background-colour',
									'name'    => 'menu[menu_item_background_colour]',
									'value'    => rmp_get_value($options,'menu_item_background_colour'),
									
									'multi_device' => true,

								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Background Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-item-background-hover-color',
									'name'    => 'menu[menu_item_background_hover_colour]',
									'value'    => rmp_get_value($options,'menu_item_background_hover_colour'),
									
									'multi_device' => true,
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item Background','responsive-menu-pro'),
									'id'     => 'rmp-menu-current-item-background-color',
									'name'    => 'menu[menu_current_item_background_colour]',
									'value'    => rmp_get_value($options,'menu_current_item_background_colour'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item Background Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-current-item-background-hover-color',
									'name'    => 'menu[menu_current_item_background_hover_colour]',
									'value'    => rmp_get_value($options,'menu_current_item_background_hover_colour'),
									
								] );
								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Border','responsive-menu-pro')
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Border Width','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-border-width',
									'name'   => 'menu[menu_border_width]',
									'value'    => rmp_get_value($options,'menu_border_width'),
									'class' => 'no-updates',
									'tool_tip' => [
										'text' => __('Set the border size for each menu link and it\'s unit.', 'responsive-menu-pro'),
									],
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-border-width-unit',
										'name' => 'menu[menu_border_width_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'menu_border_width_unit'),
									],
								] );

								echo $ui_manager->start_group_controls();
									echo $control_manager->add_color_control( [
										'label'  => __('Normal','responsive-menu-pro'),
										'id'     => 'rmp-menu-item-border-colour',
										'name'    => 'menu[menu_item_border_colour]',
										'value'    => rmp_get_value($options,'menu_item_border_colour'),
										
									] );

									echo $control_manager->add_color_control( [
										'label'  => __('Hover','responsive-menu-pro'),
										'id'     => 'rmp-menu-item-border-colour-hover',
										'name'   => 'menu[menu_item_border_colour_hover]',
										'value'    => rmp_get_value($options,'menu_item_border_colour_hover'),
										
									] );
								echo $ui_manager->end_group_controls();
								
								echo $ui_manager->start_group_controls();
									echo $control_manager->add_color_control( [
										'label'  => __('Active Item','responsive-menu-pro'),
										'id'     => 'rmp-menu-button-line-colour-active',
										'name'   => 'menu[menu_current_item_border_colour]',
										'value'    => rmp_get_value($options,'menu_current_item_border_colour'),
										
									] );

									echo $control_manager->add_color_control( [
										'label'  => __('Active Hover','responsive-menu-pro'),
										'id'     => 'rmp-menu-current-item-border-hover-colour',
										'tool_tip' => [
											'text' => __('Set the border colour when the mouse rolls over the current menu item.','responsive-menu-pro'),
										],
										'name'   => 'menu[menu_current_item_border_hover_colour]',
										'value'    => rmp_get_value($options,'menu_current_item_border_hover_colour'),
										
									] );
								echo $ui_manager->end_group_controls();

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->end_sub_accordion();
								echo $ui_manager->end_tab_item();

								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'sub-level-item-styling',
										'item_class' => 'title-contents',
									]
								);
								echo $control_manager->add_text_input_control( [
									'label'  => __('Item Height','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-submenu-links-height',
									'name'   => 'menu[submenu_links_height]',
									'value'    => rmp_get_value($options,'submenu_links_height'),
									'multi_device' => true,
									'group_classes' => 'full-size',
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-submenu-links-height-unit',
										'name' => 'menu[submenu_links_height_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'submenu_links_height_unit'),
										'multi_device' => true,
									],
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Line Height','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-submenu-links-line-height',
									'name'   => 'menu[submenu_links_line_height]',
									'value'    => rmp_get_value($options,'submenu_links_line_height'),
									'multi_device' => true,
									'group_classes' => 'full-size',
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-submenu-links-line-height-unit',
										'name' => 'menu[submenu_links_line_height_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'submenu_links_line_height_unit'),
										'multi_device' => true,
									],
								] );

								echo $ui_manager->start_sub_accordion();
									echo $ui_manager->start_accordion_item( [
										'item_header' => [
											'item_title' => __('Indentation','responsive-menu-pro')
										]
									] );
									echo $control_manager->add_select_control( [
										'label'  => __('Side','responsive-menu-pro'),
										'id'     => 'rmp-menu-depth-side',
										'tool_tip' => [
											'text' => __('You can set which side of the menu items the padding should be on.','responsive-menu-pro'),
										],
										'name'    => 'menu[menu_depth_side]',
										'options' => array( 'right' => 'Right' , 'left' => 'Left' ),
										'value'   => rmp_get_value($options,'menu_depth_side'),
									] );
	
									
									echo $ui_manager->start_group_controls();
									echo $control_manager->add_text_input_control( [
										'label'  => __('Child Level 1','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-menu-depth-level-1',
										'name'   => 'menu[menu_depth_1]',
										'value'    => rmp_get_value($options,'menu_depth_1'),
										
										'has_unit' => [
											'unit_type' => 'all',
											'id' => 'rmp-menu-depth-level-1-unit',
											'name' => 'menu[menu_depth_1_unit]',
											'classes' => 'is-unit',
											'default' => '%',
											'value' => rmp_get_value($options,'menu_depth_1_unit'),
										],
									] );
	
									echo $control_manager->add_text_input_control( [
										'label'  => __('Child Level 2','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-menu-depth-level-2',
										'name'   => 'menu[menu_depth_2]',
										'value'    => rmp_get_value($options,'menu_depth_2'),
										
										'has_unit' => [
											'unit_type' => 'all',
											'id' => 'rmp-menu-depth-level-2-unit',
											'name' => 'menu[menu_depth_2_unit]',
											'classes' => 'is-unit',
											'default' => '%',
											'value' => rmp_get_value($options,'menu_depth_2_unit'),
										],
									] );
	
									echo $ui_manager->end_group_controls();

									echo $ui_manager->start_group_controls();
									echo $control_manager->add_text_input_control( [
										'label'  => __('Child Level 3','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-menu-depth-level-3',
										'name'   => 'menu[menu_depth_3]',
										'value'    => rmp_get_value($options,'menu_depth_3'),
										
										'has_unit' => [
											'unit_type' => 'all',
											'id' => 'rmp-menu-depth-level-3-unit',
											'name' => 'menu[menu_depth_3_unit]',
											'classes' => 'is-unit',
											'default' => '%',
											'value' => rmp_get_value($options,'menu_depth_3_unit'),
										],
									] );

									echo $control_manager->add_text_input_control( [
										'label'  => __('Child Level 4','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-menu-depth-level-4',
										'name'   => 'menu[menu_depth_4]',
										'value'    => rmp_get_value($options,'menu_depth_4'),
										
										'has_unit' => [
											'unit_type' => 'all',
											'id' => 'rmp-menu-depth-level-4-unit',
											'name' => 'menu[menu_depth_4_unit]',
											'classes' => 'is-unit',
											'default' => '%',
											'value' => rmp_get_value($options,'menu_depth_4_unit'),
										],
									] );
									echo $ui_manager->end_group_controls();

									echo $ui_manager->end_accordion_item();
									echo $ui_manager->start_accordion_item( [
										'item_header' => [
											'item_title' => __('Background Color','responsive-menu-pro')
										]
									] );
									echo $control_manager->add_color_control( [
										'label'  => __('Normal','responsive-menu-pro'),
										'id'     => 'rmp-submenu-item-background-color',
										'name'    => 'menu[submenu_item_background_colour]',
										'value'    => rmp_get_value($options,'submenu_item_background_colour'),
										'multi_device' => true,
									] );
	
									echo $control_manager->add_color_control( [
										'label'  => __('Hover','responsive-menu-pro'),
										'id'     => 'rmp-submenu-item-background-hover-color',
										'name'    => 'menu[submenu_item_background_hover_colour]',
										'value'    => rmp_get_value($options,'submenu_item_background_hover_colour'),
										
										'multi_device' => true,
									] );
	
									echo $ui_manager->start_group_controls();
									echo $control_manager->add_color_control( [
										'label'  => __('Active Item','responsive-menu-pro'),
										'id'     => 'rmp-submenu-current-item-background-color',
										'name'    => 'menu[submenu_current_item_background_colour]',
										'value'    => rmp_get_value($options,'submenu_current_item_background_colour'),
										
									] );
	
									echo $control_manager->add_color_control( [
										'label'  => __('Active Item Hover','responsive-menu-pro'),
										'id'     => 'rmp-submenu-current-item-background-hover-color',
										'name'    => 'menu[submenu_current_item_background_hover_colour]',
										'value'    => rmp_get_value($options,'submenu_current_item_background_hover_colour'),
										
									] );
									echo $ui_manager->end_group_controls();
									echo $ui_manager->end_accordion_item();
									echo $ui_manager->start_accordion_item( [
										'item_header' => [
											'item_title' => __('Border','responsive-menu-pro')
										]
									] );
									echo $control_manager->add_text_input_control( [
										'label'  => __('Border Width','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-submenu-border-width',
										'name'   => 'menu[submenu_border_width]',
										'value'    => rmp_get_value($options,'submenu_border_width'),
										'tool_tip' => [
											'text' => __('Set the border size for each menu link and it\'s unit.','responsive-menu-pro'),
										],
										'has_unit' => [
											'unit_type' => 'all',
											'id' => 'rmp-submenu-border-width-unit',
											'name' => 'menu[submenu_border_width_unit]',
											'classes' => 'is-unit',
											'value' => rmp_get_value($options,'submenu_border_width_unit'),
										],
									] );
	
									echo $ui_manager->start_group_controls();
									echo $control_manager->add_color_control( [
										'label'  => __('Normal','responsive-menu-pro'),
										'id'     => 'rmp-submenu-item-border-colour',
										'name'    => 'menu[submenu_item_border_colour]',
										'value'    => rmp_get_value($options,'submenu_item_border_colour'),
										
									] );
	
									echo $control_manager->add_color_control( [
										'label'  => __('Hover','responsive-menu-pro'),
										'id'     => 'rmp-submenu-item-border-colour-hover',
										'name'   => 'menu[submenu_item_border_colour_hover]',
										'value'    => rmp_get_value($options,'submenu_item_border_colour_hover'),
										
									] );
									echo $ui_manager->end_group_controls();

									echo $ui_manager->start_group_controls();
									echo $control_manager->add_color_control( [
										'label'  => __('Active Item','responsive-menu-pro'),
										'id'     => 'rmp-submenu-button-line-colour-active',
										'name'   => 'menu[submenu_current_item_border_colour]',
										'value'    => rmp_get_value($options,'submenu_current_item_border_colour'),
										
									] );
	
									echo $control_manager->add_color_control( [
										'label'  => __('Active Item Hover','responsive-menu-pro'),
										'id'     => 'rmp-submenu-current-item-border-hover-colour',
										'tool_tip' => [
											'text' => __('Set the border colour when the mouse rolls over the current submenu item.','responsive-menu-pro'),
										],
										'name'   => 'menu[submenu_current_item_border_hover_colour]',
										'value'    => rmp_get_value($options,'submenu_current_item_border_hover_colour'),
										
									] );
									echo $ui_manager->end_group_controls();
	
									echo $ui_manager->end_accordion_item();
									echo $ui_manager->start_accordion_item( [
										'item_header' => [
											'item_title' => __('Typography','responsive-menu-pro')
										]
									] );
									echo $control_manager->add_text_input_control( [
										'label'  => __('Font Size','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-submenu-font-size',
										'class' => 'no-updates',
										'name'   => 'menu[submenu_font_size]',
										'value'    => rmp_get_value($options,'submenu_font_size'),
										'multi_device' => true,
										'has_unit' => [
											'unit_type' => 'all',
											'id' => 'rmp-submenu-font-size-unit',
											'name' => 'menu[submenu_font_size_unit]',
											'classes' => 'is-unit',
											'default' => 'px',
											'value' => rmp_get_value($options,'submenu_font_size_unit'),
											'multi_device' => true,
										],
									] );
	
									echo $control_manager->add_text_input_control( [
										'label'  => __('Font Family','responsive-menu-pro'),
										'type'   => 'text',
										'id'     => 'rmp-submenu-font',
										'name'   => 'menu[submenu_font]',
										'class' => 'no-updates',
										'value'    => rmp_get_value($options,'submenu_font'),
										'multi_device' => true,
									] );
	
									echo $ui_manager->start_group_controls();

									echo $control_manager->add_select_control( [
										'label'   => __('Font Weight','responsive-menu-pro'),
										'id'      => 'rmp-submenu-font-weight',
										'name'    => 'menu[submenu_font_weight]',
										'class' => 'no-updates',
										'options' => rmp_font_weight_options(),
										'value'   => rmp_get_value($options,'submenu_font_weight'),
									] );

									echo $control_manager->add_text_input_control( [
										'label'  => __('Letter Spacing','responsive-menu-pro'),
										'type'   => 'number',
										'id'     => 'rmp-submenu-text-letter-spacing',
										'name'   => 'menu[submenu_text_letter_spacing]',
										'value'    => rmp_get_value($options,'submenu_text_letter_spacing'),
										'has_unit' => [
											'unit_type' => 'px',
										],
									] );
	
									echo $ui_manager->end_group_controls();


									echo $control_manager->add_text_alignment_control( [
										'label'    => __('Text Alignment','responsive-menu-pro'),
										'class'    => 'rmp-submenu-text-alignment',
										'name'     => 'menu[submenu_text_alignment]',
										'options'  => ['left','center','right','justify'],
										'value'    => rmp_get_value($options,'submenu_text_alignment'),
									] );


									echo $ui_manager->end_accordion_item();
									echo $ui_manager->start_accordion_item( [
										'item_header' => [
											'item_title' => __('Text Color','responsive-menu-pro')
										]
									] );
									echo $control_manager->add_color_control( [
										'label'        => __('Color','responsive-menu-pro'),
										'id'           => 'rmp-submenu-link-color',
										'name'         => 'menu[submenu_link_colour]',
										'value'        => rmp_get_value($options,'submenu_link_colour'),
										'multi_device' => true,
										
									] );

									echo $control_manager->add_color_control( [
										'label'  => __('Hover Color','responsive-menu-pro'),
										'id'     => 'rmp-submenu-link-hover-color',
										'name'    => 'menu[submenu_link_hover_colour]',
										'value'    => rmp_get_value($options,'submenu_link_hover_colour'),
										'multi_device' => true,
									] );

									echo $ui_manager->start_group_controls();
									echo $control_manager->add_color_control( [
										'label'  => __('Active Item Color','responsive-menu-pro'),
										'id'     => 'rmp-submenu-link-colour-active',
										'name'    => 'menu[submenu_current_link_colour]',
										'value'    => rmp_get_value($options,'submenu_current_link_colour'),
									] );

									echo $control_manager->add_color_control( [
										'label'  => __('Active Item Hover','responsive-menu-pro'),
										'id'     => 'rmp-submenu-link-active-hover-color',
										'name'    => 'menu[submenu_current_link_hover_colour]',
										'value'    => rmp_get_value($options,'submenu_current_link_hover_colour'),
									] );
									echo $ui_manager->end_group_controls();

									echo $ui_manager->end_accordion_item();
									echo $ui_manager->end_sub_accordion();
							
								echo $ui_manager->end_tab_item();

								echo $ui_manager->end_tabs_controls_panel();

							echo $ui_manager->end_accordion_item();


							echo $ui_manager->start_accordion_item( [
								'item_header' => [
									'item_title' => __('Trigger Icon','responsive-menu-pro'),
								]
							] );

							echo $ui_manager->start_tabs_controls_panel(
								[ 'tab_classes' => 'rmp-tab-content',
									'tab_items'   =>
									[
										0 => [
											'item_class' => 'nav-tab-active',
											'item_target' => 'menu-item-arrow-text',
											'item_text' => __('Text','responsive-menu-pro')
										],
										1 => [
											'item_class' => '',
											'item_target' => 'menu-item-arrow-icon',
											'item_text' => __('Icon','responsive-menu-pro')
										],
										2 => [
											'item_class' => '',
											'item_target' => 'menu-item-arrow-image',
											'item_text' => __('Image','responsive-menu-pro')
										],
									]
								]
							);

							echo $ui_manager->start_tab_item( 
								[
									'item_id'    => 'menu-item-arrow-text',
									'item_class' => 'title-contents',
								]
							);
							echo $ui_manager->start_group_controls();
							echo $control_manager->add_text_input_control( [
								'label'  => __('Text Shape','responsive-menu-pro'),
								'type'   => 'text',
								'id'     => 'rmp-menu-inactive-arrow-shape',
								'name'    => 'menu[inactive_arrow_shape]',
								'value'    => rmp_get_value($options,'inactive_arrow_shape'),
								
							] );

							echo $control_manager->add_text_input_control( [
								'label'  => __('Active Text Shape','responsive-menu-pro'),
								'type'   => 'text',
								'id'     => 'rmp-menu-active-arrow-shape',
								'name'    => 'menu[active_arrow_shape]',
								'value'    => rmp_get_value($options,'active_arrow_shape'),
								
							] );
							echo $ui_manager->end_group_controls();
							echo $ui_manager->end_tab_item();

							echo $ui_manager->start_tab_item( 
								[
									'item_id'    => 'menu-item-arrow-icon',
									'item_class' => 'title-contents',
								]
							);
							

							echo $control_manager->add_icon_picker_control( [
								'label'  => __('Font Icon','responsive-menu-pro'),
								'id'     => 'rmp-menu-inactive-arrow-font-icon',
								'group_classes' => 'full-size',
								'class' => 'no-updates',
								'picker_class'  => 'rmp-menu-font-icon-picker-button',
								'picker_id' => "rmp-menu-inactive-arrow-font-icon-selector",
								'name'    => 'menu[inactive_arrow_font_icon]',
								'value'    => rmp_get_value($options,'inactive_arrow_font_icon'),
								
							] );

							echo $control_manager->add_icon_picker_control( [
								'label'  => __('Active Font Icon','responsive-menu-pro'),
								'id'     => 'rmp-menu-active-arrow-font-icon',
								'group_classes' => 'full-size',
								'picker_class'  => 'rmp-menu-font-icon-picker-button',
								'picker_id' => "rmp-menu-active-arrow-font-icon-selector",
								'name'    => 'menu[active_arrow_font_icon]',
								'value'    => rmp_get_value($options,'active_arrow_font_icon'),
								
							] );

							echo $ui_manager->end_tab_item();


							echo $ui_manager->start_tab_item( 
								[
									'item_id'    => 'menu-item-arrow-image',
									'item_class' => 'title-style',
								]
							);

							echo $control_manager->add_image_control( [
								'label'  => __('Image','responsive-menu-pro'),
								'group_classes' => 'full-size',
								'id'     => 'rmp-menu-inactive-arrow-image',
								'picker_class'  => 'rmp-menu-inactive-arrow-image-selector',
								'picker_id' => "rmp-menu-inactive-arrow-image-selector",
								'name'    => 'menu[inactive_arrow_image]',
								'value'    => rmp_get_value($options,'inactive_arrow_image'),
								
							] );

							echo $control_manager->add_image_control( [
								'label'  => __('Active Image','responsive-menu-pro'),
								'group_classes' => 'full-size',
								'id'     => 'rmp-menu-active-arrow-image',
								'picker_class'  => 'rmp-menu-active-arrow-image-selector',
								'picker_id' => "rmp-menu-active-arrow-image-selector",
								'name'    => 'menu[active_arrow_image]',
								'value'    => rmp_get_value($options,'active_arrow_image'),
								
							] );
							echo $ui_manager->end_tab_item();

							echo $ui_manager->end_tabs_controls_panel();

							echo $ui_manager->accordion_divider();

							echo $ui_manager->start_group_controls();

							echo $control_manager->add_text_input_control( [
								'label'  => __('Width','responsive-menu-pro'),
								'type'   => 'number',
								'id'     => 'rmp-submenu-arrow-width',
								'name'   => 'menu[submenu_arrow_width]',
								'value'    => rmp_get_value($options,'submenu_arrow_width'),
								'class' => 'no-updates',
								'tool_tip' => [
									'text' => __('Set the width of the menu trigger items and their units.','responsive-menu-pro')
								],
								'has_unit' => [
									'unit_type' => 'all',
									'id' => 'rmp-submenu-arrow-width-unit',
									'name' => 'menu[submenu_arrow_width_unit]',
									'classes' => 'is-unit no-updates',
									'value' => rmp_get_value($options,'submenu_arrow_width_unit'),
								],
							] );

							echo $control_manager->add_text_input_control( [
								'label'  => __('Height','responsive-menu-pro'),
								'type'   => 'number',
								'id'     => 'rmp-submenu-arrow-height',
								'name'   => 'menu[submenu_arrow_height]',
								'value'    => rmp_get_value($options,'submenu_arrow_height'),
								'class' => 'no-updates',
								'tool_tip' => [
									'text' => __('Set the height of the menu trigger items and their units.','responsive-menu-pro')
								],
								'has_unit' => [
									'unit_type' => 'all',
									'id' => 'rmp-submenu-arrow-height-unit',
									'name' => 'menu[submenu_arrow_height_unit]',
									'classes' => 'is-unit no-updates',
									'value' => rmp_get_value($options,'submenu_arrow_height_unit')
								],
							] );

							echo $ui_manager->end_group_controls();

							echo $control_manager->add_select_control( [
								'label'  => __('Position','responsive-menu-pro'),
								'id'     => 'rmp-menu-arrow-position',
								'class'  => 'rmp-menu-arrow-position',
								'name'    => 'menu[arrow_position]',
								'options' => array( 'right' => 'Right' , 'left' => 'Left' ),
								'value'   => rmp_get_value($options,'arrow_position')
							] );

							echo $ui_manager->start_sub_accordion();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Text Color','responsive-menu-pro')
									]
								] );

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Normal','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-shape-colour',
									'name'    => 'menu[menu_sub_arrow_shape_colour]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_shape_colour'),
									
								] );
	
								echo $control_manager->add_color_control( [
									'label'  => __('Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-shape-hover-colour',
									'name'    => 'menu[menu_sub_arrow_shape_hover_colour]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_shape_hover_colour'),
									
								] );
									
								echo $ui_manager->end_group_controls();

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-shape-colour-active',
									'name'    => 'menu[menu_sub_arrow_shape_colour_active]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_shape_colour_active'),
									
								] );
	
								echo $control_manager->add_color_control( [
									'label'  => __('Active Item Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-shape-hover-colour-active',
									'name'    => 'menu[menu_sub_arrow_shape_hover_colour_active]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_shape_hover_colour_active'),
									
								] );
								echo $ui_manager->end_group_controls();

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Border Color','responsive-menu-pro')
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Border Width','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-sub-arrow-border-width',
									'name'   => 'menu[menu_sub_arrow_border_width]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_border_width'),
									'class' => 'no-updates',
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-sub-arrow-border-width-unit',
										'name' => 'menu[menu_sub_arrow_border_width_unit]',
										'classes' => 'is-unit',
										'value' => rmp_get_value($options,'menu_sub_arrow_border_width_unit'),
									],
								] );

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Normal','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-border-colour',
									'name'    => 'menu[menu_sub_arrow_border_colour]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_border_colour'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-border-hover-colour',
									'name'    => 'menu[menu_sub_arrow_border_hover_colour]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_border_hover_colour'),
									
								] );
								echo $ui_manager->end_group_controls();

								echo $ui_manager->start_group_controls();
								echo $control_manager->add_color_control( [
									'label'  => __('Active Item','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-border-colour-active',
									'name'    => 'menu[menu_sub_arrow_border_colour_active]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_border_colour_active'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-border-hover-colour-active',
									'name'    => 'menu[menu_sub_arrow_border_hover_colour_active]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_border_hover_colour_active'),
									
								] );
								echo $ui_manager->end_group_controls();

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Background Color','responsive-menu-pro')
									]
								] );

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Normal','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-background-color',
									'name'    => 'menu[menu_sub_arrow_background_colour]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_background_colour'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-background-hover-colour',
									'name'    => 'menu[menu_sub_arrow_background_hover_colour]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_background_hover_colour'),
									
								] );
								echo $ui_manager->end_group_controls();
								echo $ui_manager->start_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-background-colour-active',
									'name'    => 'menu[menu_sub_arrow_background_colour_active]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_background_colour_active'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Active Item Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-sub-arrow-background-hover-colour-active',
									'name'    => 'menu[menu_sub_arrow_background_hover_colour_active]',
									'value'    => rmp_get_value($options,'menu_sub_arrow_background_hover_colour_active'),
									
								] );
								echo $ui_manager->end_group_controls();

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->end_sub_accordion();



							echo $ui_manager->start_accordion_item( [
								'item_header' => [
									'item_title' => __('Animation','responsive-menu-pro'),
								],
								'item_content' => [
									'content_class' => 'upgrade-notice-contents'
								],
								'feature_type' => 'pro'
							] );

							echo $control_manager->upgrade_notice();
							echo $ui_manager->end_accordion_item();
							

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Behaviour','responsive-menu-pro'),
									]
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Use Accordion','responsive-menu-pro'),
									'id'     => 'rmp-menu-accordion-animation',
									'class'  => 'rmp-menu-accordion-animation',
									'name'   => 'menu[accordion_animation]',
									'is_checked'   => is_rmp_option_checked('on', $options,'accordion_animation'),
									
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Auto Expand All Sub Menus','responsive-menu-pro'),
									'id'     => 'rmp-menu-auto-expand-all-submenus',
									'class'  => 'rmp-menu-auto-expand-all-submenus',
									'name'   => 'menu[auto_expand_all_submenus]',
									'is_checked'   => is_rmp_option_checked('on', $options,'auto_expand_all_submenus'),
									
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Auto Expand Current Sub Menus','responsive-menu-pro'),
									'id'     => 'rmp-menu-auto-expand-current-submenus',
									'class'  => 'rmp-menu-auto-expand-current-submenus',
									'name'   => 'menu[auto_expand_current_submenus]',
									'is_checked'   => is_rmp_option_checked('on', $options,'auto_expand_current_submenus'),
									
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Expand Sub items on Parent Item Click','responsive-menu-pro'),
									'id'     => 'rmp-menu-menu-item-click-to-trigger-submenu',
									'class'  => 'rmp-menu-menu-item-click-to-trigger-submenu',
									'name'   => 'menu[menu_item_click_to_trigger_submenu]',
									'is_checked'   => is_rmp_option_checked('on', $options,'menu_item_click_to_trigger_submenu'),
									
								] );


								echo $ui_manager->end_accordion_item();
							?>
						</ul>
					</div>

					<div id="tab-toggle-button" class="rmp-accordions" aria-label="Toggle Button">
						<ul class="rmp-accordion-container">
							<?php
								//Toggle Box

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Button Style','responsive-menu-pro'),
									]
								] );
								
								echo $ui_manager->start_group_controls();

								echo $control_manager->add_text_input_control( [
									'label'  => __('Container Width','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-button-width',
									'name'   => 'menu[button_width]',
									'class' => 'no-updates',
									'value'    => rmp_get_value($options,'button_width'),

									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-width-unit',
										'name' => 'menu[button_width_unit]',
										'default' => 'px',
										'classes' => 'is-unit no-updates',
										'value' => rmp_get_value($options,'button_width_unit'),
									],
								] );
								
								echo $control_manager->add_text_input_control( [
									'label'  => __('Container Height','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-button-height',
									'name'   => 'menu[button_height]',
									'class' => 'no-updates',
									'value'    => rmp_get_value($options,'button_height'),
									
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-height-unit',
										'default' => 'px',
										'name' => 'menu[button_height_unit]',
										'classes' => 'is-unit no-updates',
										'value' => rmp_get_value($options,'button_height_unit'),
									],
								] );

								echo $ui_manager->end_group_controls();

								echo $ui_manager->accordion_divider();


								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_color_control( [
									'label'  => __('Background Color','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-background-colour',
									'tool_tip' => [
										'text' => __('Set the background colour of the button container.','responsive-menu-pro')
									],
									'name'   => 'menu[button_background_colour]',
									'value'    => rmp_get_value($options,'button_background_colour'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Background Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-background-colour-hover',
									'name'   => 'menu[button_background_colour_hover]',
									'value'    => rmp_get_value($options,'button_background_colour_hover'),
									
								] );

								echo $ui_manager->end_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Active Color','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-background-colour-active',
									'name'   => 'menu[button_background_colour_active]',
									'value'    => rmp_get_value($options,'button_background_colour_active'),
									
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Transparent Background','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-transparent-background',
									'class'  => 'rmp-menu-button-transparent-background',
									'tool_tip' => [
										'text' => __('Set the button container to a transparent background.','responsive-menu-pro')
									],
									'name'   => 'menu[button_transparent_background]',
									'is_checked'   => is_rmp_option_checked('on', $options,'button_transparent_background'),
									
								] );

								echo $ui_manager->end_accordion_item();

								//Toggle Positioning
								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Button Position','responsive-menu-pro'),
									]
								] );

								echo  $ui_manager->start_group_controls();

								echo $control_manager->add_select_control( [
									'label'  => __('Side','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-left-or-right',
									'class'  => 'rmp-menu-button-left-or-right no-updates',
									'tool_tip' => [
										'text' => __('Specify which side of the page you want the button to be displayed on.','responsive-menu-pro'),
									],
									'name'    => 'menu[button_left_or_right]',
									'options' => array( 'right' => 'Right' , 'left' => 'Left' ),
									'value'   => rmp_get_value($options,'button_left_or_right')
								] );


								echo $control_manager->add_select_control( [
									'label'  => __('Position','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-position-type',
									'class' => 'no-updates',
									'tool_tip' => [
										'text' => __('Specify how you want the button to stick to your page.','responsive-menu-pro')
									],
									'name'    => 'menu[button_position_type]',
									'options' => array( 'fixed' => 'Fixed' , 'absolute' => 'Absolute', 'relative' => 'Relative' ),
									'value'   => rmp_get_value($options,'button_position_type')
								] );
								echo  $ui_manager->end_group_controls();

								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_text_input_control( [
									'label'  => __('Distance from Side','responsive-menu-pro'),
									'type'   => 'number',
									'class'  => 'no-updates',
									'id'     => 'rmp-menu-button-distance-from-side',
									'name'   => 'menu[button_distance_from_side]',
									'value'    => rmp_get_value($options,'button_distance_from_side'),
									'tool_tip' => [
										'text' => __('Specify how far across from the side you want the button to display and it\'s unit.','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-distance-from-side-unit',
										'name' => 'menu[button_distance_from_side_unit]',
										'classes' => 'is-unit',
										'default' => '%',
										'value' => rmp_get_value($options,'button_distance_from_side_unit')
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Distance from Top','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-button-top',
									'name'   => 'menu[button_top]',
									'value'    => rmp_get_value($options,'button_top'),
									'class'  => 'no-updates',
									'tool_tip' => [
										'text' => __('Specify how far from the top you want the button to display and it\'s unit.','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-top-unit',
										'name' => 'menu[button_top_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'button_top_unit')
									],
								] );

								echo  $ui_manager->end_group_controls();

								echo $control_manager->add_switcher_control( [
									'label'  => __('Push Button with Menu','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-push-animation',
									'tool_tip' => [
										'text' => __('The toggle button will slide along with menu container.','responsive-menu-pro'),
									],
									'name'   => 'menu[button_push_with_animation]',
									'is_checked'   => is_rmp_option_checked('on', $options,'button_push_with_animation'),
									
								] );


								echo $ui_manager->end_accordion_item();

								//Toggle Type
								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Button Type','responsive-menu-pro'),
									],
									'feature_type' => 'semi-pro',
								] );

								echo $ui_manager->start_tabs_controls_panel(
									[ 'tab_classes' => 'rmp-tab-content',
										'tab_items'   =>
										[
											0 => [
												'item_class'  => 'nav-tab-active',
												'item_target' => 'hamburger-type-line',
												'item_text'   =>  __('Hamburger','responsive-menu-pro')
											],
											1 => [
												'item_class' => '',
												'item_target' => 'hamburger-type-icon',
												'item_text' => __('Icon','responsive-menu-pro')
											],
											2 => [
												'item_class' => '',
												'item_target' => 'hamburger-type-image',
												'item_text' => __('Image','responsive-menu-pro')
											],
										]
									]
								);
	
								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'hamburger-type-line',
										'item_class' => 'title-contents',
									]
								);

								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_select_control( [
									'label'  => __('Animation','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-click-animation',
									'class' => 'no-updates',
									'name'    => 'menu[button_click_animation]',
									'options' => rmp_hamburger_type_animation_options(),
									'value'   => rmp_get_value($options,'button_click_animation')
								] );


								echo $control_manager->add_text_input_control( [
									'label'  => __('Line Spacing','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-button-line-margin',
									'name'   => 'menu[button_line_margin]',
									'value'    => rmp_get_value($options,'button_line_margin'),
									
									'tool_tip' => [
										'text' => __('Set the margin between each individual button line and it\'s unit','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-line-margin-unit',
										'name' => 'menu[button_line_margin_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'button_line_margin_unit')
									],
								] );
								echo  $ui_manager->end_group_controls();

								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_text_input_control( [
									'label'  => __('Line Width','responsive-menu-pro'),
									'type'   => 'number',
									'class' => 'no-updates',
									'id'     => 'rmp-menu-button-line-width',
									'name'   => 'menu[button_line_width]',
									'value'    => rmp_get_value($options,'button_line_width'),
									
									'tool_tip' => [
										'text' => __('Set the width of each individual button line and it\'s unit','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-line-width-unit',
										'name' => 'menu[button_line_width_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'button_line_width_unit')
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Line Height','responsive-menu-pro'),
									'type'   => 'number',
									'class' => 'no-updates',
									'id'     => 'rmp-menu-button-line-height',
									'name'   => 'menu[button_line_height]',
									'value'    => rmp_get_value($options,'button_line_height'),
									
									'tool_tip' => [
										'text' => __('Set the height of each individual button line and it\'s unit','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-line-height-unit',
										'name' => 'menu[button_line_height_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'button_line_height_unit')
									]
								] );

								echo  $ui_manager->end_group_controls();

								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_color_control( [
									'label'  => __('Line Color','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-line-colour',
									'name'   => 'menu[button_line_colour]',
									'value'    => rmp_get_value($options,'button_line_colour'),
									
								] );

								echo $control_manager->add_color_control( [
									'label'  => __('Line Hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-line-colour-hover',
									'name'   => 'menu[button_line_colour_hover]',
									'value'    => rmp_get_value($options,'button_line_colour_hover'),
									
								] );
								echo  $ui_manager->end_group_controls();


								echo $control_manager->add_color_control( [
									'label'  => __('Line Active','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-line-colour-active',
									'name'   => 'menu[button_line_colour_active]',
									'value'    => rmp_get_value($options,'button_line_colour_active'),
									
								] );

								echo $ui_manager->end_tab_item();


								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'hamburger-type-icon',
										'item_class' => 'title-contents',
									]
								);

								echo $control_manager->add_icon_picker_control( [
									'label'  => __('Font Icon','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-font-icon',
									'class' => 'no-updates',
									'group_classes' => 'full-size',
									'picker_class'  => 'rmp-menu-font-icon-picker-button',
									'picker_id' => "rmp-menu-button-font-icon-selector",
									'name'    => 'menu[button_font_icon]',
									'tool_tip'=> [
										'text' => __( 'Use a custom font icon instead of standard hamburger lines', 'responsive-menu-pro' )
									],
									'value'    => rmp_get_value($options,'button_font_icon'),
									
								] );

								echo $control_manager->add_icon_picker_control( [
									'label'  => __('Active Font Icon','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-font-icon-when-clicked',
									'group_classes' => 'full-size',
									'picker_class'  => 'rmp-menu-font-icon-picker-button',
									'picker_id' => "rmp-menu-button-font-icon-when-clicked-selector",
									'name'    => 'menu[button_font_icon_when_clicked]',
									'value'    => rmp_get_value($options,'button_font_icon_when_clicked'),
								] );


								echo $ui_manager->end_tab_item();

								echo $ui_manager->start_tab_item( 
									[
										'item_id'    => 'hamburger-type-image',
										'item_class' => 'title-contents',
									]
								);

								echo $control_manager->add_image_control( [
									'label'  => __('Image','responsive-menu-pro'),
									'group_classes' => 'full-size',
									'id'     => 'rmp-menu-button-image',
									'picker_class'  => 'rmp-menu-button-image-selector',
									'picker_id' => "rmp-menu-button-image-selector",
									'name'    => 'menu[button_image]',
									'tool_tip'=> [
										'text' => __( 'Use a custom image instead of standard hamburger lines.', 'responsive-menu-pro' )
									],
									'value'    => rmp_get_value($options,'button_image'),
								] );

								echo $control_manager->add_image_control( [
									'label'  => __('Active Image','responsive-menu-pro'),
									'group_classes' => 'full-size',
									'id'     => 'rmp-menu-button-image-when-clicked',
									'picker_class'  => 'rmp-menu-button-image-when-clicked-selector',
									'picker_id' => "rmp-menu-button-image-when-clicked-selector",
									'name'    => 'menu[button_image_when_clicked]',
									'value'    => rmp_get_value($options,'button_image_when_clicked'),
								] );

								echo $ui_manager->end_tab_item();

								echo $ui_manager->end_accordion_item();

								//Toggle Title
								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Button Text','responsive-menu-pro'),
									]
								] );

								echo  $ui_manager->start_group_controls();

								echo $control_manager->add_text_input_control( [
									'label'  => __('Text','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-title',
									'type'   => 'text',
									'class' => 'on-updates',
									'placeholder' => __('Enter text','responsive-menu-pro'),
									'name'   => 'menu[button_title]',
									'value'    => rmp_get_value($options,'button_title'),
									'tool_tip'=> [
										'text' => __( 'Add text along with hamburger icon/image when button is in active state.', 'responsive-menu-pro' )
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Active Text','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-title-open',
									'name'   => 'menu[button_title_open]',
									'class' => 'on-updates',
									'placeholder' => __('Enter text','responsive-menu-pro'),
									'type'   => 'text',
									'value'    => rmp_get_value($options,'button_title_open'),

								] );	

								echo  $ui_manager->end_group_controls();

								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_select_control( [
									'label'  => __('Text Position','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-title-position',
									'class' => 'on-updates',
									'class'  => 'rmp-menu-button-title-position',
									'name'    => 'menu[button_title_position]',
									'options' => array( 'top' => 'Top' , 'left' => 'Left', 'bottom' => 'Bottom', 'right'=>'Right' ),
									'value'   => rmp_get_value($options,'button_title_position')
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Font Family','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-font',
									'class' => 'no-updates',
									'name'   => 'menu[button_font]',
									'placeholder' => __('Enter font','responsive-menu-pro'),
									'type'   => 'text',
									'value'    => rmp_get_value($options,'button_font'),
								] );
								echo  $ui_manager->end_group_controls();

								echo  $ui_manager->start_group_controls();
								echo $control_manager->add_text_input_control( [
									'label'  => __('Font Size','responsive-menu-pro'),
									'type'   => 'number',
									'class' => 'on-updates',
									'id'     => 'rmp-menu-button-font-size',
									'name'   => 'menu[button_font_size]',
									'value'    => rmp_get_value($options,'button_font_size'),
									'class' => 'no-updates',
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-font-size-unit',
										'name' => 'menu[button_font_size_unit]',
										'classes' => 'is-unit no-updates',
										'default' => 'px',
										'value' => rmp_get_value($options,'button_font_size_unit'),
									],
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Line Height','responsive-menu-pro'),
									'type'   => 'number',
									'class' => 'on-updates',
									'id'     => 'rmp-menu-button-title-line-height',
									'name'   => 'menu[button_title_line_height]',
									'value'    => rmp_get_value($options,'button_title_line_height'),
									
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-button-title-line-height-unit',
										'name' => 'menu[button_title_line_height_unit]',
										'classes' => 'is-unit no-updates',
										'default' => 'px',
										'value' => rmp_get_value($options,'button_title_line_height_unit'),
									],
								] );

								echo  $ui_manager->end_group_controls();

								echo $control_manager->add_color_control( [
									'label'  => __('Text Color','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-text-colour',
									'name'   => 'menu[button_text_colour]',
									'value'    => rmp_get_value($options,'button_text_colour'),
									
								] );

								echo $ui_manager->end_accordion_item();

								//Toggle behaviour
								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Button Behaviour','responsive-menu-pro'),
									],
									'feature_type' => 'semi-pro'
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Toggle menu on click','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-trigger-type-click',
									'class'  => 'rmp-menu-button-trigger-type',
									'name'   => 'menu[button_trigger_type_click]',
									'feature_type' => 'pro',
									'is_checked'   => 'checked',
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Toggle menu on hover','responsive-menu-pro'),
									'id'     => 'rmp-menu-button-trigger-type-hover',
									'class'  => 'rmp-menu-button-trigger-type',
									'name'   => 'menu[button_trigger_type_hover]',
									'feature_type' => 'pro',
									'is_checked'   => is_rmp_option_checked('on', $options,'button_trigger_type_hover'),
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Custom Toggle Selector','responsive-menu-pro'),
									'type' => 'text',
									'group_classes' => 'full-size',
									'id'     => 'rmp-menu-button-click-trigger',
									'name'   => 'menu[button_click_trigger]',
									'value'    => rmp_get_value($options,'button_click_trigger'),
									'tool_tip' => [
										'text' => __( 'If you don\'t want to use the button that comes with the menu, you can specify your own container trigger here. Any CSS selector is accepted.', 'responsive-menu-pro' ),
									]
								] );

								echo $ui_manager->end_accordion_item();
							?>
						</ul>
					</div>

					<div id="tab-container" class="rmp-accordions" aria-label="Container">
						<div class="rmp-order-item rmp-order-item-description rmp-ignore-accordion">
							<?php echo __('Drag the container items up and down to re-order their appearance on the front end.','responsive-menu-pro'); ?>
						</div>
						<ul class="rmp-accordion-container" id="rmp-menu-ordering-items">
							
							<?php

								if ( ! empty( $options['items_order'] ) ) {
									foreach( $options['items_order'] as $key => $value ) {
										if ( 'menu' === $key ) {
											include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/menu.php';
										} elseif( 'title' === $key ) {
											include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/title.php';
										} elseif( 'search' === $key ) {
											include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/search.php';
										} else {
											include_once RMP_PLUGIN_PATH_V4 . '/templates/menu-elements/additional-content.php';
										}
									}
								}

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Appearance','responsive-menu-pro'),
									],
									'feature_type' => 'semi-pro',
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Width','responsive-menu-pro'),
									'type'   => 'number',
									'id'     => 'rmp-menu-container-width',
									'class' => 'no-updates',
									'name'   => 'menu[menu_width]',
									'value'    => rmp_get_value($options,'menu_width'),
									'group_classes' => 'full-size',
									'placeholder' => __('Enter value','responsive-menu-pro'),
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-container-width-unit',
										'name' => 'menu[menu_width_unit]',
										'default' => '%',
										'classes' => 'is-unit no-updates',
										'value' => rmp_get_value($options,'menu_width_unit'),
									]
								] );

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_text_input_control( [
									'label'  => __('Maximum Width','responsive-menu-pro'),
									'type'   => 'number',
									'class' => 'no-updates',
									'id'     => 'rmp-menu-container-max-width',
									'name'   => 'menu[menu_maximum_width]',
									'value'    => rmp_get_value($options,'menu_maximum_width'),
									'placeholder' => __('Enter value','responsive-menu-pro'),
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-container-max-width-unit',
										'name' => 'menu[menu_maximum_width_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'menu_maximum_width_unit'),
									]
								] );

								echo $control_manager->add_text_input_control( [
									'label'  => __('Minimum Width','responsive-menu-pro'),
									'type'   => 'number',
									'class' => 'no-updates',
									'id'     => 'rmp-menu-container-min-width',
									'name'   => 'menu[menu_minimum_width]',
									'value'    => rmp_get_value($options,'menu_minimum_width'),
									'placeholder' => __('Enter value','responsive-menu-pro'),
									'has_unit' => [
										'unit_type' => 'all',
										'id' => 'rmp-menu-container-min-width-unit',
										'name' => 'menu[menu_minimum_width_unit]',
										'classes' => 'is-unit',
										'default' => 'px',
										'value' => rmp_get_value($options,'menu_minimum_width_unit'),
									]
								] );

								echo $ui_manager->end_group_controls();

								echo $control_manager->add_switcher_control( [
									'label'  => __('Auto Height','responsive-menu-pro'),
									'id'     => 'rmp-menu-container-height',
									'class'  => 'rmp-menu-container-height',
									'tool_tip' => [
										'text' => __( 'Limit container height upto last container element','responsive-menu-pro'),
									],
									'feature_type' => 'pro',
									'name'   => 'menu_auto_height',
									'is_checked'   => ''
								] );

								echo $control_manager->add_group_text_control( [
									'label'  => __('Padding','responsive-menu-pro'),
									'type'   =>   'text',
									'class'  =>  'rmp-menu-container-padding',
									'name'    => 'menu[menu_container_padding]',
									'input_options' => [  'top', 'right', 'bottom', 'left' ],
									'value_options' => ! empty( $options['menu_container_padding'] ) ? $options['menu_container_padding'] : ''
								] );

								echo $ui_manager->accordion_divider();

								echo $control_manager->add_color_control( [
									'label'  => __('Container Background','responsive-menu-pro'),
									'id'     => 'rmp-container-background-colour',
									'name'    => 'menu[menu_container_background_colour]',
									'value'    => rmp_get_value($options,'menu_container_background_colour'),
								] );

								echo $control_manager->add_image_control( [
									'label'  => __('Background Image','responsive-menu-pro'),
									'group_classes' => 'full-size',
									'id'     => 'rmp-menu-background-image',
									'picker_class'  => 'rmp-menu-background-image-selector',
									'picker_id' => "rmp-menu-background-image-selector",
									'name'    => 'menu[menu_background_image]',
									'value'    => rmp_get_value($options,'menu_background_image'),
									
								] );

								echo $ui_manager->accordion_divider();

								echo $control_manager->add_sub_heading(
									['text' => __('Animation','responsive-menu-pro') ]
								);

								echo $ui_manager->start_group_controls();

								echo $control_manager->add_select_control( [
									'label'  => __('Type','responsive-menu-pro'),
									'id'     => 'rmp-animation-type',
									'name'    => 'menu[animation_type]',
									'options' => [ 'slide' => 'Slide' , 'push' => 'Push', 'fade' => 'Fade (PRO)' ],
									'value'   => rmp_get_value($options,'animation_type')
								] );

								echo $control_manager->add_select_control( [
									'label'  => __('Direction','responsive-menu-pro'),
									'id'     => 'rmp-menu-appear-from',
									'tool_tip' => [
										'text' => __('Set the viewport side for container entry.','responsive-menu-pro'),
									],
									'name'    => 'menu[menu_appear_from]',
									'options' => [ 'left' => 'Left' , 'right' => 'Right', 'top' => 'Top', 'bottom' => 'Bottom' ],
									'value'   => rmp_get_value($options,'menu_appear_from')
								] );

								echo $ui_manager->end_group_controls();

								echo $control_manager->add_text_input_control( [
									'label'  => __('Transition delay','responsive-menu-pro'),
									'type'   => 'text',
									'id'     => 'rmp-menu-animation-speed',
									'name'   => 'menu[animation_speed]',
									'value'    => rmp_get_value($options,'animation_speed'),
									'tool_tip' => [
										'text' => __('Control the speed of animation for container entry and exit.','responsive-menu-pro')
									],
									'has_unit' => [
										'unit_type' => 's',
									],
								] );

								echo $control_manager->add_text_input_control( [
									'label'         => __('Push Wrapper','responsive-menu-pro'),
									'group_classes' => 'full-size',
									'type'          => 'text',
									'tool_tip' => [
										'text' => __('Mention the CSS selector of the main element which should be pushed when using push animations.','responsive-menu-pro')
									],
									'placeholder' => __('CSS Selector','responsive-menu-pro'),
									'id'       => 'rmp-page-wrapper',
									'name'     => 'menu[page_wrapper]',
									'value'    => rmp_get_value($options,'page_wrapper')
								] );

								echo $ui_manager->end_accordion_item();

								echo $ui_manager->start_accordion_item( [
									'item_header' => [
										'item_title' => __('Behaviour','responsive-menu-pro'),
									],
									'feature_type' => 'semi-pro',
								] );

								echo $control_manager->add_sub_heading(
									['text' => __('Hide Menu On','responsive-menu-pro') ]
								);

								echo $control_manager->add_switcher_control( [
									'label'  => __('Page Clicks','responsive-menu-pro'),
									'id'     => 'rmp-menu-close-on-page-click',
									'name'   => 'menu[menu_close_on_body_click]',
									'is_checked'   => is_rmp_option_checked('on', $options,'menu_close_on_body_click'),
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Link Clicks','responsive-menu-pro'),
									'id'     => 'rmp-menu-close-on-link-click',
									'name'   => 'menu[menu_close_on_link_click]',
									'is_checked'   => is_rmp_option_checked('on', $options,'menu_close_on_link_click'),
								] );

								echo $control_manager->add_switcher_control( [
									'label'  => __('Page Scroll','responsive-menu-pro'),
									'id'     => 'rmp-menu-close-on-page-scroll',
									'name'   => 'menu_close_on_scroll',
									'feature_type' => 'pro',
									'is_checked'   => ''
								] );

								echo $ui_manager->accordion_divider();

								echo $control_manager->add_switcher_control( [
									'label'  => __('Enable Touch Gestures','responsive-menu-pro'),
									'id'     => 'rmp-menu-touch-gestures',
									'tool_tip' => [
										'text' => __('This will enable you to drag or swipe to close the container on touch devices.','responsive-menu-pro'),
									],
									'feature_type' => 'pro',
									'name'   => 'enable_touch_gestures',
									'is_checked'   => '',
								] );

								echo $ui_manager->accordion_divider();

								echo $control_manager->add_sub_heading(
									[
										'text' => __('Keyboard Controls','responsive-menu-pro'),
										'tool_tip' => [
											'text' => __( 'Select keystrokes to control the menu via keyboard.','responsive-menu-pro'),
										]
									]
								);

								$keys =  rmp_get_menu_open_close_keys();	

								echo $control_manager->add_select_control( [
									'label'  => __('Hide Menu','responsive-menu-pro'),
									'id'     => 'rmp-keyboard-shortcut-close-menu',
									'class'  => 'rmp-keyboard-shortcut-close-menu',
									'group_classes' => 'full-size',
									'multiple' => true,
									'name'    => 'keyboard_shortcut_open_menu[]',
									'options' => $keys,
									'value' => [ 0 => '27', 1 => 40 ],
									'feature_type' => 'pro'
								] );

								echo $control_manager->add_select_control( [
									'label'  => __('Show Menu ','responsive-menu-pro'),
									'id'     => 'rmp-keyboard-shortcut-open-menu',
									'class'  => 'rmp-keyboard-shortcut-open-menu',
									'group_classes' => 'full-size',
									'multiple' => true,
									'name'    => 'keyboard_shortcut_open_menu[]',
									'value' => [ 0 => '13', 1 => 38 ],
									'options' => $keys,
									'feature_type' => 'pro',
								] );

								echo $ui_manager->end_accordion_item();
							?>			
						</ul>
					</div>
				</div>
				<?php
					// RMP Customize-Footer
					echo $editor->footer_section();
				?>
			</form>

			<main id="rmp-editor-preview" class="rmp-editor-preview-main">
				<div id="rmp-preview-wrapper" class="rmp-preview-wrapper">
					<div id="rmp-preview-iframe-loader">
						<img src="https://demo.responsive.menu/wp-content/themes/demo-main/static/imgs/giphy.webp" alt="loading" />
					</div>
					<iframe id="rmp-preview-iframe" src="<?php echo esc_url( get_site_url() . '?rmp_preview_mode=true' );  ?>"></iframe>
				</div>
			</main>

			<?php echo $editor->sidebar_drawer(); ?>
		</div>
		<?php
			include_once RMP_PLUGIN_PATH_V4 . '/templates/rmp-wizards.php';
			do_action( 'admin_print_footer_scripts' );
		?>

		<div id="rmp-required-footer">
			<?php wp_footer(); ?>
		</div>
	</body>
</html>
