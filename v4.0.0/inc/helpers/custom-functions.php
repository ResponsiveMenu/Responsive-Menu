<?php
/**
 * RMP features custom functions.
 *
 * @version 4.0.0
 *
 * @package responsive-menu
 */

// namespace RMP\Features\Inc\Helpers;

/**
 * Function to check the input is checked or not.
 *
 * @version 4.0.0
 *
 * @param string $actual_value
 *
 * @return string|null
 */
function is_rmp_option_checked( $actual_value, $options, $key ) {
	if ( empty( $options[ $key ] ) ) {
		return;
	}

	$checked_value = $options[ $key ];
	if ( $actual_value == $checked_value ) {
		return 'checked';
	}

	return;
}

/**
 * Function to return the value from option
 *
 * @version 4.0.0
 *
 * @param string $key
 * @param array  $options
 *
 * @return string|array
 */
function rmp_get_value( $options, $key ) {
	if ( empty( $options[ $key ] ) ) {
		return;
	}

	return $options[ $key ];
}

function rmp_get_list_of_pages() {
	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => 'any',
		)
	);

	$all_pages = array();

	foreach ( $posts as $post ) {
		$all_pages[ $post->ID ] = $post->post_title;
	}
	wp_reset_postdata();
	return $all_pages;
}

/**
 * Return the form to select a dashicon
 *
 * @since 1.5.2
 * @return string
 */
function rmp_dashicon_selector() {
	foreach ( rmp_all_dash_icons() as $code => $class ) {
		$bits = explode( '-', $code );
		$type = $bits[0]; ?>
		<div class="<?php echo esc_attr( $type ); ?> font-icon">
			<input class="radio" id="<?php echo esc_attr( $class ); ?>" type="radio" rel="<?php echo '&#x' . esc_attr( $bits[1] ); ?>" name="icon" value="dashicons <?php echo esc_attr( $class ); ?>" />
			<label rel="<?php echo '&#x' . esc_attr( $bits[1] ); ?>" for="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $class ); ?>" ></label>
		</div>
		<?php
	}
}


/**
 * Function to return the all menu ids of published menu.
 *
 * @since 4.0.0
 * @return array $menu_ids;
 */
function get_all_rmp_menu_ids() {
	$args = array(
		'post_type'      => 'rmp_menu',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);

	$all_menus    = get_posts( $args );
	$menu_ids = array();

	if ( ! empty( $all_menus ) ) {
		foreach ( $all_menus as $menu ) {
			setup_postdata( $menu );
			$menu_ids[] = $menu->ID;
		}
	}
	wp_reset_postdata();
	return $menu_ids;
}

/**
 * Function to return the all published menu list.
 *
 * @since 4.0.0
 * @return array;
 */
function rmp_get_all_menus() {
	$args = array(
		'post_type'      => 'rmp_menu',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);

	$all_menus    = get_posts( $args );
	$menus = array();

	if ( ! empty( $all_menus ) ) {
		foreach ( $all_menus as $menu ) {
			setup_postdata( $menu );
			$menus[ $menu->ID ] = $menu->post_title;
		}
	}
	wp_reset_postdata();
	return $menus;
}



/**
 * Get image alt text by image URL
 *
 * @param String $image_url
 *
 * @return Bool | String
 */
function rmp_image_alt_by_url( $image_url ) {
	global $wpdb;

	if ( empty( $image_url ) ) {
		return '';
	}

	$query_arr = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s';", strtolower( $image_url ) ) );
	$image_id  = ( ! empty( $query_arr ) ) ? $query_arr[0] : 0;

	return get_post_meta( $image_id, '_wp_attachment_image_alt', true );
}

/**
 * Return the menu items.
 */
function rmp_get_wp_nav_menu_items( $options ) {
	$menu = '';

	if ( ! empty( $options['theme_location_menu'] ) && has_nav_menu( $options['theme_location_menu'] ) ) {
		$menu = get_term( get_nav_menu_locations()[ $options['theme_location_menu'] ], 'nav_menu' )->slug;
	} elseif ( ! empty( $options['menu_to_use'] ) ) {
		$menu = $options['menu_to_use'];
	} elseif ( ! empty( get_terms( 'nav_menu' )[0]->slug ) ) {
		$menu = get_terms( 'nav_menu' )[0]->slug;
	}

	return wp_get_nav_menu_items( $menu );
}


/**
 * @return allow svg html tags.
 */
function rmp_allow_svg_html_tags() {
	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = array(
		'svg'   => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array(
			'd'    => true,
			'fill' => true,
		),
	);

	return array_merge( $kses_defaults, $svg_args );
}

/**
 * Sanitizes multi-dimentional array
 *
 * @since 4.1.6
 */
function rm_sanitize_rec_array( $array, $textarea = false ) {
	foreach ( (array) $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$array[ $key ] = rm_sanitize_rec_array( $value );
		} else {
			$array[ $key ] = $textarea ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		}
	}
	return $array;
}

/**
 * Add RM customize button for admin menus
 * @since 4.3.0
 */
function add_rm_customize_button_to_save_menu() {
	global $pagenow;
    if ( 'edit.php' === $pagenow && ! empty( $_REQUEST['post_type'] ) && ! empty( $_REQUEST['open'] ) && 'rmp_menu' === $_REQUEST['post_type'] && 'wizard' === $_REQUEST['open'] ) {
		$inline_script = "jQuery('#rmp-new-menu-wizard').fadeIn();";
		if ( ! empty( $_REQUEST['menu-to-use'] ) ) {
			$inline_script .= "jQuery('#rmp-menu-to-use').val('".esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['menu-to-use'] ) ) ) ."' );";
		}
		wp_add_inline_script( 'rmp_admin_scripts', $inline_script );
	}
    // Check if it's the admin menu page
    if ( 'nav-menus.php' === $pagenow ) {
		$menu_id = isset($_REQUEST['menu']) ? sanitize_text_field( wp_unslash( intval( $_REQUEST['menu'] ) ) ) : absint( get_user_option( 'nav_menu_recently_edited' ) );
		$nav_menus  = wp_get_nav_menus();
		if ( ( empty( $menu_id ) || ! is_nav_menu( $menu_id ) ) && 0 < count( $nav_menus ) ) {
			$menu_id = $nav_menus[0]->term_id;
		}
		$rmp_customize_menu = admin_url( 'edit.php?post_type=rmp_menu&open=wizard' );
		if ( ! empty( $menu_id ) && is_nav_menu( $menu_id ) ) {
			$rmp_customize_menu = admin_url( 'edit.php?post_type=rmp_menu&open=wizard&menu-to-use='.esc_attr( $menu_id ) );
			$query = new WP_Query(array(
				'post_type'      => 'rmp_menu',
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'     => 'rmp_menu_meta',
						'value'   => '"menu_to_use";s:'.strlen( $menu_id ).':"'. esc_sql( $menu_id ).'";',
						'compare' => 'LIKE',
					),
				),
			));
			if ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				wp_reset_postdata();
				$rmp_customize_menu = admin_url( 'post.php?post='.esc_attr( $post_id ).'&action=edit&editor=true' );
			}
		}
		$inline_script = "jQuery(document).ready(function($) {
			$('#save_menu_footer').before('<a href=\"".esc_url( $rmp_customize_menu )."\" style=\"margin-right:5px;\" class=\"button button-secondary button-large rmp-customize-menu\">". __('Customize Menu', 'responsive-menu') ."</a>');
		});";
		// Enqueue the script
		wp_add_inline_script( 'admin-bar', $inline_script  );
	}
}
add_action('admin_footer', 'add_rm_customize_button_to_save_menu', 999);

