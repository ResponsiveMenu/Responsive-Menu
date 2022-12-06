<li class="rmp-accordion-item rmp-order-item">
	<div class="rmp-accordion-title rmp-order-item-title">
		<span class="item-title"><?php esc_html_e( 'Title', 'responsive-menu' ); ?></span>
		<span class="item-controls">
			<input type='hidden' value='' name='menu[items_order][title]'/>
			<input type="checkbox" value="on" data-toggle="title" class="no-updates toggle item-type" id="rmp-item-order-title" name="menu[items_order][title]"
			<?php
			if ( ! empty( $options['items_order']['title'] ) ) {
				echo esc_attr( 'checked' );
			}
			?>
			>
			<a class="item-edit open-item" aria-label="open Title options">
				<span class="screen-reader-text">Open</span>
			</a>
		</span>
	</div>

	<div class="rmp-accordion-content tabs rmp-menu-controls">
		<ul class="nav-tab-wrapper rmp-tab-items">
			<li><a class="nav-tab nav-tab-active" href="#title-contents"><?php esc_html_e( 'Contents', 'responsive-menu' ); ?></a></li>
			<li><a class="nav-tab" href="#title-styles"><?php esc_html_e( 'Styles', 'responsive-menu' ); ?></a></li>
		</ul>
		<div id="title-contents" class="title">
			<?php

			$control_manager->add_text_input_control(
				array(
					'label'         => esc_html__( 'Title Text ', 'responsive-menu' ),
					'group_classes' => 'full-size',
					'type'          => 'text',
					'placeholder'   => 'Enter Title',
					'id'            => 'rmp-menu-menu-title',
					'class'         => 'no-updates',
					'name'          => 'menu[menu_title]',
					'value'         => rmp_get_value( $options, 'menu_title' ),
				)
			);

			$control_manager->add_text_input_control(
				array(
					'label'         => esc_html__( 'Link ', 'responsive-menu' ),
					'group_classes' => 'full-size',
					'type'          => 'text',
					'class'         => 'no-updates',
					'placeholder'   => 'Enter Link',
					'id'            => 'rmp-menu-title-link',
					'name'          => 'menu[menu_title_link]',
					'value'         => rmp_get_value( $options, 'menu_title_link' ),
				)
			);

			$control_manager->add_select_control(
				array(
					'label'   => esc_html__( 'Link Target', 'responsive-menu' ),
					'id'      => 'rmp-menu-title-link-location',
					'class'   => 'no-updates',
					'name'    => 'menu[menu_title_link_location]',
					'options' => array(
						'_blank'  => 'New Tab',
						'_self'   => 'Same Page',
						'_parent' => 'Parent Page',
						'_top'    => 'Full Window Body',
					),
					'value'   => rmp_get_value( $options, 'menu_title_link_location' ),
				)
			);

			$ui_manager->accordion_divider();

			$control_manager->add_image_control(
				array(
					'label'         => esc_html__( 'Image', 'responsive-menu' ),
					'group_classes' => 'full-size',
					'id'            => 'rmp-menu-title-image',
					'picker_class'  => 'rmp-menu-title-image-selector',
					'picker_id'     => 'rmp-menu-title-image-selector',
					'name'          => 'menu[menu_title_image]',
					'value'         => rmp_get_value( $options, 'menu_title_image' ),
				)
			);

			$control_manager->add_icon_picker_control(
				array(
					'label'         => esc_html__( 'Set Font Icon', 'responsive-menu' ),
					'id'            => 'rmp-button-title-icon',
					'group_classes' => 'full-size',
					'picker_class'  => 'rmp-button-title-icon-picker-button',
					'picker_id'     => 'rmp-button-title-icon-selector',
					'name'          => 'menu[menu_title_font_icon]',
					'value'         => rmp_get_value( $options, 'menu_title_font_icon' ),
				)
			);

			?>
		</div>

		<div id="title-styles" class="title ">

				<?php

					$control_manager->add_group_text_control(
						array(
							'label'         => esc_html__( 'Padding', 'responsive-menu' ),
							'type'          => 'text',
							'class'         => 'rmp-menu-title-section-padding',
							'name'          => 'menu[menu_title_section_padding]',
							'input_options' => array( 'top', 'right', 'bottom', 'left' ),
							'value_options' => ! empty( $options['menu_title_section_padding'] ) ? $options['menu_title_section_padding'] : '',
						)
					);

					$ui_manager->start_group_controls();
					$control_manager->add_color_control(
						array(
							'label' => esc_html__( 'Background', 'responsive-menu' ),
							'id'    => 'rmp-menu-title-background-colour',
							'name'  => 'menu[menu_title_background_colour]',
							'value' => rmp_get_value( $options, 'menu_title_background_colour' ),

						)
					);
					$control_manager->add_color_control(
						array(
							'label' => esc_html__( 'Background Hover', 'responsive-menu' ),
							'id'    => 'rmp-menu-title-background-hover-colour',
							'name'  => 'menu[menu_title_background_hover_colour]',
							'value' => rmp_get_value( $options, 'menu_title_background_hover_colour' ),

						)
					);
					$ui_manager->end_group_controls();

					$ui_manager->accordion_divider();
					$ui_manager->start_group_controls();
					$control_manager->add_text_input_control(
						array(
							'label'    => esc_html__( 'Font Size', 'responsive-menu' ),
							'type'     => 'number',
							'class'    => 'no-updates',
							'id'       => 'rmp-menu-title-font-size',
							'name'     => 'menu[menu_title_font_size]',
							'value'    => rmp_get_value( $options, 'menu_title_font_size' ),
							'has_unit' => array(
								'unit_type' => 'all',
								'id'        => 'rmp-menu-title-font-size-unit',
								'name'      => 'menu[menu_title_font_size_unit]',
								'classes'   => 'is-unit no-updates',
								'default'   => 'px',
								'value'     => rmp_get_value( $options, 'menu_title_font_size_unit' ),
							),
						)
					);

					$control_manager->add_text_alignment_control(
						array(
							'label'   => esc_html__( 'Text Alignment', 'responsive-menu' ),
							'class'   => 'rmp-menu-title-alignment',
							'name'    => 'menu[menu_title_alignment]',
							'options' => array( 'left', 'center', 'right', 'justify' ),
							'value'   => rmp_get_value( $options, 'menu_title_alignment' ),

						)
					);
					$ui_manager->end_group_controls();

					// Font family and Font weight options.
					$ui_manager->start_group_controls();
					$control_manager->add_select_control(
						array(
							'label'         => esc_html__( 'Font Weight', 'responsive-menu' ),
							'id'            => 'rmp-menu-title-font-weight',
							'class'         => 'no-updates',
							'name'          => 'menu[menu_title_font_weight]',
							'options'       => rmp_font_weight_options(),
							'value'         => rmp_get_value( $options, 'menu_title_font_weight' ),
							'group_classes' => 'full-size',
						)
					);

					$control_manager->add_text_input_control(
						array(
							'label' => esc_html__( 'Font Family', 'responsive-menu' ),
							'type'  => 'text',
							'id'    => 'rmp-menu-title-font-family',
							'name'  => 'menu[menu_title_font_family]',
							'class' => 'no-updates',
							'value' => rmp_get_value( $options, 'menu_title_font_family' ),
						)
					);
					$ui_manager->end_group_controls();

					$ui_manager->start_group_controls();
					$control_manager->add_color_control(
						array(
							'label' => esc_html__( ' Text Color', 'responsive-menu' ),
							'id'    => 'rmp-menu-title-colour',
							'name'  => 'menu[menu_title_colour]',
							'value' => rmp_get_value( $options, 'menu_title_colour' ),

						)
					);
					$control_manager->add_color_control(
						array(
							'label' => esc_html__( ' Text Hover', 'responsive-menu' ),
							'id'    => 'rmp-menu-title-hover-colour',
							'name'  => 'menu[menu_title_hover_colour]',
							'value' => rmp_get_value( $options, 'menu_title_hover_colour' ),

						)
					);
					$ui_manager->end_group_controls();

					$ui_manager->accordion_divider();

					$ui_manager->start_group_controls();
						$control_manager->add_text_input_control(
							array(
								'label'       => esc_html__( 'Image Width', 'responsive-menu' ),
								'type'        => 'number',
								'id'          => 'rmp-menu-title-image-width',
								'class'       => 'no-updates',
								'name'        => 'menu[menu_title_image_width]',
								'value'       => rmp_get_value( $options, 'menu_title_image_width' ),
								'placeholder' => esc_html__( 'Enter width', 'responsive-menu' ),
								'has_unit'    => array(
									'unit_type' => 'all',
									'id'        => 'rmp-menu-title-image-width-unit',
									'name'      => 'menu[menu_title_image_width_unit]',
									'classes'   => 'is-unit',
									'default'   => '%',
									'value'     => rmp_get_value( $options, 'menu_title_image_width_unit' ),
								),
							)
						);

						$control_manager->add_text_input_control(
							array(
								'label'       => esc_html__( 'Image Height', 'responsive-menu' ),
								'type'        => 'number',
								'id'          => 'rmp-menu-title-image-height',
								'class'       => 'no-updates',
								'name'        => 'menu[menu_title_image_height]',
								'value'       => rmp_get_value( $options, 'menu_title_image_height' ),
								'placeholder' => esc_html__( 'Enter height', 'responsive-menu' ),
								'has_unit'    => array(
									'unit_type' => 'all',
									'id'        => 'rmp-menu-title-image-height-unit',
									'name'      => 'menu[menu_title_image_height_unit]',
									'classes'   => 'is-unit',
									'default'   => 'px',
									'value'     => rmp_get_value( $options, 'menu_title_image_height_unit' ),
								),
							)
						);
						$ui_manager->end_group_controls();
						?>
		</div>
	</div>
</li>
