<?php
/**
 * Dynamic badges for menu items.
 *
 * Adds a per-menu-item badge — a small counter or label rendered next to the
 * item title — configured from the WordPress Menus screen. Badges are rendered
 * by the Responsive Menu walker only, so they never leak into a theme menu.
 *
 * @since 4.7.4
 *
 * @package responsive-menu
 */

// Disable the direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post meta keys holding a menu item's badge configuration.
 *
 * @since 4.7.4
 */
const RMP_BADGE_SOURCE_META = '_rmp_badge_source';
const RMP_BADGE_VALUE_META  = '_rmp_badge_value';

/**
 * Badge sources available on the menu item screen.
 *
 * @since 4.7.4
 *
 * @return array Source key => human readable label.
 */
function rmp_badge_sources() {

	$sources = array(
		''              => __( 'None', 'responsive-menu' ),
		'text'          => __( 'Static text', 'responsive-menu' ),
		'wc_cart_count' => __( 'WooCommerce — cart item count', 'responsive-menu' ),
		'custom'        => __( 'Custom (shortcode)', 'responsive-menu' ),
	);

	if ( ! class_exists( 'WooCommerce' ) ) {
		unset( $sources['wc_cart_count'] );
	}

	/**
	 * Filters the badge sources offered on the menu item screen.
	 *
	 * Add a key here and return its value from the
	 * `rmp_menu_item_badge_value` filter to register a new source.
	 *
	 * @since 4.7.4
	 *
	 * @param array $sources Source key => label.
	 */
	return apply_filters( 'rmp_menu_item_badge_sources', $sources );
}

/**
 * Render the badge fields on the WordPress Menus screen.
 *
 * @since 4.7.4
 *
 * @param int $item_id Menu item ID.
 *
 * @return void
 */
