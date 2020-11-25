<?php
/**
 * RMP features custom functions.
 *
 * @version 4.0.0
 * 
 * @package responsive-menu-pro
 */

//namespace RMP\Features\Inc\Helpers;

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

    if ( empty( $options[$key] ) ) { 
        return;
    }

    $checked_value = $options[$key];
    if ( $actual_value == $checked_value ) {
        return "checked";
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
function rmp_get_value( $options , $key ) {

	if ( empty( $options[$key] ) ) { 
		return;
	}

	return $options[$key];
}

function rmp_get_list_of_pages() {
		
    $posts = get_posts( [ 
        'numberposts' => -1,
        'post_type' => 'any'
    ] );

    $all_pages = [];
    
    foreach( $posts as $post ) {
        $all_pages[$post->ID] = $post->post_title;
    }

    return $all_pages;
}

/**
 * Return the form to select a dashicon
 *
 * @since 1.5.2
 * @return string
 */
function rmp_dashicon_selector() {

    $return = '';
    foreach ( rmp_all_dash_icons() as $code => $class ) {

        $bits = explode( "-", $code );
        $code = "&#x" . $bits[1] . "";
        $type = $bits[0];

        $return .= sprintf( '<div class="%s font-icon">', esc_attr($type) );
        $return .= sprintf('<input class="radio" id="%1$s" type="radio" rel="%2$s" name="icon" value="dashicons %1$s" />', esc_attr($class), esc_attr($code) );
        $return .= sprintf('<label rel="%1$s" for="%2$s" title="%2$s" ></label>', $code, esc_attr($class) );
        $return .= "</div>";

    }

    return $return;
}


/**
 * Function to return the all menu ids of published menu.
 * 
 * @since 4.0.0
 * @return array $menu_ids;
 */
function get_all_rmp_menu_ids() {

    $args = array(
        'post_type' => 'rmp_menu',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $query = new \WP_Query($args);
    $menu_ids = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $menu_ids[] = get_the_ID();
        }

        wp_reset_postdata();
    }

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
        'post_type' => 'rmp_menu',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $query = new \WP_Query($args);
    $menus = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $menus[ get_the_ID() ] = get_the_title();
        }

        wp_reset_postdata();
    }

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

    if( empty( $image_url ) ) {
        return '';
    }

    $query_arr  = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s';", strtolower( $image_url ) ) );
    $image_id   = ( ! empty( $query_arr ) ) ? $query_arr[0] : 0;

    return get_post_meta( $image_id, '_wp_attachment_image_alt', true );
}

/**
 * Return the menu items.
 */
function rmp_get_wp_nav_menu_items( $options ) {

    $menu = '';

    if( ! empty( $options['theme_location_menu'] ) && has_nav_menu( $options['theme_location_menu'] ) ) {
        $menu = get_term(get_nav_menu_locations()[ $options['theme_location_menu'] ], 'nav_menu')->slug;
    } elseif( ! empty($options['menu_to_use'] ) ) {
        $menu = $options['menu_to_use'];
    } elseif( !empty( get_terms('nav_menu')[0]->slug ) ) {
        $menu = get_terms('nav_menu')[0]->slug;
    }

    return wp_get_nav_menu_items($menu);
}
