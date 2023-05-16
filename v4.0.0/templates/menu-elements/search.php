
<li class="rmp-accordion-item rmp-order-item">
	<div class="rmp-accordion-title rmp-order-item-title">
		<span class="item-title"><?php esc_html_e( 'Search', 'responsive-menu' ); ?></span>
		<span class="item-controls">
			<input type='hidden' value='' name='menu[items_order][search]'/>
			<input type="checkbox" data-toggle="search" value="on" id="rmp-item-order-search" class="no-updates toggle item-type" name="menu[items_order][search]"
			<?php
			if ( ! empty( $options['items_order']['search'] ) ) {
				echo esc_attr( 'checked' );
			}
			?>
			>
			<a class="item-edit open-item" aria-label="open Search options">
				<span class="screen-reader-text">Open</span>
			</a>
		</span>
	</div>
	<div class="rmp-accordion-content tabs rmp-menu-controls">
		<ul class="nav-tab-wrapper rmp-tab-items">
			<li><a class="nav-tab nav-tab-active" href="#search-contents"><?php esc_html_e( 'Contents', 'responsive-menu' ); ?></a></li>
			<li><a class="nav-tab" href="#search-styles"><?php esc_html_e( 'Styles', 'responsive-menu' ); ?></a></li>
		</ul>

		<div id="search-contents" class="title">
			<div class="rmp-input-control-wrapper full-size">
				<label class="rmp-input-control-label">
					<?php esc_html_e( 'Placeholder Text', 'responsive-menu' ); ?>
					<span>
						<a target="_blank" rel="noopener" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </a>
					</span>
				</label>
				<div class="rmp-input-control">
					<input disabled placeholder="<?php esc_html_e( 'Search', 'responsive-menu' ); ?>" type="text" id="rmp-menu-search-box-text" name="menu[menu_search_box_text]" class="regular-text" value="<?php echo esc_attr( rmp_get_value( $options, 'menu_search_box_text' ) ); ?>"/>
				</div>
			</div>
			<div class="rmp-input-control-wrapper full-size">
				<label class="rmp-input-control-label">
					<?php esc_html_e( 'Custome search code', 'responsive-menu' ); ?>
					<span>
						<a target="_blank" rel="noopener" class="upgrade-tooltip" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" > <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </a>
					</span>
				</label>
				<div class="rmp-input-control">
					<textarea disabled placeholder="<?php esc_html_e( 'Add your custome search code..', 'responsive-menu' ); ?>" id="rmp-menu-search-box-code" name="menu[menu_search_box_code]" class="regular-text"><?php echo esc_attr( rmp_get_value( $options, 'menu_search_box_code' ) ); ?></textarea>
				</div>
			</div>
		</div>

		<div id="search-styles" class="title">
			<?php

			$control_manager->add_group_text_control(
				array(
					'label'         => esc_html__( 'Padding', 'responsive-menu' ),
					'type'          => 'text',
					'class'         => 'rmp-menu-search-section-padding',
					'name'          => 'menu[menu_search_section_padding]',
					'input_options' => array( 'top', 'right', 'bottom', 'left' ),
					'value_options' => ! empty( $options['menu_search_section_padding'] ) ? $options['menu_search_section_padding'] : '',
				)
			);

			$ui_manager->start_group_controls();
				$control_manager->add_text_input_control(
					array(
						'label'    => esc_html__( 'Height', 'responsive-menu' ),
						'type'     => 'number',
						'id'       => 'rmp-menu-search-box-height',
						'name'     => 'menu[menu_search_box_height]',
						'class'    => 'no-updates',
						'value'    => rmp_get_value( $options, 'menu_search_box_height' ),
						'has_unit' => array(
							'unit_type' => 'all',
							'id'        => 'rmp-menu-search-box-height-unit',
							'name'      => 'menu[menu_search_box_height_unit]',
							'classes'   => 'is-unit no-updates',
							'default'   => 'px',
							'value'     => rmp_get_value( $options, 'menu_search_box_height_unit' ),
						),
					)
				);

				$control_manager->add_text_input_control(
					array(
						'label'    => esc_html__( 'Border Radius', 'responsive-menu' ),
						'type'     => 'number',
						'class'    => 'no-updates',
						'id'       => 'rmp-menu-search-box-border-radius',
						'name'     => 'menu[menu_search_box_border_radius]',
						'value'    => rmp_get_value( $options, 'menu_search_box_border_radius' ),
						'has_unit' => array(
							'unit_type' => 'px',
						),
					)
				);

				$ui_manager->end_group_controls();

				$ui_manager->accordion_divider();

				$ui_manager->start_group_controls();
				$control_manager->add_color_control(
					array(
						'label' => esc_html__( 'Text Color', 'responsive-menu' ),
						'id'    => 'rmp-menu-search-box-text-colour',
						'name'  => 'menu[menu_search_box_text_colour]',
						'value' => rmp_get_value( $options, 'menu_search_box_text_colour' ),

					)
				);
				$control_manager->add_color_control(
					array(
						'label' => esc_html__( 'Background Color', 'responsive-menu' ),
						'id'    => 'rmp-menu-search-box-background-colour',
						'name'  => 'menu[menu_search_box_background_colour]',
						'value' => rmp_get_value( $options, 'menu_search_box_background_colour' ),

					)
				);
				$ui_manager->end_group_controls();
				$ui_manager->start_group_controls();

				$control_manager->add_color_control(
					array(
						'label' => esc_html__( 'Placeholder Color', 'responsive-menu' ),
						'id'    => 'rmp-menu-search-box-placeholder-colour',
						'name'  => 'menu[menu_search_box_placeholder_colour]',
						'value' => rmp_get_value( $options, 'menu_search_box_placeholder_colour' ),
					)
				);

				$control_manager->add_color_control(
					array(
						'label' => esc_html__( 'Border Color', 'responsive-menu' ),
						'id'    => 'rmp-menu-search-box-border-colour',
						'name'  => 'menu[menu_search_box_border_colour]',
						'value' => rmp_get_value( $options, 'menu_search_box_border_colour' ),

					)
				);
				$ui_manager->end_group_controls();

				?>

		</div>
	</div>
</li>
