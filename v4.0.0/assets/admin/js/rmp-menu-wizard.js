/**
 * This is js hook scripts file for responsive menu.
 *
 * @file   This files defines the rmpNewMenuWizard object.
 * @author ExpressTech System.
 *
 * @since 4.1.0
 *
 * @package responsive-menu
 */

'use strict';

/**
 * Hooks class.
 *
 * @type  {Object}
 *
 * @since 4.1.0
 */
const rmpMenuWizard = {

	/**
	 * Initialize.
	 *
	 * @return {void}
	 */
	init() {
		this.setProps();
	},

	/**
	 * Set properties and selectors.
	 *
	 * @return {void}
	 */
	setProps() {

		// Assign wizard container element id.
		const menuWizardContainer = jQuery( '#rmp-new-menu-wizard' );

		// Open new create menu wizard on click event.
		jQuery( document ).on( 'click', 'a.page-title-action', function( e ) {
			e.preventDefault();
			menuWizardContainer.show();
		} );

		// Close the new menu wizard.
		jQuery( '#rmp-new-menu-wizard .rmp-dialog-header button.close' ).on( 'click', function() {
			menuWizardContainer.hide();
		} );

		// Show/Hide the page selection input control.
		menuWizardContainer.on( 'change', '.rmp-menu-display-option', function( e ) {
			const optionValue = jQuery( this ).val();
			if ( 'exclude-pages' === optionValue || 'include-pages' === optionValue ) {
				jQuery( '#rmp-menu-page-selector' ).show();
				return;
			}

			jQuery( '#rmp-menu-page-selector' ).hide();
		} );


		// Show/Hide change theme wizard in customizer page.
		jQuery( '.rmp-theme-change-button' ).on( 'click', function( e ) {
			menuWizardContainer.toggle();
		} );

		// Multi step form event for next button.
		jQuery( '#rmp-menu-next-step' ).on( 'click', () =>  {
			this.nextSection();
		} );

		// Multi step form event for top item label.
		jQuery( 'li.rmp-new-menu-step' ).on( 'click', ( e ) => {
			const index = jQuery( e.currentTarget ).index();
			this.goToSection( index );
		} );

		// Call ajax to save the new create menu.
		jQuery( '#rmp-create-new-menu' ).on( 'click', ( e ) => {
			e.preventDefault();

			const menuName  = jQuery( '#rmp-menu-name' );
			let themeName   = jQuery( '.rmp-theme-option:checked' ).val();

			if ( themeName == undefined ) {
				themeName = '';
			}

			jQuery.ajax( {
				url: rmpObject.ajaxURL,
				data: {
					'action': 'rmp_create_new_menu',
					'ajax_nonce': rmpObject.ajax_nonce,
					'menu_name': menuName.val(),
					'menu_to_hide': jQuery( '#rmp-hide-menu' ).val(),
					'menu_to_use': jQuery( '#rmp-menu-to-use' ).val(),
					'menu_show_on_pages': jQuery( '#rmp-menu-display-on-pages' ).val(),
					'menu_show_on': jQuery( '.rmp-menu-display-option' ).val(),
					'menu_theme': themeName,
					'theme_type': jQuery( '.rmp-theme-option:checked' ).attr( 'theme-type' ),
				},
				type: 'POST',
				dataType: 'json',
				beforeSend: function() {
					jQuery( e.currentTarget ).prop( 'disabled', true );
					jQuery( '.spinner' ).addClass( 'is-active' );
				},
				error: function( error ) {
					console.log( 'Internal Error !' );
					jQuery( '#rmp-create-new-menu' ).prop( 'disabled', false );
					jQuery( '.spinner' ).removeClass( 'is-active' );
				},
				success: function( response ) {
					jQuery( '.spinner' ).removeClass( 'is-active' );
					jQuery( '#rmp-create-new-menu' ).prop( 'disabled', false );

					if ( response.success ) {
						window.location.href = response.data.customize_url;
					} else {
						alert( response.data.message );
					}
				}
			} );

		} );

		// Ajax call to upload the theme.
		jQuery( '#rmp-theme-upload' ).on( 'click', ( e ) => {
			e.preventDefault();

			let formData = new FormData();
			let file = jQuery( '#rmp_menu_theme_zip' ).prop( 'files' )[0];
			formData.append( 'file', file );
			formData.append( 'action', 'rmp_menu_theme_upload' );
			formData.append( 'ajax_nonce', rmpObject.ajax_nonce );

			jQuery.ajax( {
				url: rmpObject.ajaxURL,
				data: formData,
				type: 'POST',
				processData: false,
				contentType: false,
				dataType: 'json',
				success: ( response ) => {
					jQuery( '#rmp_menu_theme_zip' ).val( '' );
					alert( response.data.message );
					if ( response.data.html ) {
						jQuery( '#rmp-new-menu-wizard' ).find( '#tabs-1' ).html( response.data.html );
						jQuery( '#rmp-menu-library-import' ).addClass( 'hide' );
					}
				}
			} );

		} );

		// Ajax call to check the recent changes the theme api.
		jQuery( '.rmp-call-theme-api-button' ).on( 'click', ( e ) => {

			if ( ! jQuery( e.currentTarget ).hasClass( 'rmp-call-theme-api-button' ) ) {
				return;
			}

			jQuery( '#rmp-new-menu-wizard' ).find( '.rmp-page-loader' ).css( 'display', 'flex' );

			jQuery.ajax( {
				url: rmpObject.ajaxURL,
				data: {
					'action': 'rmp_call_theme_api',
					'ajax_nonce': rmpObject.ajax_nonce
				},
				type: 'POST',
				dataType: 'json',
				error: function( error ) {
					jQuery( '#rmp-new-menu-wizard' ).find( '.rmp-page-loader' ).hide();
					jQuery( '#rmp-new-menu-wizard' ).find( '#tabs-2 .rmp_theme_grids' ).html( 'Internal Error !' );
				},
				success: ( response ) => {
					if ( response.data.html ) {
						jQuery( '#rmp-new-menu-wizard' ).find( '#tabs-2 .rmp_theme_grids' ).html( response.data.html );
						jQuery( e.currentTarget ).removeClass( 'rmp-call-theme-api-button' );
					}
				}
			} );
		} );

	},

	/**
	 * Jump to the next section of wizard.
	 *
	 * @return {void}
	 */
	nextSection() {
		var currectSectionIndex = jQuery( 'div.rmp-menu-section.current' ).index();
		this.goToSection( currectSectionIndex + 1 );
	},

	/**
	 * Show the indexed section in wizard.
	 *
	 * @return {void}
	 */
	goToSection( currectSectionIndex ) {

		if ( 1 <= currectSectionIndex ) {
			jQuery( '#rmp-create-new-menu' ).show();
			jQuery( '#rmp-menu-next-step' ).hide();
		} else {
			jQuery( '#rmp-create-new-menu' ).hide();
			jQuery( '#rmp-menu-next-step' ).show();
		}

		jQuery( 'div.rmp-menu-section' ).eq( currectSectionIndex ).addClass( 'current' ).siblings().removeClass( 'current' );
		jQuery( 'li.rmp-new-menu-step' ).eq( currectSectionIndex ).addClass( 'current' ).siblings().removeClass( 'current' );
	}

};

rmpMenuWizard.init();

export default rmpMenuWizard;
