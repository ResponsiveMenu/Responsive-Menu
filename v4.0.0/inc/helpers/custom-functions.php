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
function rm_sanitize_rec_array( $array, $allowhtml = false ) {
    if ( ! is_array( $array ) ) {
        return $allowhtml ? rm_sanitize_html_tags( wp_specialchars_decode( (string) $array, ENT_QUOTES ) ) : sanitize_text_field( (string) $array );
    }
    foreach ( $array as $key => $value ) {
        if ( is_array( $value ) ) {
            $array[ $key ] = rm_sanitize_rec_array( $value, $allowhtml );
        } else {
            $array[ $key ] = $allowhtml ? rm_sanitize_html_tags( wp_specialchars_decode( (string) $value, ENT_QUOTES ) ) : sanitize_text_field( (string) $value );
        }
    }
    return $array;
}

function rm_sanitize_html_tags( $content ) {
    // Define allowed HTML tags and attributes
    $common_attrs = array(
		'class' => true,
		'id'    => true,
	);
	$form_common_attrs = array_merge($common_attrs, array(
		'name'     => true,
		'disabled' => true,
		'required' => true,
		'readonly' => true,
	));

	$allowed_tags = array(
		'svg'      => array(
			'xmlns'   => true,
			'viewBox' => true,
			'width'   => true,
			'height'  => true,
			'fill'    => true,
		),
		'path'     => array(
			'd'               => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
		),
		'div'      => $common_attrs,
		'span'     => $common_attrs,
		'br'       => true,
		'b'        => true,
		'strong'   => true,
		'img'      => array(
			'src'    => true,
			'alt'    => true,
			'width'  => true,
			'height' => true,
			'class'  => true,
			'id'     => true,
			'style'  => true,
		),
		'i'        => $common_attrs,
		'label'    => $common_attrs,
		'a'        => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
		'h1'       => $common_attrs,
		'h2'       => $common_attrs,
		'h3'       => $common_attrs,
		'h4'       => $common_attrs,
		'h5'       => $common_attrs,
		'h6'       => $common_attrs,
		'p'        => $common_attrs,
		'form'     => array(
			'action' => true,
			'method' => true,
			'class'  => true,
			'id'     => true,
		),
		'input'    => array_merge($form_common_attrs, array(
			'type'        => true,
			'value'       => true,
			'placeholder' => true,
			'checked'     => true,
		)),
		'textarea' => array_merge($form_common_attrs, array(
			'placeholder' => true,
			'rows'        => true,
			'cols'        => true,
		)),
		'select'   => $form_common_attrs,
		'option'   => array(
			'value'    => true,
			'selected' => true,
		),
		'button'   => array_merge($form_common_attrs, array(
			'type'  => true,
			'value' => true,
		)),
	);

    // Sanitize content
    return wp_kses($content, $allowed_tags);
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
			$(document).on('change', '.edit-menu-item-hide-login-rmp-setting, .edit-menu-item-hide-nonlogin-rmp-setting', function() {
				var parent = $(this).closest('.menu-item-edit-active');
				var hideLoginCheckbox = parent.find('.edit-menu-item-hide-login-rmp-setting');
				var hideNonLoginCheckbox = parent.find('.edit-menu-item-hide-nonlogin-rmp-setting');

				if (hideLoginCheckbox.is(':checked')) {
					hideNonLoginCheckbox.prop('disabled', true);
				} else {
					hideNonLoginCheckbox.prop('disabled', false);
				}

				if (hideNonLoginCheckbox.is(':checked')) {
					hideLoginCheckbox.prop('disabled', true);
				} else {
					hideLoginCheckbox.prop('disabled', false);
				}
			});
		});";
		// Enqueue the script
		wp_add_inline_script( 'admin-bar', $inline_script  );
	}
}
add_action('admin_footer', 'add_rm_customize_button_to_save_menu', 999);

/*
 * Add custom fields to WordPress navigation menu item settings
 * @since 4.6.0
 */
function rm_custom_menu_item_settings( $item_id, $item, $depth, $args ) {
    $hide_login_rmp_setting = get_post_meta($item_id, '_hide_login_rmp_setting', true);
    $hide_nonlogin_rmp_setting = get_post_meta($item_id, '_hide_nonlogin_rmp_setting', true);

    ?>
    <p class="rm-menu-settings-heading description description-wide">
        <strong><?php esc_html_e('RMP Settings', 'responsive-menu-pro'); ?></strong>
    </p>
    <p class="field-hide-login-rmp description description-thin">
        <label for="edit-menu-item-hide-login-rmp-setting-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" class="edit-menu-item-hide-login-rmp-setting" id="edit-menu-item-hide-login-rmp-setting-<?php echo esc_attr($item_id); ?>" name="menu-item-hide-login-rmp-setting[<?php echo esc_attr($item_id); ?>]" <?php checked($hide_login_rmp_setting, 'on'); echo 'on' === $hide_nonlogin_rmp_setting ? 'disabled' : ''; ?> value="on"/>
            <?php esc_html_e('Hide for logged in users', 'responsive-menu-pro'); ?><br />
        </label>
    </p>
    <p class="field-hide-nonlogin-rmp description description-thin">
        <label for="edit-menu-item-hide-nonlogin-rmp-setting-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" class="edit-menu-item-hide-nonlogin-rmp-setting" id="edit-menu-item-hide-nonlogin-rmp-setting-<?php echo esc_attr($item_id); ?>" name="menu-item-hide-nonlogin-rmp-setting[<?php echo esc_attr($item_id); ?>]" <?php checked($hide_nonlogin_rmp_setting, 'on'); echo 'on' === $hide_login_rmp_setting ? 'disabled' : ''; ?> value="on" />
            <?php esc_html_e('Hide for non logged in users', 'responsive-menu-pro'); ?><br />
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'rm_custom_menu_item_settings', 10, 4);

/*
 * Save custom fields to WordPress navigation menu item settings
 * @since 4.6.0
 */
function rm_save_custom_menu_item_setting( $menu_id, $menu_item_db_id, $menu_item_args ) {
    $custom_setting = ! empty( $_POST['menu-item-hide-login-rmp-setting'][ $menu_item_db_id ] ) ? 'on' : 'off';
    update_post_meta($menu_item_db_id, '_hide_login_rmp_setting', $custom_setting);
    $custom_setting = ! empty( $_POST['menu-item-hide-nonlogin-rmp-setting'][ $menu_item_db_id ] ) ? 'on' : 'off';
    update_post_meta($menu_item_db_id, '_hide_nonlogin_rmp_setting', $custom_setting);
}
add_action('wp_update_nav_menu_item', 'rm_save_custom_menu_item_setting', 10, 3);

/*
 * Modify menu items based on custom setting
 * @since 4.6.0
 */
function rmp_modify_menu_items( $items, $args ) {
    foreach ( $items as $key => $item ) {
        $hide_login_rmp_setting = get_post_meta($item->ID, '_hide_login_rmp_setting', true);
        $hide_nonlogin_rmp_setting = get_post_meta($item->ID, '_hide_nonlogin_rmp_setting', true);
        if ( 'on' === $hide_login_rmp_setting && is_user_logged_in() ) {
            unset($items[ $key ]);
            continue;
        }
        if ( 'on' === $hide_nonlogin_rmp_setting && !is_user_logged_in() ) {
            unset($items[ $key ]);
            continue;
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'rmp_modify_menu_items', 10, 2);
 