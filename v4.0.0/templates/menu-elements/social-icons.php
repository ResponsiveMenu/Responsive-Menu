<?php
/**
 * Social Icons menu element.
 *
 * @since 4.6.0
 *
 * @package responsive-menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'rmp_render_social_icon_row' ) ) {
	/**
	 * Render a social icon row.
	 *
	 * @param string|int $index Row index.
	 * @param array      $icon  Icon data.
	 * @param object     $control_manager Control manager instance.
	 *
	 * @return void
	 */
	function rmp_render_social_icon_row( $index, $icon, $control_manager, $ui_manager ) {
		$icon_value       = ! empty( $icon['icon'] ) ? $icon['icon'] : '';
		$icon_color       = ! empty( $icon['color'] ) ? $icon['color'] : '';
		$icon_hover_color = ! empty( $icon['hover_color'] ) ? $icon['hover_color'] : '';
		$icon_link  = ! empty( $icon['link'] ) ? $icon['link'] : '';

		$label = esc_html__( 'Icon', 'responsive-menu' );
		if ( is_numeric( $index ) ) {
			$label = sprintf( esc_html__( 'Icon %d', 'responsive-menu' ), ( intval( $index ) + 1 ) );
		}
		?>
		<li class="rmp-social-icon-item" data-index="<?php echo esc_attr( $index ); ?>">
			<div class="rmp-social-icon-item-header">
				<span class="rmp-social-icon-item-label"><?php echo esc_html( $label ); ?></span>
				<button type="button" class="button-link-delete rmp-social-icon-remove" aria-label="<?php esc_attr_e( 'Remove social icon', 'responsive-menu' ); ?>">
					<i class="dashicons dashicons-trash" aria-hidden="true"></i>
				</button>
			</div>

			<div class="rmp-social-icon-item-fields">
				<?php
				$control_manager->add_icon_picker_control(
					array(
						'label'         => esc_html__( 'Icon', 'responsive-menu' ),
						'id'            => 'rmp-social-icon-' . $index,
						'group_classes' => 'full-size',
						'picker_class'  => 'rmp-social-icon-picker-button',
						'picker_id'     => 'rmp-social-icon-picker-' . $index,
						'name'          => 'menu[menu_social_icons][' . $index . '][icon]',
						'value'         => $icon_value,
					)
				);
				$ui_manager->start_group_controls();
				$control_manager->add_color_control(
					array(
						'label' => esc_html__( 'Icon Color', 'responsive-menu' ),
						'id'    => 'rmp-social-icon-color-' . $index,
						'name'  => 'menu[menu_social_icons][' . $index . '][color]',
						'value' => $icon_color,
					)
				);
				$control_manager->add_color_control(
					array(
						'label' => esc_html__( 'Icon Hover Color', 'responsive-menu' ),
						'id'    => 'rmp-social-icon-hover-color-' . $index,
						'name'  => 'menu[menu_social_icons][' . $index . '][hover_color]',
						'value' => $icon_hover_color,
					)
				);
				$ui_manager->end_group_controls();
				$control_manager->add_text_input_control(
					array(
						'label'         => esc_html__( 'Link', 'responsive-menu' ),
						'group_classes' => 'full-size',
						'type'          => 'text',
							'placeholder'   => esc_html__( 'https://', 'responsive-menu' ),
							'id'            => 'rmp-social-icon-link-' . $index,
							'name'          => 'menu[menu_social_icons][' . $index . '][link]',
							'value'         => $icon_link,
						)
					);
				?>
			</div>
		</li>
		<?php
	}
}

$social_icons = array();
if ( ! empty( $options['menu_social_icons'] ) && is_array( $options['menu_social_icons'] ) ) {
	$social_icons = array_values( $options['menu_social_icons'] );
}

if ( empty( $social_icons ) ) {
	$social_icons = array( array() );
}