function rmp_menu_item_badge_fields( $item_id ) {

	$item_id = absint( $item_id );
	$source  = (string) get_post_meta( $item_id, RMP_BADGE_SOURCE_META, true );
	$value   = (string) get_post_meta( $item_id, RMP_BADGE_VALUE_META, true );
	$sources = rmp_badge_sources();

	if ( ! isset( $sources[ $source ] ) ) {
		$source = '';
	}
	?>
	<p class="field-rmp-badge-source description description-wide">
		<label for="rmp-badge-source-<?php echo esc_attr( $item_id ); ?>">
			<?php esc_html_e( 'Badge', 'responsive-menu' ); ?><br />
			<select
				id="rmp-badge-source-<?php echo esc_attr( $item_id ); ?>"
				class="widefat rmp-badge-source"
				name="rmp-badge-source[<?php echo esc_attr( $item_id ); ?>]">
				<?php foreach ( $sources as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $source, $key ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
	</p>
	<p class="field-rmp-badge-value description description-wide">
		<label for="rmp-badge-value-<?php echo esc_attr( $item_id ); ?>">
			<?php esc_html_e( 'Badge text', 'responsive-menu' ); ?><br />
			<input
				type="text"
				id="rmp-badge-value-<?php echo esc_attr( $item_id ); ?>"
				class="widefat rmp-badge-value"
				name="rmp-badge-value[<?php echo esc_attr( $item_id ); ?>]"
				value="<?php echo esc_attr( $value ); ?>" />
			<span class="description">
				<?php esc_html_e( 'Used by the "Static text" and "Custom (shortcode)" sources. A badge with an empty or zero value is not rendered.', 'responsive-menu' ); ?>
			</span>
		</label>
	</p>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'rmp_menu_item_badge_fields', 10, 1 );

/**
 * Persist the badge fields when a menu item is saved.
 *
 * WordPress nonce- and capability-checks the Menus screen before this action
 * fires; the capability check below keeps the meta write safe for any other
 * caller of wp_update_nav_menu_item().
 *
 * @since 4.7.4
 *
 * @param int $menu_id         Menu ID.
 * @param int $menu_item_db_id Menu item ID.
 *
 * @return void
 */
function rmp_save_menu_item_badge( $menu_id, $menu_item_db_id ) {

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$menu_item_db_id = absint( $menu_item_db_id );

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified by the Menus screen before this action runs.
	$source = isset( $_POST['rmp-badge-source'][ $menu_item_db_id ] )
		? sanitize_key( wp_unslash( $_POST['rmp-badge-source'][ $menu_item_db_id ] ) )
		: '';

	$value = isset( $_POST['rmp-badge-value'][ $menu_item_db_id ] )
		? sanitize_text_field( wp_unslash( $_POST['rmp-badge-value'][ $menu_item_db_id ] ) )
		: '';
	// phpcs:enable WordPress.Security.NonceVerification.Missing

	if ( ! array_key_exists( $source, rmp_badge_sources() ) ) {
		$source = '';
	}

	if ( '' === $source ) {
		delete_post_meta( $menu_item_db_id, RMP_BADGE_SOURCE_META );
		delete_post_meta( $menu_item_db_id, RMP_BADGE_VALUE_META );
		return;
	}

	update_post_meta( $menu_item_db_id, RMP_BADGE_SOURCE_META, $source );
	update_post_meta( $menu_item_db_id, RMP_BADGE_VALUE_META, $value );
}
add_action( 'wp_update_nav_menu_item', 'rmp_save_menu_item_badge', 10, 2 );

/**
 * Resolve a menu item's badge value.
 *
 * @since 4.7.4
 *
 * @param WP_Post $item Menu item.
 *
 * @return string Badge value, or an empty string when nothing should render.
 */
function rmp_get_menu_item_badge_value( $item ) {

	if ( empty( $item->ID ) ) {
		return '';
	}

	$source = (string) get_post_meta( $item->ID, RMP_BADGE_SOURCE_META, true );

	if ( '' === $source ) {
		return '';
	}

	$stored = (string) get_post_meta( $item->ID, RMP_BADGE_VALUE_META, true );
	$value  = '';

	switch ( $source ) {
		case 'text':
			$value = $stored;
			break;

		case 'wc_cart_count':
			$value = rmp_get_woocommerce_cart_count();
			break;

		case 'custom':
			$value = wp_strip_all_tags( do_shortcode( $stored ) );
			break;
	}

	/**
	 * Filters a menu item's resolved badge value.
	 *
	 * Return a value here to implement a source registered through
	 * `rmp_menu_item_badge_sources`.
	 *
	 * @since 4.7.4
	 *
	 * @param string  $value  Resolved value.
	 * @param string  $source Badge source key.
	 * @param WP_Post $item   Menu item.
	 * @param string  $stored Value stored on the item.
	 */
	$value = apply_filters( 'rmp_menu_item_badge_value', $value, $source, $item, $stored );

	$value = trim( (string) $value );

	// A badge that reads "0" is noise on a cart or a notification counter.
	if ( '' === $value || '0' === $value ) {
		return '';
	}

	return $value;
}

/**
 * Current WooCommerce cart item count.
 *
 * @since 4.7.4
 *
 * @return string Item count, or an empty string when the cart is unavailable.
 */
function rmp_get_woocommerce_cart_count() {

	if ( ! function_exists( 'WC' ) ) {
		return '';
	}

	$woocommerce = WC();

	if ( empty( $woocommerce->cart ) ) {
		return '';
	}

	return (string) $woocommerce->cart->get_cart_contents_count();
}

/**
 * Append the badge markup to a menu item title.
 *
 * @since 4.7.4
 *
 * @param string  $title Menu item title.
 * @param WP_Post $item  Menu item.
 *
 * @return string
 */
function rmp_append_menu_item_badge( $title, $item ) {

	$value = rmp_get_menu_item_badge_value( $item );

	if ( '' === $value ) {
		return $title;
	}

	$source = (string) get_post_meta( $item->ID, RMP_BADGE_SOURCE_META, true );

	$badge = sprintf(
		'<span class="rmp-menu-item-badge rmp-menu-item-badge--%1$s">%2$s</span>',
		esc_attr( '' !== $source ? $source : 'text' ),
		esc_html( $value )
	);

	/**
	 * Filters the badge markup appended to a menu item title.
	 *
	 * @since 4.7.4
	 *
	 * @param string  $badge Badge markup.
	 * @param string  $value Resolved badge value.
	 * @param WP_Post $item  Menu item.
	 */
	$badge = apply_filters( 'rmp_menu_item_badge_html', $badge, $value, $item );

	return $title . $badge;
}
add_filter( 'rmp_menu_item_title', 'rmp_append_menu_item_badge', 10, 2 );
