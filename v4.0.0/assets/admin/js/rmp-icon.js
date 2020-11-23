/**
 * This file contain the script to handle the icon wizard and it's operation.
 *
 * @version 4.0.0
 */
jQuery( document ).ready( function() {

	var RMP_Icon = {
		iconSelect: '#rmp-icon-dialog-select',
		iconContainer: '.rmp-menu-icons-dialog',
		clearSelector: '#rmp-icon-dialog-clear',
		closeSelector: '.rmp-menu-icons-dialog .rmp-dialog-header span.close',
		clear: function () {
			var self = this;
			jQuery( self.clearSelector ).click( function() {
				jQuery( self.iconContainer ).find( 'input' ).prop( 'checked', false );
			} );
		},
		closeDialog : function() {
			var self = this;
			jQuery(self.closeSelector).click(function(){
				jQuery(self.iconContainer).hide();
			});
		},
		openDialog : function(iconChooser) {
			var self = this;
			jQuery(iconChooser).click(function(e) {
				e.stopPropagation();
				jQuery(self.iconContainer).show();
				jQuery(self.iconSelect).attr('data-click',jQuery(e.target).attr('id'));
			});
		},
		getIconElementWrap :function( icon_class ) {

			if( icon_class.includes('material-icons') ) {
				icon_class = icon_class.replace('material-icons','');
				return '<span class="rmp-font-icon material-icons">'+ icon_class +'</span>';
			}

			return '<span class="rmp-font-icon '+ icon_class +' "></span>';
		},
		removeIcon : function(iconChooser) {
			jQuery( iconChooser ).on( 'click', '.rmp-icon-picker-trash', function(e) {
				e.preventDefault();
				e.stopPropagation();
				jQuery(this).parent('.rmp-icon-picker').siblings('input.rmp-icon-hidden-input').val('');
				jQuery(this).siblings('.rmp-font-icon').remove();
				jQuery(this).parent('.rmp-icon-picker').removeAttr('data-icon');
				jQuery(this).remove();
			});
		},
		getIcon : function() {
			var self = this;
			jQuery(document).on( 'click', this.iconSelect, function() {

				icon_class  =  jQuery(self.iconContainer).find('input:checked').val();

				clicker = '#' + jQuery(self.iconSelect).attr('data-click');

				icon_wrap = self.getIconElementWrap(icon_class);
				jQuery(clicker).find('.rmp-font-icon').remove();
				jQuery(clicker).prev('input.rmp-icon-hidden-input').val(icon_wrap);
				jQuery(clicker).append( icon_wrap );
				jQuery(clicker).attr('data-icon',true);

				jQuery(clicker).find('.rmp-icon-picker-trash').remove();
				jQuery(clicker).append('<i class="rmp-icon-picker-trash dashicons dashicons-trash" aria-hidden="true"></i>');

				jQuery(self.iconSelect).removeAttr('data-click');
				jQuery(self.closeSelector).click();
				jQuery(clicker).prev('input').first().focus();
			});
		},
		init: function( iconChooser ) { 
			this.openDialog(iconChooser);
			this.removeIcon(iconChooser);
			this.getIcon();
			this.clear();
			this.closeDialog();

			jQuery('#rmp-icon-search').on('keyup', _.debounce( this.searchIcon, 500 ) );

			jQuery('#rmp-icon-search').on('keyup', function () {

				var query_string  = this.value.toLocaleLowerCase();
				if ( query_string.length ) {
					if ( ! jQuery('#rmp-icon-search-typing-message').length ) {
						jQuery(this).after('<span id="rmp-icon-search-typing-message"> Waiting for more keystrokes... </span>');   
					} else {
						jQuery('#rmp-icon-search-typing-message').html('Waiting for more keystrokes...');
					}
				}

			});

			/**
			 * Create menu item icon selector.
			 */
			jQuery( document ).on( 'click' , '.delete-menu-item-icon', function() {
				jQuery(this).closest('.rmp-menu-item-icon-container').remove();
			} );

			var self = this;
			jQuery( document ).on( 'click', '#rmp-menu-add-item-icon', function() {
				var lastRow = jQuery( '.rmp-menu-item-icon-container').last();
				var nextRow = lastRow.clone();

				if ( ! nextRow.find( '.delete-menu-item-icon' ).length ) {
					nextRow.append('<span class="delete-menu-item-icon dashicons dashicons-no "></span>' );
				}

				const iconChooser = nextRow.find( '.rmp-icon-picker' );
				self.openDialog(iconChooser);
				self.removeIcon( iconChooser);
				nextRow.find('.rmp-icon-picker-trash').trigger( 'click' );

				let index = jQuery( '.rmp-menu-item-icon-container').length;

				nextRow.find( '.rmp-icon-hidden-input' )
					.attr( 'id', 'rmp-menu-item-font-icon-' +  index );
				nextRow.find( '.rmp-icon-picker' )
					.attr( 'for', 'rmp-menu-item-font-icon-' +  index );
				nextRow.find( '.rmp-icon-picker' )
					.attr( 'id', 'rmp-menu-item-font-icon-selector-' +  index );

				lastRow.after(nextRow);

			} );	

		},
		searchIcon: function(e) {
		   
			jQuery('#rmp-icon-search-typing-message').html('Please wait moment..');
		 
			var query_string  = this.value.toLocaleLowerCase();
		   
		  
			var activeTab = jQuery('.rmp-menu-icons-dialog').find('.nav-tab-active');
			if ( ! activeTab.length ) {
				activeTab = jQuery('.rmp-menu-icons-dialog').find('.nav-tab').first();
			}           

			icon_container = activeTab.attr('href');

			var icon_selector = jQuery( icon_container + ' .font-icon');
			var is_exist = false;
			icon_selector.each( function() {
				var icon_label = jQuery(this).children('input').val().toLocaleLowerCase();
				if ( icon_label.includes(query_string) ) {
					jQuery(this).show();
					is_exist = true;
				} else {
					jQuery(this).hide();
				}
			});

			if ( is_exist ) {
				jQuery('#rmp-icon-search-typing-message').html('Done, Check results..');
			} else {
				jQuery('#rmp-icon-search-typing-message').html('Sorry, Not found..');
			}
			
		}

	};

	RMP_Icon.init( '.rmp-icon-picker' );

});