$icon_index = 0;
?>
<li class="rmp-accordion-item rmp-order-item">
	<div class="rmp-accordion-title rmp-order-item-title">
		<span class="item-title"><?php esc_html_e( 'Social Icons', 'responsive-menu' ); ?></span>
		<span class="item-controls">
			<input type="hidden" value="" name="menu[items_order][social-icons]"/>
			<input type="checkbox" data-toggle="social-icons" value="on" id="rmp-item-order-social-icons" class="no-updates toggle item-type" name="menu[items_order][social-icons]"
			<?php
			if ( ! empty( $options['items_order']['social-icons'] ) ) {
				echo esc_attr( 'checked' );
			}
			?>
			>
			<a class="item-edit open-item" aria-label="<?php esc_attr_e( 'Open social icon options', 'responsive-menu' ); ?>">
				<span class="screen-reader-text">Open</span>
			</a>
		</span>
	</div>

	<div class="rmp-accordion-content tabs rmp-menu-controls">
		<ul class="nav-tab-wrapper rmp-tab-items">
			<li><a class="nav-tab nav-tab-active" href="#social-icons-contents"><?php esc_html_e( 'Icons', 'responsive-menu' ); ?></a></li>
			<li><a class="nav-tab" href="#social-icons-styles"><?php esc_html_e( 'Styles', 'responsive-menu' ); ?></a></li>
		</ul>

		<div id="social-icons-contents" class="title">
			<div class="rmp-input-control-wrapper">
				<div class="rmp-social-icons-repeater" id="rmp-social-icons-repeater" data-next-index="<?php echo esc_attr( count( $social_icons ) ); ?>">
					<ul class="rmp-social-icons-items">
						<?php
						foreach ( $social_icons as $icon ) {
							rmp_render_social_icon_row( $icon_index, $icon, $control_manager, $ui_manager );
							$icon_index++;
						}
						?>
					</ul>
					<button type="button" class="button rmp-social-icons-add" id="rmp-social-icons-add">
						<?php esc_html_e( 'Add Icon', 'responsive-menu' ); ?>
					</button>
				</div>
			</div>
			<script type="text/template" id="rmp-social-icon-template">
				<?php rmp_render_social_icon_row( '__INDEX__', array(), $control_manager, $ui_manager ); ?>
			</script>
		</div>

		<div id="social-icons-styles" class="title">
			<?php
			$ui_manager->start_group_controls();
			$control_manager->add_text_input_control(
				array(
					'label'    => esc_html__( 'Icon Size', 'responsive-menu' ),
					'type'     => 'number',
					'class'    => 'no-updates',
					'id'       => 'rmp-menu-social-icon-size',
					'name'     => 'menu[menu_social_icons_size]',
					'value'    => rmp_get_value( $options, 'menu_social_icons_size' ),
						'has_unit' => array(
							'unit_type' => 'all',
							'id'        => 'rmp-menu-social-icon-size-unit',
							'name'      => 'menu[menu_social_icons_size_unit]',
							'classes'   => 'is-unit no-updates',
							'default'   => 'px',
							'value'     => rmp_get_value( $options, 'menu_social_icons_size_unit' ),
						),
				)
			);

			$control_manager->add_text_input_control(
				array(
					'label'    => esc_html__( 'Space Between', 'responsive-menu' ),
					'type'     => 'number',
					'class'    => 'no-updates',
					'id'       => 'rmp-menu-social-icons-gap',
					'name'     => 'menu[menu_social_icons_gap]',
					'value'    => rmp_get_value( $options, 'menu_social_icons_gap' ),
					'has_unit' => array(
						'unit_type' => 'all',
						'id'        => 'rmp-menu-social-icons-gap-unit',
						'name'      => 'menu[menu_social_icons_gap_unit]',
						'classes'   => 'is-unit no-updates',
						'default'   => 'px',
						'value'     => rmp_get_value( $options, 'menu_social_icons_gap_unit' ),
					),
				)
			);
			$ui_manager->end_group_controls();
			$ui_manager->start_group_controls();
			$control_manager->add_select_control(
				array(
					'label'   => esc_html__( 'Layout', 'responsive-menu' ),
					'id'      => 'rmp-menu-social-icons-layout',
					'class'   => 'no-updates',
					'name'    => 'menu[menu_social_icons_layout]',
					'options' => array(
						'horizontal' => esc_html__( 'Horizontal', 'responsive-menu' ),
						'vertical'   => esc_html__( 'Vertical', 'responsive-menu' ),
					),
					'value'   => rmp_get_value( $options, 'menu_social_icons_layout' ),
				)
			);

			$control_manager->add_select_control(
				array(
					'label'   => esc_html__( 'Align', 'responsive-menu' ),
					'id'      => 'rmp-menu-social-icons-alignment',
					'class'   => 'no-updates',
					'name'    => 'menu[menu_social_icons_alignment]',
					'options' => array(
						'left'   => esc_html__( 'Left', 'responsive-menu' ),
						'center' => esc_html__( 'Center', 'responsive-menu' ),
						'right'  => esc_html__( 'Right', 'responsive-menu' ),
					),
					'value'   => rmp_get_value( $options, 'menu_social_icons_alignment' ),
				)
			);
			$ui_manager->end_group_controls();

			$ui_manager->accordion_divider();

			$control_manager->add_group_text_control(
				array(
					'label'         => esc_html__( 'Padding', 'responsive-menu' ),
					'type'          => 'text',
					'class'         => 'rmp-menu-social-icons-section-padding',
					'name'          => 'menu[menu_social_icons_section_padding]',
					'input_options' => array( 'top', 'right', 'bottom', 'left' ),
					'value_options' => ! empty( $options['menu_social_icons_section_padding'] ) ? $options['menu_social_icons_section_padding'] : '',
				)
			);
			?>
		</div>
	</div>
</li>
