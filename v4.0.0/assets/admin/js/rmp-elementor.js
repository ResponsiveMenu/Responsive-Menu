/**
 * This file contain the scripts related to responsive menu and elementor based features.
 * 
 * @since 4.0.2
 */
jQuery( document ).ready( function() {

	/**
	 * Fires the event when change menu the responsive menu widget.
	 * 
	 * @fires change
	 */
	jQuery(document).on( 'change', 'select[data-setting="rmp_menu"]', (e) => {
		let menu_id = jQuery( e.target ).val();
		const link  = jQuery( e.target ).parents('.elementor-control-content').find('.rmp-menu-edit-link');
		var href    = new URL( link.attr( 'href' ) );
		href.searchParams.set( 'post', menu_id );
		link.attr( 'href', href.toString() );
	} );

} );
