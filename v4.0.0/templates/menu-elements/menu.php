<li class="rmp-accordion-item rmp-order-item">
	<div class="rmp-accordion-title rmp-order-item-title">
		<span class="item-title"><?php esc_html_e( 'Menu', 'responsive-menu' ); ?></span>
		<span class="item-controls">
			<input type='hidden' value='' name='menu[items_order][menu]'/>
			<input type="checkbox" data-toggle="menu" value="on" id="rmp-item-order-menu"  class="no-updates toggle item-type" name="menu[items_order][menu]"
			<?php
			if ( ! empty( $options['items_order']['menu'] ) ) {
				echo esc_attr( 'checked' );
			}
			?>
			>
		</span>
	</div>

	<div class="rmp-accordion-content rmp-menu-controls">
		<?php
			$control_manager->add_shortcut_link(
				array(
					'label'        => 'Menu Settings',
					'target'       => 'tab-menu-styling',
					'accordion_id' => 'ui-id-36',
				)
			);
			?>
	</div>
</li>
