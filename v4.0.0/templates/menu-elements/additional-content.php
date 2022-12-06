<li class="rmp-accordion-item rmp-order-item">
	<div class="rmp-accordion-title rmp-order-item-title">
		<span class="item-title"><?php esc_html_e( 'Additional Content', 'responsive-menu' ); ?></span>
		<span class="item-controls">
			<input type='hidden' value='' name='menu[items_order][additional content]'/>
			<input type="checkbox" data-toggle="additional-content" value="on" id="rmp-item-order-additional-content"  class="no-updates toggle item-type" name="menu[items_order][additional content]"
			<?php
			if ( ! empty( $options['items_order']['additional content'] ) ) {
				echo esc_attr( 'checked' ); }
			?>
			>
			<a class="item-edit open-item" aria-label="open Addition Contents options">
				<span class="screen-reader-text">Open</span>
			</a>
		</span>
	</div>
	<div class="rmp-accordion-content tabs rmp-menu-controls">
			<ul class="nav-tab-wrapper rmp-tab-items">
				<li><a class="nav-tab nav-tab-active" href="#additions-contents"><?php esc_html_e( 'Contents', 'responsive-menu' ); ?></a></li>
				<li><a class="nav-tab" href="#additions-contents-styles"><?php esc_html_e( 'Styles', 'responsive-menu' ); ?></a></li>
			</ul>

			<div id="additions-contents" class="title">
				<div class="rmp-input-control-wrapper full-size">
					<label class="rmp-input-control-label"> <?php esc_html_e( 'Content', 'responsive-menu' ); ?> </label>
					<div class="rmp-input-control">
						<textarea id="rmp-menu-additional-content" name="menu[menu_additional_content]" class="no-updates"><?php echo esc_html( rmp_get_value( $options, 'menu_additional_content' ) ); ?></textarea>
					</div>
				</div>
			</div>

			<div id="additions-contents-styles" class="title">
					<?php

					$control_manager->add_group_text_control(
						array(
							'label'         => esc_html__( 'Padding', 'responsive-menu' ),
							'type'          => 'text',
							'class'         => 'rmp-menu-additional-section-padding',
							'name'          => 'menu[menu_additional_section_padding]',
							'input_options' => array( 'top', 'right', 'bottom', 'left' ),
							'value_options' => ! empty( $options['menu_additional_section_padding'] ) ? $options['menu_additional_section_padding'] : '',
						)
					);

					$ui_manager->start_group_controls();
					$control_manager->add_text_input_control(
						array(
							'label'    => esc_html__( 'Font Size', 'responsive-menu' ),
							'type'     => 'number',
							'class'    => 'no-updates',
							'id'       => 'rmp-menu-additional-content-font-size',
							'name'     => 'menu[menu_additional_content_font_size]',
							'value'    => rmp_get_value( $options, 'menu_additional_content_font_size' ),
							'has_unit' => array(
								'unit_type' => 'all',
								'id'        => 'rmp-menu-additional-content-font-size-unit',
								'name'      => 'menu[menu_additional_content_font_size_unit]',
								'classes'   => 'is-unit no-updates',
								'default'   => 'px',
								'value'     => rmp_get_value( $options, 'menu_additional_content_font_size_unit' ),
							),
						)
					);

					$control_manager->add_text_alignment_control(
						array(
							'label'   => esc_html__( 'Text Alignment', 'responsive-menu' ),
							'class'   => 'rmp-menu-additional-content-alignment',
							'name'    => 'menu[menu_additional_content_alignment]',
							'options' => array( 'left', 'center', 'right', 'justify' ),
							'value'   => rmp_get_value( $options, 'menu_additional_content_alignment' ),
						)
					);
					$ui_manager->end_group_controls();

					$control_manager->add_color_control(
						array(
							'label' => esc_html__( 'Text Color', 'responsive-menu' ),
							'id'    => 'rmp-menu-additional-content-color',
							'name'  => 'menu[menu_additional_content_colour]',
							'value' => rmp_get_value( $options, 'menu_additional_content_colour' ),

						)
					);

					?>
			</div>
		</div>
</li>
