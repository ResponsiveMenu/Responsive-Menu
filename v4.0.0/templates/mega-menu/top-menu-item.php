<?php 


$menu_items = rmp_get_wp_nav_menu_items( $options );

if( ! empty( $menu_items ) && is_array( $menu_items) ) {

    foreach( $menu_items as $item ) {

        if ( ! empty( $item->menu_item_parent ) ) {
            continue;
        }

        $is_checked = '';
        if ( ! empty( $options['mega_menu'][$item->ID] ) ) {
            $is_checked = ( $options['mega_menu'][$item->ID] == 'on' ) ? 'checked' : '';
        }
        printf(
            '<div class="rmp-mega-menu-top-item" aria-item-id="%1$s" aria-label="%2$s">
                <span class="item-title">%2$s</span>
                <span class="item-controls">
                    <input type="hidden" value="off" name="menu[mega_menu][%1$s]"/>
                    <input type="checkbox" value="on" title="Enable Mega Menu" class="toggle mega-menu-item" data-id="%1$s" id="rmp-top-item-%1$s" name="menu[mega_menu][%1$s]" %3$s/>
                    <span class="rmp-mega-menu-edit-icon"> %4$s </span>
                </span>
            </div>',
            esc_attr( $item->ID ),
            esc_html( $item->title ),
            esc_attr( $is_checked ),
            file_get_contents( RMP_PLUGIN_PATH_V4 .'/assets/admin/icons/svg/general.svg' )
        );
    }
}

?>
