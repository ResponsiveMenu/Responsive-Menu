/**
 * This is admin scripts file which contain the admin actions.
 *
 * @version 4.0.0
 *
 * @author Expresstech System
 *
 */

jQuery( document ).ready( function( jQuery ) {

	/**
	 * Iframe loader and contents show/hide.
	 */
	jQuery('#rmp-preview-iframe').on('load', function() {
		jQuery( '#rmp-preview-iframe-loader' ).hide();
		jQuery( '#rmp-menu-update-notification').remove();
		jQuery('#rmp-preview-iframe').show();

		jQuery('#rmp-preview-iframe').contents().find( 'a' ).on( 'click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			var url = jQuery(this).attr('href');

			// Prevent to load the customizer page on preview aria.
			if ( '#' == url ) {
				return;
			}

			if ( url.indexOf('?') >= 0 ) {
				url = url + '&rmp_preview_mode=true';
			} else {
				url = url + '?rmp_preview_mode=true';
			}

			jQuery('#rmp-preview-iframe').attr('src', url );

		});

	});

	/**
	 * Save the theme as template.
	 *
	 * @since 4.0.0
	 *
	 * @fires Click
	 */
	jQuery( 'button#rmp-save-theme' ).on( 'click', function( e ) {
		e.stopPropagation();
		e.preventDefault();

		const themeName = jQuery( '#rmp-save-theme-name' ).val();

		if ( 3 > themeName.length ) {
			alert( 'Please give meaning full name to this theme' );
			return;
		}

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_save_theme',
				'ajax_nonce': rmpObject.ajax_nonce,
				'theme_name': themeName,
				'menu_id': jQuery( '#menu_id' ).val(),
				'form': jQuery( '#rmp-editor-form' ).serialize()
			},
			type: 'POST',
			dataType: 'json',
			error: function( error ) {
				console.log( error.statusText );
			},
			success: function( response ) {
				jQuery( e.target ).parents( '.rmp-dialog-contents' )
					.append( '<div class="notice notice-success settings-error is-dismissible"><p>' + response.data.message + '</p></div>' );
			}
		} );
	} );

	/**
	 * Ajax call to save the menu settings when click on update.
	 *
	 * @version 4.0.0
	 *
	 * @fires click
	 */
	jQuery(document).on( 'click', 'button#rmp-save-menu-options,#rmp-menu-quick-update-button', function( e ) {

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_save_menu_action',
				'ajax_nonce': rmpObject.ajax_nonce,
				'form': jQuery( '#rmp-editor-form' ).serialize()
			},
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				jQuery( '#rmp-preview-iframe-loader' ).show();
			},
			error: function( error ) {
				console.log( error.statusText );
				jQuery( '#rmp-preview-iframe-loader' ).hide();
			},
			success: function( response ) {
				// If options is updated successfully then reload the iframe.
				if ( response.success ) {
					const url = jQuery( '#rmp-preview-iframe' ).attr('src');
					jQuery('#rmp-preview-iframe').attr('src', url );
				}
			}
		} );
	} );

	// Initiate the color picker instances.
	jQuery( '.rmp-color-input' ).wpColorPicker();

	// Fix events glitch on color textbox.
	jQuery( '.rmp-color-input' ).removeAttr( 'style' );
	jQuery( document ).find( '.rmp-color-input' ).off( 'focus' );

	// Initiate the tab elements.
	jQuery( '.tabs,#rmp-setting-tabs' ).tabs( {
		hide: { effect: 'explode', duration: 1000 },
		show: { effect: 'explode', duration: 800 },
		active: 0
	} );

	// Active tabs under ordering elements.
	jQuery( '.nav-tab-wrapper' ).on( 'click', '.nav-tab', function( e ) {
		jQuery( '.nav-tab-wrapper .nav-tab' ).removeClass( 'nav-tab-active' );
		jQuery( this ) .addClass( 'nav-tab-active' );
	} );

	/**
	 * Hamburger element selector option hide and show.
	 */

	 hideShowSelect( '#rmp-menu-button-position-type', '.rmp-menu-hamburger-selector-div', 'show', 'inside-element');

	jQuery('#rmp-menu-button-position-type').on( 'change', function() {
		hideShowSelect( this, '.rmp-menu-hamburger-selector-div', 'show', 'inside-element');
	});


	/**
	 * select hide show function
	 * @para string select, string show/hide Element, string show/hide
	 */
	 function hideShowSelect( checkElement, targetElement, condition, value ) {

		if ( jQuery(checkElement).val() == value ) {
			if(condition == 'show'){
				jQuery(targetElement).show();
			}else{
				jQuery(targetElement).hide();
			}
		} else {
			if(condition == 'show'){
				jQuery(targetElement).hide();
			}else{
				jQuery(targetElement).show();
			}
		}
	}

	/**
	 * Check open/close of device options switcher.
	 *
	 * @version 4.0.0
	 *
	 * @fires click
	 */
	jQuery( '.rmp-device-switcher' ).on( 'click', function() {
		var isOpen = jQuery( this ).hasClass( 'open' );

		if ( isOpen ) {
			jQuery( this ).removeClass( 'open' );
		} else {
			jQuery( '.rmp-device-switcher' ).removeClass( 'open' );
			jQuery( this ).addClass( 'open' );
		}

	} );

	/**
	 * Change the option when select a device.
	 *
	 * @version 4.0.0
	 *
	 * @fires click
	 */
	jQuery( '.rmp-device-switcher li' ).on( 'click', function() {
		var  selectedDevice = jQuery( this ).attr( 'data-device' );
		var  firstDevice    = jQuery( '.rmp-device-switcher li:first-child' ).attr( 'data-device' );
		if ( selectedDevice != firstDevice ) {
			activeDeviceOptions( selectedDevice );
			if ( 'desktop' == selectedDevice ) {
				jQuery( '#rmp-preview-desktop' ).trigger( 'click' );
			} else if ( 'tablet' == selectedDevice ) {
				jQuery( '#rmp-preview-tablet' ).trigger( 'click' );
			} else {
				jQuery( '#rmp-preview-mobile' ).trigger( 'click' );
			}
		}
	} );


	/**
	 * Active all the device options in editor.
	 *
	 * @version 4.0.0;
	 * @param {string} selectedDevice This device name which is active.
	 */
	function activeDeviceOptions( selectedDevice ) {
		const firstDevice   = jQuery( '.rmp-device-switcher li:first-child' ).attr( 'data-device' );
		const selectedIcon  = jQuery( '.rmp-device-switcher li[data-device=' + selectedDevice + ']' ).html();
		const firstIcon     = jQuery( '.rmp-device-switcher li:first-child' ).html();

		jQuery( '.rmp-device-switcher li' ).each( function() {
			if ( jQuery( this ).attr( 'data-device' ) === selectedDevice ) {
				jQuery( this ).html( firstIcon );
				jQuery( this ).attr( 'data-device', firstDevice );
			} else if ( jQuery( this ).attr( 'data-device' ) === firstDevice ) {
				jQuery( this ).html( selectedIcon );
				jQuery( this ).attr( 'data-device', selectedDevice );
			}
		} );
	}

	/**
	 * Close the device switcher when mouseup other places.
	 *
	 * @version 4.0.0
	 *
	 * @fires mouseup
	 */
	jQuery( document ).on( 'mouseup', function( event ) {
		var target = event.target;
		var deviceSwitcher = jQuery( '.rmp-device-switcher' );

		if ( ! deviceSwitcher.is( target ) && 0 === deviceSwitcher.has( target ).length ) {
			deviceSwitcher.removeClass( 'open' );
		}

	} );

	/**
	 * Active preview as per clicked device.
	 *
	 * @version 4.0.0
	 *
	 * @fires Click
	 */
	jQuery( '#rmp-editor-footer .rmp-preview-device-wrapper' ).on( 'click', 'button', function( e ) {
		jQuery( '#rmp-editor-footer' ).find( '.rmp-preview-device-wrapper button' ).removeClass( 'active' );
		jQuery( '#rmp-editor-footer' ).find( '.rmp-preview-device-wrapper button' ).attr( 'aria-pressed', 'false' );
		jQuery( this ).addClass( 'active' );
		jQuery( this ).attr( 'aria-pressed', 'true' );
		const device = jQuery( this ).data( 'device' );
		const deviceEditor = jQuery( '#rmp-editor-wrapper' );
		const allClasses = deviceEditor.attr( 'class' ).split( ' ' );

		allClasses.forEach( function( value ) {
			if ( value.includes( 'rmp-preview-' ) ) {
				deviceEditor.removeClass( value );
			}
		} );

		deviceEditor.addClass( 'rmp-preview-' + device );
		activeDeviceOptions( device );
	} );

	/**
	 * Instantiate the accordion elements.
	 * @version 4.0.0
	 */
	jQuery( '.rmp-accordion-container,.rmp-sub-accordion-container' ).accordion( {
		collapsible: true,
		heightStyle: 'content',
		animate: 200,
		active: 0,
	} );

	/**
	 * Instantiate the draggable and sortable menu item order elements.
	 * 	@version 4.0.0
	 */
	jQuery( '#rmp-menu-ordering-items' ).accordion().sortable( {
		placeholder: 'sortable-placeholder',
		opacity: 0.9,
		cursor: 'move',
		delay: 150,
		forcePlaceholderSize: true,
		active: false
	} );

	/**
	 * Stop propagating when click on item control element.
	 */
	jQuery( '#tab-container .item-controls, #tab-header-bar .item-controls' ).on( 'click', function( event ) {
		event.stopPropagation();
	} );

	/**
	 * Show/Hide tooltip for option description.
	 *
	 * @version 4.0.0
	 *
	 * @fires click,mouseleave
	 */
	jQuery( '.rmp-tooltip-icon' ).on( 'click', function(e) {

		if ( jQuery(this).hasClass('show-tooltip') ) {
			return;
		} else {
			jQuery( this ).addClass('show-tooltip');
		}

		var toolTipContents = jQuery( this ).find( '.rmp-tooltip-content' );
		toolTipContents.css({
			'left': e.pageX - ( ( toolTipContents.width() / 100 ) * 60 ),
			'position': 'fixed',
			'top':  ( e.pageY - toolTipContents.height() - 10 ),
			'bottom': 'unset'
		});

		toolTipContents.fadeIn();

	} ).on( 'mouseleave', function() {
		jQuery(this).removeClass('show-tooltip');
		jQuery( this ).find( '.rmp-tooltip-content' ).fadeOut();
	} );

	/**
	 * Remove image from image picker
	 *
	 * @version 4.0.0
	 *
	 * @fires Click
	 */
	jQuery( '.rmp-image-picker ' ).on( 'click', '.rmp-image-picker-trash', function( e ) {
		e.stopPropagation();
		e.preventDefault();
		jQuery( this ).parent( '.rmp-image-picker' ).siblings( 'input.rmp-image-url-input' ).val( '' );
		jQuery( this ).parent( '.rmp-image-picker' ).removeAttr( 'style' );
		jQuery( this ).remove();

		if ( ! jQuery('#rmp-editor-main').find('#rmp-menu-update-notification').length ) {
			addUpdateNotification();
		}

	} );

	/**
	 * Show/Hide the theme uploader section in theme page.
	 */
	jQuery( '#rmp-upload-new-theme' ).on( 'click', function() {
		jQuery( '#rmp-menu-library-import' ).toggleClass( 'hide' );
	} );

	/**
	 * Hide theme uploader section when click on cancel.
	 */
	jQuery( '#rmp-menu-library-import-form' ).on( 'click', '.cancel', function( e ) {
		jQuery( '#rmp-menu-library-import' ).addClass( 'hide' );
	} );

	/**
	 * Upload the theme file using dropzone.
	 *
	 * @version 4.0.0
	 */
	jQuery( '#rmp-menu-library-import-form' ).dropzone( {
		clickable: true,
		acceptedFiles: '.zip',
		uploadMultiple: false,
		success: function ( file, response ) {
			location.reload();
		},
		totaluploadprogress: function() {
			jQuery('.rmp-page-loader').css( 'display','flex' );
		}

	} );

	/**
	 * Open theme options in editor footer.
	 */
	jQuery( '#rmp-theme-action' ).on( 'click', function( e ) {
		jQuery( '#rmp-footer-theme-options' ).toggleClass('open');
	} );

	/**
	 * Show/Hide the save theme wizard.
	 */
	jQuery( '.rmp-theme-save-button, #rmp-menu-save-theme-wizard .rmp-dialog-wrap .close' ).on( 'click', function( e ) {
		jQuery( '#rmp-menu-save-theme-wizard' ).toggle();
	} );

	/**
	 * Delete the theme from theme page.
	 */
	jQuery( '.rmp-theme-delete' ).on( 'click', function( e ) {
		e.preventDefault();

		/** Ask for delete confirmation */
		const isConfirm = confirm( 'Are you sure, You want to delete this theme ?' );

		if ( ! isConfirm ) {
			return;
		}

		//Show the loader on deleting theme.
		const current_theme = jQuery(this);
		current_theme.append( '<span class="spinner is-active"></span>' );

		let themeName = jQuery( this ).attr( 'data-theme' );
		let themeType = jQuery( this ).attr( 'data-theme-type' ).toLowerCase();

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_theme_delete',
				'ajax_nonce': rmpObject.ajax_nonce,
				'theme_name': themeName,
				'theme_type': themeType
			},
			type: 'POST',
			dataType: 'json',
			error: function( error ) {
				console.log( error.statusText );
			},
			success: function( response ) {
				current_theme.find('.spinner').removeClass('is-active');

				if ( response.success ) {
					location.reload();
				} else {
					alert( response.data.message );
				}
			}
		} );

	} );

	/**
	 * Apply the selected theme in current active menu in editor.
	 *
	 * @version 4.0.0
	 *
	 * @fires click
	 */
	jQuery( document ).on( 'click', '.rmp-theme-apply', function( e ) {

		//Show the overlay with loader.
		jQuery('.rmp-page-loader').css('display','flex');

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_theme_apply',
				'ajax_nonce': rmpObject.ajax_nonce,
				'theme_name': jQuery( this ).attr( 'theme-name' ),
				'theme_type': jQuery( this ).attr( 'theme-type' ).toLowerCase(),
				'menu_id': jQuery( '#menu_id' ).val(),
				'menu_to_use' : jQuery('#rmp-menu-to-use').val()
			},
			type: 'POST',
			dataType: 'json',
			error: function( error ) {
				console.log( error.statusText );
				jQuery( '.rmp-page-loader' ).hide();
			},
			success: function( response ) {

				if ( response.success ) {
					location.reload();
				} else {
					jQuery( '.rmp-page-loader' ).hide();
					alert( response.data.message );
				}
			}
		} );

	} );

	/**
	 * Save the global settings on click.
	 *
	 * @version 4.0.0
	 *
	 * @fires click
	 */
	jQuery( '.rmp-save-global-settings-button' ).on( 'click', function( e ) {
		e.preventDefault();

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_save_global_settings',
				'ajax_nonce': rmpObject.ajax_nonce,
				'form': jQuery( '#rmp-global-settings' ).serialize()
			},
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				jQuery( this ).prop( 'disabled', true );
				jQuery( '.spinner' ).addClass( 'is-active' );
			},
			error: function( error ) {
				console.log( 'Internal Error !' + error );
			},
			success: function( response ) {
				jQuery( '.spinner' ).removeClass( 'is-active' );
				jQuery( this ).prop( 'disabled', false );
			}
		} );
	} );

	/**
	 * Initiate multiple selectize option of editor.
	 */
	jQuery( '#rmp-keyboard-shortcut-close-menu,#rmp-keyboard-shortcut-open-menu' ).selectize( {
		maxItems : 2,
		plugins: [ 'remove_button' ]
	} );

	/**
	 * Event to linked the group inputs.
	 *
	 * @fires Click
	 */
	jQuery( document ).on( 'click', 'button.rmp-group-input-linked',  function() {
		jQuery(this).toggleClass( 'is-linked' );
	});

	/**
	 * Event to type on all sibblings input if linked.
	 *
	 * @fires keyup
	 */
	jQuery( document ).on( 'keyup', 'input.rmp-group-input', function( event ) {
		var pressedKeys  = this.value.toLocaleLowerCase();
		const parent     = jQuery(this).parents('.rmp-input-group-control');
		const isLinked   = parent.find( '.is-linked' );

		if ( isLinked.length ) {
			parent.find( 'input.rmp-group-input' ).val( pressedKeys);
		} else {
			jQuery( this ).val(pressedKeys);
		}

	});

	/**
	 * Function to add the notification and update button.
	 */
	function addUpdateNotification() {

		if ( ! jQuery('#rmp-editor-main').find('#rmp-menu-update-notification').length ) {
			jQuery( '#rmp-editor-main' ).prepend(
				'<div id="rmp-menu-update-notification" class="rmp-order-item rmp-order-item-description">' +
					'<span> <span class="rmp-font-icon dashicons dashicons-warning "></span> Update Required </span>' +
					'<a href="javascript:void(0)" id="rmp-menu-quick-update-button">UPDATE</a>' +
				'</div>'
			);
		}
	}

	jQuery( 'form#rmp-editor-form' ).on(
		'keyup change paste',
		'input, select, textarea, radio, checkbox',
		function() {
			if (  ! jQuery(this).hasClass('no-updates') ) {
				addUpdateNotification();
			}
		}
	);

	jQuery(document).on(
		'click',
		'#rmp-icon-dialog-select,.media-button-select,.rmp-icon-picker,.rmp-image-picker',
		function() {
			if ( ! jQuery('#rmp-editor-main').find('#rmp-menu-update-notification').length ) {
				addUpdateNotification();
			}
	});

	/**
	 * Event to download exported menu settings as json file.
	 *
	 * @version 4.0.0
	 */
	jQuery( '#rmp-export-menu-button' ).on( 'click', function( e ) {
		e.preventDefault();

		let menu_id   = jQuery('#rmp_export_menu_list').val();

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_export_menu',
				'ajax_nonce': rmpObject.ajax_nonce,
				'menu_id': menu_id
			},
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				jQuery( '#rmp-export-menu-button' ).prop( 'disabled', true );
			},
			error: function( error ) {
				console.log( error.statusText );
				jQuery( '#rmp-export-menu-button' ).prop( 'disabled', false );
			},
			success: function( response ) {
				jQuery( '#rmp-export-menu-button' ).prop( 'disabled', false );
				if ( response.success ) {
					let menu_name = jQuery('#rmp_export_menu_list').children(":selected").text().trim().toLocaleLowerCase().split(' ').join('-');
					download_file( response.data , menu_name + '.json' , 'application/json' );
				}else{
					jQuery( '#rmp-global-settings' ).before(
						'<div class="notice notice-error settings-error is-dismissible"> <p>' + response.data.message + '</p></div>'
					);
					setTimeout( function() {
						jQuery( '#rmp-global-settings' ).parent().find( '.notice' ).remove();
					}, 3000 );
				}
			}
		});

	});

	/**
	 * Function to download the content as file.
	 *
	 * @since 4.0.0
	 *
	 * @param {String} content Contents for file
	 * @param {String} name    Name of the file.
	 * @param {String} type    File type
	 */
	function download_file(content, name, type ) {
		const link = document.body.appendChild( document.createElement('a') );
		const file = new Blob([content], {
			type: type
		});
		link.href = URL.createObjectURL(file);
		link.download = name;
		link.click();
	}

	/**
	 * Event to download exported menu settings as json file.
	 *
	 * @version 4.0.0
	 */
	jQuery( '#rmp-import-menu-button' ).on( 'click', function( e ) {
		e.preventDefault();

		let menu_id   = jQuery('#rmp_import_menu_list').val();

		if( ! menu_id ) {
			alert( 'Please create menu first ! ');
			return;
		}

		let file_data = jQuery('#rmp_input_import_file')[0].files[0];

		if( ! file_data ) {
			alert( 'Choose export file ! ');
			return;
		}

		var form_data = new FormData();
		form_data.append( 'file', file_data );
		form_data.append( 'ajax_nonce', rmpObject.ajax_nonce );
		form_data.append( 'menu_id', menu_id );
		form_data.append( 'action', 'rmp_import_menu' );

		jQuery.ajax( {
			url: rmpObject.ajaxURL,
			data: form_data,
			type: 'POST',
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			beforeSend: function() {
				jQuery( '#rmp-import-menu-button' ).prop( 'disabled', true );
			},
			error: function( error ) {
				console.log( error.statusText );
				jQuery( '#rmp-import-menu-button' ).prop( 'disabled', false );
			},
			success: function( response ) {
				jQuery( '#rmp-import-menu-button' ).prop( 'disabled', false );
				let noticeClass = 'notice-error';
				if ( response.success ) {
					noticeClass = 'notice-success';
					jQuery('#rmp_input_import_file').val('');
				}

				jQuery( '#rmp-global-settings' ).before(
					'<div class="notice ' + noticeClass + ' settings-error is-dismissible"> <p>' + response.data.message + '</p></div>'
				);

				setTimeout( function() {
					jQuery( '#rmp-global-settings' ).parent().find( '.notice' ).remove();
				}, 3000 );
			}
		});

	});

	/**
	 * Function to manage menu container animation options.
	 *
	 * @param {String} optionValue
	 */
	function updateMenuContainerAnimationOptions( optionValue ) {

		if ( 'push' === optionValue ) {
			jQuery( '#rmp-page-wrapper' ).parents('.rmp-input-control-wrapper').fadeIn();
		} else {
			jQuery( '#rmp-page-wrapper' ).parents('.rmp-input-control-wrapper').fadeOut();
		}

		if ( 'fade' === optionValue ) {
			jQuery('#rmp-menu-appear-from option[value="top"]').hide();
			jQuery('#rmp-menu-appear-from option[value="bottom"]').hide();
		} else {
			jQuery('#rmp-menu-appear-from option[value="top"]').show();
			jQuery('#rmp-menu-appear-from option[value="bottom"]').show();
		}
	}

	// Menu container animation type and their options.
	updateMenuContainerAnimationOptions( jQuery( '#rmp-animation-type' ).val() );
	jQuery( '#rmp-editor-wrapper' ).on( 'change', '#rmp-animation-type', function( e ) {
		const optionValue = jQuery( this ).val();
		updateMenuContainerAnimationOptions( optionValue );
	});

	/**
	 * Event to back on home page under preview screen.
	 *
	 * @since 4.1.0
	 *
	 * @fires click
	 *
	 * @return void
	 */
	jQuery('#rmp-preview-wrapper').on( 'click', () => {
		let url = window.location.href;
		url = url.substring( 0, url.indexOf( 'wp-admin' ) ) + '?rmp_preview_mode=true' ;
		jQuery('#rmp-preview-iframe-loader').show();
		jQuery('#rmp-preview-iframe').attr('src', url );
	} );

	/** Call ajax to hide admin notice permanent. */
	jQuery( '.notice-responsive-menu' ).on( 'click', '.notice-dismiss', function( event ) {
		event.preventDefault();
		jQuery.ajax( {
			type: "POST",
			url: rmpObject.ajaxURL,
			data: 'action=rmp_upgrade_admin_notice_dismiss',
		});
	});

} );
