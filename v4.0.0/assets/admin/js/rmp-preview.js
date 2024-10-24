/**
 * This is preview scripts file for responsive menu customizer.
 *
 * @file   This files defines the rmpHook object.
 * @author ExpressTech System.
 * @type  {Object}
 *
 * @since 4.0.0
 *
 * @package responsive-menu
 */

/**
 * Hooks class.
 *
 * @type  {Object}
 *
 * @since 4.0.0
 */
const rmpHook = {
	hooks: [ ],
	isBreak: false,

	/**
	 * Function to register the hook.
	 *
	 * @since 4.0.0
	 *
	 * @param String   name     Hook Name.
	 * @param function callback Associated function.
	 */
	register: function( name, callback ) {

		if ( 'undefined' == typeof ( rmpHook.hooks[name] ) ) {
			rmpHook.hooks[name] = [ ];
		}

		rmpHook.hooks[name].push( callback );
	},

	/**
	 * Function to call the hook.
	 *
	 * @since 4.0.0
	 *
	 * @param String   name   Hook Name.
	 * @param function params Paramter list.
	 */
	call: function( name, params ) {

		if ( 'undefined' != typeof ( rmpHook.hooks[name] ) ) {
			for ( let i = 0; i < rmpHook.hooks[name].length; ++i ) {
				let output = rmpHook.hooks[name][i]( params );
				if ( false == output ) {
					rmpHook.isBreak = true;
					return false;
				}

				return output;
			}
		}

		return true;
	}
};

/**
 * Register function to color the menu elements.
 *
 * @since 4.0.0
 *
 * @param  {Object}  args List of inputs.
 * @return {String}
 */
rmpHook.register( 'rmp_color_style', function ( args ) {

	if ( ! args ) {
		return false;
	}

	// Set the state/pseudo class.
	if ( 'hover' == args.state ) {
		args.outputSelector = args.outputSelector + ':hover';
	} else if ( 'placeholder' == args.state ) {
		args.outputSelector = args.outputSelector + '::placeholder';
	} else if ( 'before' == args.state ) {
		args.outputSelector = args.outputSelector + '::before';
	} else if ( 'after' == args.state ) {
		args.outputSelector = args.outputSelector + '::after';
	}

	//Prepare css string and return.
	return args.outputSelector + '{ ' + args.attr + ' : ' +  args.value + ';}';
} );

/**
 * rmpPreview class
 *
 * @since 4.0.0
 *
 * @type  {Object}
 */
window.RMP_Preview = {
	iframe : '#rmp-preview-iframe',
	menuId : jQuery('#menu_id').val(),
	mobile_breakpoint : jQuery('#rmp-menu-mobile-breakpoint').val() + 'px',
	tablet_breakpoint : jQuery('#rmp-menu-tablet-breakpoint').val() + 'px',
	active_device: jQuery('#rmp_device_mode'),
	menuContainer : '#rmp-container-'+ self.menuId,

	onTyping: function (inputSelector, outputSelector, type, meta = '') {
		var self = this;
		var iframe  = jQuery(self.iframe);
		jQuery(inputSelector).on( 'keyup change paste', function() {
			let value = jQuery(this).val();
			let is_linked = jQuery(this).parents('.rmp-input-group-control').find('.rmp-group-input-linked').hasClass('is-linked');
			let pos = jQuery(this).attr('data-input');
			let attr = '';
			let unit = '';
			let css = '';

			switch( type ) {
				case 'border-radius':
					css = outputSelector + '{ border-radius : '+ ( value ) +'px;}';
					self.inlineCssInjector(css);
				break;
				case 'section-padding':
					attr = 'padding';
					if( ! is_linked ) {
						attr = attr + '-' + pos;
					}
					css = outputSelector + '{ '+ attr +' : '+ ( value ) +';}';
					self.inlineCssInjector(css);
				break;
				case 'trigger-text':
						if ( iframe.contents().find(outputSelector).length ) {
							iframe.contents().find(outputSelector).html(this.value);
						} else {
							iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).before('<div class="rmp-trigger-label rmp-trigger-label-top"><span class="rmp-trigger-text">"'+ this.value + '"</span></div>')
						}
				break;

				case 'trigger-text-open':
						if ( iframe.contents().find(outputSelector).length ) {
							iframe.contents().find(outputSelector).html(this.value);
						} else {
							iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).before('<div class="rmp-trigger-label rmp-trigger-label-top"><span class="rmp-trigger-text-open">"'+ this.value + '"</span></div>')
						}
				break;

				case 'text' :
					iframe.contents().find( outputSelector ).html(this.value);
				break;

				case 'placeholder' :
					iframe.contents().find(outputSelector).attr('placeholder', this.value );
				break;
				case 'href' :
					iframe.contents().find(outputSelector).attr('href', this.value );
				break;

				case 'font-size':
					unit = jQuery(this).next( '.is-unit' ).val();

					if ( ! unit.length ) {
						unit = 'px';
					}

					css = outputSelector + '{ font-size : '+  ( value + unit ) + ';}';

					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}

					self.inlineCssInjector(css);

				break;

				case 'width':
					unit = jQuery(this).next('.is-unit').val();

					if ( ! unit ) {
						unit = 'px';
					}

					css = outputSelector + '{ width : '+ (this.value + unit) +';}';
					self.inlineCssInjector(css);

				break;

				case 'height':
					unit = jQuery(this).next('.is-unit').val();

					if ( ! unit.length ) {
						unit = 'px';
					}

					css = outputSelector + '{ height : '+  ( this.value + unit ) + ';}';

					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}

					self.inlineCssInjector(css);

				break;
				case 'line-height':
					unit = jQuery(this).next('.is-unit').val();

					if ( ! unit.length ) {
						unit = 'px';
					}

					css = outputSelector + '{ line-height : '+  ( this.value + unit ) + ';}';

					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}

					self.inlineCssInjector(css);

				break;
				case 'min-width':
					unit = jQuery(this).next('.is-unit').val();

					if ( ! unit.length ) {
						unit = 'px';
					}

					css = outputSelector + '{ min-width : '+ (this.value + unit) +';}';

					self.inlineCssInjector(css);

				break;
				case 'max-width':
					unit = jQuery(this).next('.is-unit').val();

					if ( ! unit.length ) {
						unit = 'px';
					}
					css = outputSelector + '{ max-width : '+ (this.value + unit) +';}';
					self.inlineCssInjector(css);
				break;
				case 'trigger-side-position':

					var side = jQuery('#rmp-menu-button-left-or-right').val();

					unit  = jQuery('#rmp-menu-button-distance-from-side-unit').val();

					css = outputSelector + '{ '+ side +' :'+ (value + unit) +' !important;}';

					self.inlineCssInjector(css);

				break;

				case 'top':
					unit = jQuery(this).next('.is-unit').val();
					if ( ! unit ) {
						unit = 'px';
					}
					css = outputSelector + '{ top :' + (value + unit) +' !important;}';
					self.inlineCssInjector(css);
				break;

				case 'bottom':
					unit = jQuery(this).next('.is-unit').val();
					if ( ! unit ) {
						unit = 'px';
					}
					css = outputSelector + '{ bottom :' + (value + unit) +' !important;}';
					self.inlineCssInjector(css);
				break;

				case 'border-width':
					unit = jQuery(this).next('.is-unit').val();
					if ( ! unit ) {
						unit = 'px';
					}
					css = outputSelector + '{ border-width : '+ (this.value + unit) +';}';
					self.inlineCssInjector(css);
				break;

				case 'padding':
					unit = jQuery(this).next('.is-unit').val();
					if ( ! unit ) {
						unit = 'px';
					}

					if ( meta == 'lr') {
						css = outputSelector + '{ padding : 0 '+ (this.value + unit) +';}';
					}
					self.inlineCssInjector(css);
				break;
			}
		});
	},
	bindImage : function(inputSelector, outputSelector, type ) {
		var self    = this;
		var iframe  = jQuery(self.iframe);
		jQuery(document).on( 'click', inputSelector, function(e) {
			e.preventDefault();
			var button = jQuery(this),

			custom_uploader = wp.media({
			title: 'Select image',
			library : {
				type : 'image'
			},
			button: {
				text: 'Use this image'
			},
			multiple: false,
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();

				jQuery(e.target).prev('input.rmp-image-url-input').val(attachment.url);
				jQuery(e.target).css('background-image', 'url(' + attachment.url + ')');
				jQuery(e.target).append('<i class="rmp-image-picker-trash dashicons dashicons-trash" aria-hidden="true"></i>');

				if ( type == 'img-src') {
					iframe.contents().find(outputSelector).attr('src', attachment.url );
				} else if( type == 'background' ) {
					let css = outputSelector + '{ background-image : url('+  attachment.url + ');}';
					self.inlineCssInjector(css);
				} else if( type == 'trigger-icon' ) {

					if ( iframe.contents().find(outputSelector).length ) {
						iframe.contents().find(outputSelector).attr('src', attachment.url );
					} else {
						iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .responsive-menu-pro-inner' ).hide();
						iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).append('<img class="rmp-trigger-icon rmp-trigger-icon-inactive" src="'+ attachment.url +'"/>')
					}
				} else if( type == 'trigger-icon-open' ) {

					if ( iframe.contents().find(outputSelector).length ) {
						iframe.contents().find(outputSelector).attr('src', attachment.url );
					} else {
						iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .responsive-menu-pro-inner' ).hide();
						iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).append('<img class="rmp-trigger-icon rmp-trigger-icon-active" src="'+ attachment.url +'"/>')
					}
				}
			}).open();

		});
	},
	toggleElements: function( inputSelector, outputSelector ) {
		var self = this;
		jQuery(inputSelector).on( 'change', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var iframe = jQuery(self.iframe);
			if ( iframe.contents().find(outputSelector).length ) {
				if ( jQuery(this).is(':checked') ) {
					iframe.contents().find(outputSelector).fadeIn(500);
				} else {
					iframe.contents().find(outputSelector).fadeOut(500);
				}
			} else {
				e.preventDefault();
				var menuId = jQuery('#menu_id').val(),
				toggle_on = jQuery(this).data('toggle');

				jQuery.ajax({
					url: rmpObject.ajaxURL,
					data: {
						'action'      : 'rmp_enable_menu_item',
						'ajax_nonce'    : rmpObject.ajax_nonce,
						'menu_id'     : menuId,
						'menu_element'   : toggle_on,
					},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						jQuery(this).prop('disabled' , true);
						jQuery('#iframe-spinner').show();
					},
					error: function( error ) {
						console.log('Internal Error !');
						jQuery(this).prop('disabled', false);
						jQuery('#iframe-spinner').hide();
					},
					success: function( response ) {

						if ( response.data.markup ) {
							iframe.contents().find( '#rmp-container-'+ self.menuId ).append(response.data.markup);
							self.orderMenuElements();
						}

						jQuery(this).prop('disabled', false);
						jQuery('#iframe-spinner').hide();
					}
				});
			}
		});
	},
	orderMenuElements: function() {
		var list = [];
		var self = this;
		var iframeContents = jQuery(self.iframe).contents();
		jQuery('#tab-container .item-title').each(function () {
			var val = jQuery(this).text().toLocaleLowerCase().trim();

			if ( val=='title') {
				list.push( iframeContents.find( self.menuTitle ) );
				iframeContents.find( self.menuTitle ).remove();
			} else if( val=='search' ) {
				list.push( iframeContents.find( self.menuSearch ) );
				iframeContents.find( self.menuSearch ).remove();
			} else if( val == 'menu' ) {
				list.push( iframeContents.find( self.menuWrap ) );
				iframeContents.find( self.menuWrap ).remove();
			} else {
				list.push( iframeContents.find( self.menuContents ) );
				iframeContents.find( self.menuContents ).remove();
			}
		} );

		list.forEach( function( menuElement ) {
			iframeContents.find( self.menuContainer ).append( menuElement );
		});

	},
	/**
	 * Function to bind the color input with option and elements.
	 *
	 * @version 4.0.0
	 *
	 * @param {String} inputSelector
	 * @param {String} outputSelector
	 * @param {String} attr
	 * @param {String} state
	 */
	bindColor: function( inputSelector, outputSelector, attr, state ) {
		var self = this;
		jQuery( inputSelector ).wpColorPicker( {
			change: function(event, ui) {
				var value = ui.color.toString();
				var css   = rmpHook.call(
					'rmp_color_style', {
						'outputSelector' : outputSelector,
						'attr' : attr,
						'value': value,
						'state': state
					} );

				if ( jQuery( inputSelector ).attr( 'multi-device') ) {
					css = self.mediaQuery( css );
				}

				self.inlineCssInjector(css);
			}
		});
	},
	mediaQuery: function( css ) {

		var self = this;
		self.tablet_breakpoint = jQuery('#rmp-menu-tablet-breakpoint').val() + 'px';
		css = '@media screen and (max-width: '+ self.tablet_breakpoint +' ) {' + css + '}';

		return css;
	},
	inlineCssInjector: function( css ) {
		var self = this;
		var iframe = jQuery(self.iframe);
		var styleElement = iframe.contents().find( '#rmp-inline-css-' + self.menuId );
		if ( styleElement.length ) {
			styleElement.append(css);
		} else {
			let style = '<style id="rmp-inline-css-'+ self.menuId +'">'+ css + '</style>';
			iframe.contents().find('head').append(style);
		}
	},
	changeInput: function( inputSelector, outputSelector , attr, meta = '' ) {
		var self = this;
		var iframe = jQuery(self.iframe);
		jQuery(inputSelector).on( 'change', function(e) {
			let value = jQuery(this).val();
			let unit = jQuery(this).next('.is-unit').val() || 'px'; // Assign default unit if not specified
			let css = '';
			let side = '';
			switch (attr) {
				case 'height-unit':
					css = outputSelector + '{ height : '+  ( value + unit ) + ';}';
					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}
					self.inlineCssInjector(css);
				break;
				case 'line-height-unit':
					css = outputSelector + '{ line-height : '+  ( value+unit ) + ';}';
					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}
					self.inlineCssInjector(css);
				break;
				case 'width-unit':
					css = outputSelector + '{ width : '+ ( value + unit) +';}';
					self.inlineCssInjector(css);
				break;

				case 'font-size':
					css = outputSelector + '{ font-size :' + value + unit + ' !important;}';
					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}
					self.inlineCssInjector(css);
				break;
				case 'font-family':
					css = outputSelector + '{ font-family :' + value +' !important;}';
					if ( jQuery(this).attr( 'multi-device') ) {
						css = self.mediaQuery( css );
					}
					self.inlineCssInjector(css);
				break;
				case 'font-weight':
					css = outputSelector + '{ font-weight :' + value +';}';
					self.inlineCssInjector(css);
				break;
				case 'padding-unit':
					if ( ! unit ) {
						unit = 'px';
					}

					if( meta == 'lr') {
						css = outputSelector + '{ padding : 0 '+ (value + unit) +';}';
					}

					self.inlineCssInjector(css);
				break;
				case 'letter-spacing':
					css = outputSelector + '{ letter-spacing :' + value +'px; }';
					self.inlineCssInjector(css);
				break;
				case 'position-alignment':

					if ( iframe.contents().find( outputSelector ).length ) {
						let position  = jQuery(this).val();
						var rmpTriggerBox = iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' );
						iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).remove();
						if ( position == 'top' || position == 'left' ) {
							iframe.contents().find( '#rmp_menu_trigger-' + self.menuId ).append(rmpTriggerBox);
						} else {
							iframe.contents().find( '#rmp_menu_trigger-' + self.menuId ).prepend(rmpTriggerBox);
						}

					}
				break;
				case 'trigger-animation':
					var new_class = 'rmp-menu-trigger-' + value;
					let all_class =  iframe.contents().find( outputSelector ).attr('class').split(" ");
					all_class.forEach( function( value ) {
						if ( value.includes( 'rmp-menu-trigger-' ) ) {
							iframe.contents().find( outputSelector ).removeClass(value);
							iframe.contents().find( outputSelector ).addClass(new_class);
						}
					});
				break;
				case 'top':
					css = outputSelector + '{ top :' + (value + unit) +' !important;}';
					self.inlineCssInjector(css);
				break;
				case 'trigger-side-position':
					side = jQuery('#rmp-menu-button-left-or-right').val();
					css = outputSelector + '{ '+ side +' :'+ (value + unit) +' !important;}';
					self.inlineCssInjector(css);
				break;
				case 'trigger-side':
					side  = jQuery(this).val();
					css = outputSelector + '{' + side + ':'+ ( value + unit ) +' !important;'+ (side === 'left' ? 'right:unset !important;' : 'left:unset !important;') +'}';
					self.inlineCssInjector(css);

				break;
				case 'position':
					css = outputSelector + '{ position :'+ value +' !important;}';
					self.inlineCssInjector(css);
				break;
				case 'trigger-background' :
					if ( jQuery(this).is(':checked') ) {
						iframe.contents().find( outputSelector ).attr('style', 'background:unset !important;');
					} else {
						iframe.contents().find( outputSelector ).removeAttr( 'style' );
					}
				break;
				case 'target':
					let targetValue = jQuery(this).val();
					if( ! targetValue.length ) {
						targetValue = '_self';
					}

					iframe.contents().find(outputSelector).attr('target', targetValue );
				break;

				case 'text-align':
					let textAlignValue =  jQuery(this).val();
					iframe.contents().find( outputSelector ).css({
						'text-align' : textAlignValue,
					} );
				break;
			}
		});
	},

	init: function() {
		var self = this;

		//Mobile menu elements.
		self.menuContainer = '#rmp-container-'+ self.menuId;
		self.menuTitle     = '#rmp-menu-title-' + self.menuId;
		self.menuSearch    = '#rmp-search-box-' + self.menuId;
		self.menuWrap      = '#rmp-menu-wrap-' + self.menuId;
		self.menuContents  = '#rmp-menu-additional-content-' + self.menuId;

		//Menu container background color.
		self.bindColor(
			'#rmp-container-background-colour',
			'#rmp-container-' + self.menuId ,
			'background',
			''
		);

		//Menu background.
		self.bindColor(
			'#rmp-menu-background-colour',
			'#rmp-menu-wrap-' + self.menuId ,
			'background'
		);

		//Menu title section background color.
		self.bindColor(
			'#rmp-menu-title-background-colour',
			'#rmp-menu-title-' + self.menuId ,
				'background'
		);

		//Menu title section background hover color.
		self.bindColor(
			'#rmp-menu-title-background-hover-colour',
			'#rmp-menu-title-' + self.menuId ,
			'background',
			'hover'
		);

		// Menu item trigger

		self.bindColor(
			'#rmp-menu-sub-arrow-shape-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'color'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-shape-hover-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'color',
			'hover'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-shape-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow.rmp-menu-subarrow-active',
			'color'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-shape-hover-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow.rmp-menu-subarrow-active',
			'color',
			'hover'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-border-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'border-color'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-border-hover-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'border-color',
			'hover'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-border-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow-active',
			'border-color'
		);


		self.bindColor(
			'#rmp-menu-sub-arrow-border-hover-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow-active',
			'border-color',
			'hover'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-background-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'background'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-background-hover-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'background',
			'hover'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-background-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow.rmp-menu-subarrow-active',
			'background'
		);

		self.bindColor(
			'#rmp-menu-sub-arrow-background-hover-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow.rmp-menu-subarrow-active',
			'background',
			'hover'
		);

		//Legacy options
		self.bindColor(
			'#rmp-submenu-sub-arrow-shape-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'color'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-shape-colour-hover',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'color',
			'hover'
		);


		self.bindColor(
			'#rmp-submenu-sub-arrow-shape-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow-active',
			'color'
		);


		self.bindColor(
			'#rmp-submenu-sub-arrow-shape-hover-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow-active',
			'color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-border-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'border-color'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-border-hover-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'border-color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-border-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow.rmp-menu-subarrow-active',
			'border-color'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-border-hover-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow-active',
			'border-color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-background-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'background'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-background-hover-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'background',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-background-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow-active',
			'background'
		);

		self.bindColor(
			'#rmp-submenu-sub-arrow-background-hover-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow-active',
			'background',
			'hover'
		);

		self.bindColor('#rmp-menu-title-colour', '#rmp-menu-title-' + self.menuId + ' > span' , 'color');
		self.bindColor('#rmp-menu-title-hover-colour', '#rmp-menu-title-' + self.menuId + ' > span' , 'color','hover');
		self.bindColor('#rmp-menu-additional-content-color', '#rmp-container-'+ self.menuId + ' #rmp-menu-additional-content-' + self.menuId  , 'color');
		self.bindColor('#rmp-menu-search-box-text-colour', '#rmp-container-'+ self.menuId + ' #rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'color');
		self.bindColor('#rmp-menu-search-box-background-colour', '#rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'background');
		self.bindColor('#rmp-menu-search-box-border-colour', '#rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'border-color');
		self.bindColor('#rmp-menu-search-box-placeholder-colour', '#rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'color', 'placeholder');
		self.changeInput('#rmp-menu-title-font-weight', '#rmp-menu-title-' + self.menuId +' #rmp-menu-title-link', 'font-weight' );
		self.changeInput('#rmp-menu-title-font-family', '#rmp-menu-title-' + self.menuId +' #rmp-menu-title-link', 'font-family' );

		//Menu Trigger
		self.bindColor('#rmp-menu-button-background-colour', '#rmp_menu_trigger-' + self.menuId , 'background', '' );
		self.bindColor('#rmp-menu-button-background-colour-hover', '#rmp_menu_trigger-' + self.menuId , 'background-color', 'hover' );
		self.bindColor('#rmp-menu-button-background-colour-active', '#rmp_menu_trigger-' + self.menuId + '.is-active' , 'background', '' );

		self.bindColor('#rmp-menu-button-line-colour', '#rmp_menu_trigger-' + self.menuId + ' .responsive-menu-pro-inner,#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after,#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before', 'background', '' );
		self.bindColor('#rmp-menu-button-line-colour-active', '.is-active#rmp_menu_trigger-' + self.menuId + ' .responsive-menu-pro-inner,.is-active#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after,.is-active#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before', 'background','' );
		self.bindColor('#rmp-menu-button-line-colour-hover', '#rmp_menu_trigger-' + self.menuId + ':hover .responsive-menu-pro-inner,#rmp_menu_trigger-' + self.menuId +':hover .responsive-menu-pro-inner:after,#rmp_menu_trigger-' + self.menuId +':hover .responsive-menu-pro-inner:before', 'background','' );
		self.bindColor('#rmp-menu-button-text-colour', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'color' );
		self.onTyping('#rmp-menu-toggle-border-radius','#rmp_menu_trigger-' + self.menuId ,'border-radius' );
		self.bindColor('#rmp-menu-button-line-colour', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .rmp-trigger-icon-inactive .rmp-font-icon', 'color', '' );
		self.bindColor('#rmp-menu-button-line-colour-active', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .rmp-trigger-icon-active .rmp-font-icon', 'color','' );
		self.bindColor('#rmp-menu-button-line-colour-hover', '#rmp_menu_trigger-' + self.menuId + ':hover .rmp-trigger-box .rmp-trigger-icon-inactive .rmp-font-icon', 'color','' );

		self.onTyping('.rmp-menu-container-padding','#rmp-container-'+ self.menuId , 'section-padding' );
		self.onTyping('.rmp-menu-title-section-padding','#rmp-menu-title-'+ self.menuId , 'section-padding' );
		self.onTyping('.rmp-menu-section-padding','#rmp-menu-wrap-'+ self.menuId , 'section-padding' );
		self.onTyping('.rmp-menu-search-section-padding','#rmp-search-box-'+ self.menuId , 'section-padding' );
		self.onTyping('.rmp-menu-additional-section-padding','#rmp-menu-additional-content-'+ self.menuId , 'section-padding' );

		// CONTENT BASED ELEMENTS.

		self.onTyping('#rmp-menu-search-box-height','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','height' );
		self.onTyping('#rmp-menu-search-box-border-radius','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','border-radius' );


		self.onTyping('#rmp-menu-menu-title','#rmp-menu-title-'+ self.menuId +' .rmp-menu-title-link span', 'text' );
		self.onTyping('#rmp-menu-additional-content','#rmp-menu-additional-content-'+ self.menuId,'text');
		self.onTyping('#rmp-menu-search-box-text','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','placeholder');
		self.onTyping('#rmp-menu-title-image-alt', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image','alt');
		self.onTyping('#rmp-menu-title-font-size', '#rmp-menu-title-' + self.menuId + ' > a','font-size');

		self.onTyping('#rmp-menu-title-image-width', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image','width');
		self.onTyping('#rmp-menu-title-image-height', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image','height');

		self.bindImage('#rmp-menu-title-image-selector', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image', 'img-src' );

		self.onTyping('#rmp-menu-additional-content-font-size', '#rmp-menu-additional-content-' + self.menuId ,'font-size' );

		self.onTyping('#rmp-menu-container-width', '#rmp-container-'+ self.menuId, 'width' );
		self.onTyping('#rmp-menu-container-min-width', '#rmp-container-'+ self.menuId, 'min-width' );
		self.onTyping('#rmp-menu-container-max-width', '#rmp-container-'+ self.menuId, 'max-width' );

		self.onTyping('#rmp-menu-button-image-alt-when-clicked', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-active', 'alt' );
		self.onTyping('#rmp-menu-button-image-alt', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-inactive', 'alt' );

		self.onTyping('#rmp-menu-button-title-open', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-text-open', 'trigger-text-open' );
		self.onTyping('#rmp-menu-button-title', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-text', 'trigger-text' );
		self.onTyping('#rmp-menu-button-font-size', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'font-size' );
		self.onTyping('#rmp-menu-button-title-line-height', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'line-height' );

		//Menu Trigger
		self.onTyping(
			'#rmp-menu-button-width',
			'#rmp_menu_trigger-' + self.menuId,
			'width'
		);
		self.onTyping(
			'#rmp-menu-button-height',
			'#rmp_menu_trigger-' + self.menuId ,
			'height'
		);

		self.onTyping(
			'#rmp-menu-button-line-width',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner',
			'width'
		);

		self.onTyping(
			'#rmp-menu-button-line-width',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after',
			'width'
		);

		self.onTyping(
			'#rmp-menu-button-line-width',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before',
			'width'
		);

		self.onTyping(
			'#rmp-menu-button-line-height',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner',
			'height'
		);

		self.onTyping(
			'#rmp-menu-button-line-margin',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after',
			'bottom'
		);

		self.onTyping(
			'#rmp-menu-button-line-margin',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before',
			'top'
		);

		self.onTyping(
			'#rmp-menu-button-line-height',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after',
			'height'
		);

		self.onTyping(
			'#rmp-menu-button-line-height',
			'#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before',
			'height'
		);

		self.bindImage('#rmp-button-title-image', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image', 'img-src' );
		self.bindImage('#rmp-menu-background-image-selector', '#rmp-container-'+ self.menuId, 'background' );

		self.bindImage('#rmp-menu-button-image-when-clicked-selector', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-active', 'trigger-icon-open' );
		self.bindImage('#rmp-menu-button-image-selector', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-inactive', 'trigger-icon' );

		self.changeInput(
			'#rmp-menu-title-link-location',
			'#rmp-menu-title-' + self.menuId + ' #rmp-menu-title-link',
			'target'
		);

		self.changeInput('.rmp-menu-title-alignment', '#rmp-menu-title-' + self.menuId ,'text-align');
		self.changeInput('.rmp-menu-additional-content-alignment', '#rmp-menu-additional-content-'+ self.menuId,'text-align');

		//Top menu item links
		self.onTyping(
			'#rmp-menu-links-height',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link',
			'height'
		);

		self.onTyping(
			'#rmp-menu-links-line-height',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link',
			'line-height'
		);

		self.onTyping(
			'#rmp-menu-font-size',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link',
			'font-size'
		);

		self.changeInput('#rmp-menu-font', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'font-family' );
		self.changeInput('#rmp-menu-font-weight', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'font-weight' );
		self.changeInput('.rmp-menu-text-alignment', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'text-align' );

		self.changeInput(
			'#rmp-menu-text-letter-spacing',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link',
			'letter-spacing'
		);

		self.onTyping(
			'#rmp-menu-depth-level-0',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link',
			'padding',
			'lr'
		);

		self.onTyping(
			'#rmp-menu-border-width',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link',
			'border-width'
		);

		self.onTyping(
			'#rmp-menu-sub-arrow-border-width',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'border-width'
		);

		self.onTyping(
			'#rmp-submenu-sub-arrow-border-width',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'border-width'
		);

		self.bindColor('#rmp-menu-link-color', '#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link', 'color');
		self.bindColor('#rmp-menu-link-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link', 'color','hover');
		self.bindColor('#rmp-menu-current-link-active-color', '#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-current-item .rmp-menu-item-link', 'color');
		self.bindColor('#rmp-menu-current-link-active-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-current-item .rmp-menu-item-link', 'color','hover');

		self.bindColor('#rmp-menu-item-background-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link', 'background');
		self.bindColor('#rmp-menu-item-background-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link', 'background','hover');
		self.bindColor('#rmp-menu-current-item-background-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-current-item .rmp-menu-item-link', 'background');
		self.bindColor('#rmp-menu-current-item-background-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-current-item .rmp-menu-item-link', 'background','hover');

		self.bindColor('#rmp-menu-item-border-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link', 'border-color');
		self.bindColor('#rmp-menu-item-border-colour-hover', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-item-link', 'border-color', 'hover' );
		self.bindColor('#rmp-menu-current-item-border-hover-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-current-item .rmp-menu-item-link', 'border-color', 'hover' );
		self.bindColor('#rmp-menu-item-border-colour-active', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-current-item .rmp-menu-item-link', 'border-color');

		// Trigger of top level
		self.bindImage('#rmp-menu-inactive-arrow-image-selector', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow', 'background' );
		self.bindImage('#rmp-menu-active-arrow-image-selector', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow-active', 'background' );

		self.onTyping(
			'#rmp-submenu-arrow-height',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'height'
		);

		self.onTyping(
			'#rmp-submenu-arrow-width',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow',
			'width'
		);

		self.onTyping(
			'#rmp-submenu-child-arrow-height',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'height'
		);


		self.onTyping(
			'#rmp-submenu-child-arrow-width',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'width'
		);

		self.changeInput(
			'#rmp-submenu-child-arrow-width-unit',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'width-unit'
		);

		self.changeInput(
			'#rmp-submenu-child-arrow-height-unit',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
			'height-unit'
		);

		self.bindColor('#rmp-menu-sub-arrow-background-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow', 'background');
		self.bindColor('#rmp-menu-sub-arrow-background-hover-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow', 'background','hover');
		self.bindColor('#rmp-menu-sub-arrow-background-colour-active', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow-active', 'background');
		self.bindColor('#rmp-menu-sub-arrow-background-hover-colour-active', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow-active', 'background','hover' );

		//sub menu item links
		self.onTyping(
			'#rmp-submenu-links-height',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'height'
		);

		self.changeInput(
			'#rmp-submenu-links-height-unit',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'height-unit'
		);

		self.onTyping(
			'#rmp-submenu-links-line-height',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'line-height'
		);

		self.changeInput(
			'#rmp-submenu-links-line-height-unit',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'line-height-unit'
		);

		self.onTyping(
			'#rmp-submenu-font-size',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'font-size'
		);

		self.changeInput(
			'#rmp-submenu-font',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'font-family'
		);

		self.changeInput(
			'#rmp-submenu-font-weight',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'font-weight'
		);

			self.changeInput(
				'.rmp-submenu-text-alignment',
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
				'text-align'
		);

		self.changeInput(
			'#rmp-submenu-text-letter-spacing',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'letter-spacing'
		);

		self.onTyping(
			'#rmp-submenu-border-width',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'border-width'
		);

		self.bindColor(
			'#rmp-submenu-item-border-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'border-color'
		);

		self.bindColor(
			'#rmp-submenu-item-border-colour-hover',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'border-color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-item-border-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link',
			'border-color',
		);

		self.bindColor(
			'#rmp-submenu-current-item-border-hover-colour',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link',
			'border-color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-link-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'color'
		);

		self.bindColor(
			'#rmp-submenu-link-hover-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-link-colour-active',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link',
			'color'
		);

		self.bindColor(
			'#rmp-submenu-link-active-hover-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link',
			'color',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-item-background-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'background'
		);

		self.bindColor(
			'#rmp-submenu-item-background-hover-color',
			' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link',
			'background',
			'hover'
		);

		self.bindColor(
			'#rmp-submenu-current-item-background-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link',
			'background'
		);

		self.bindColor(
			'#rmp-submenu-current-item-background-hover-color',
			'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link',
			'background',
			'hover'
		);

		//Menu Trigger
		self.changeInput('.rmp-menu-button-transparent-background', '#rmp_menu_trigger-' + self.menuId , 'background','');
		self.changeInput('#rmp-menu-button-position-type', '#rmp_menu_trigger-' + self.menuId , 'position');
		self.changeInput('.rmp-menu-button-left-or-right', '#rmp_menu_trigger-' + self.menuId , 'trigger-side');
		self.onTyping('#rmp-menu-button-distance-from-side', '#rmp_menu_trigger-' + self.menuId , 'trigger-side-position');
		self.onTyping('#rmp-menu-button-top', '#rmp_menu_trigger-' + self.menuId , 'top');
		self.changeInput('#rmp-menu-button-click-animation', '#rmp_menu_trigger-' + self.menuId , 'trigger-animation');
		self.changeInput('#rmp-menu-button-font', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'font-family' );
		self.changeInput('.rmp-menu-button-title-position', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'position-alignment' );

		jQuery("#rmp-menu-button-font-icon").focus(function() {
			var outputSelector = '#rmp_menu_trigger-' + self.menuId + ' span.rmp-trigger-icon-inactive';
			let value = jQuery(this).val();
			var iframe =   jQuery(self.iframe);
			if ( "" != value ) {
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box img.rmp-trigger-icon' ).hide();
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .responsive-menu-pro-inner' ).hide();
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .rmp-trigger-icon-inactive' ).remove();
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).append('<span class="rmp-trigger-icon rmp-trigger-icon-inactive">' + value +'</span>');
			}
		});

		jQuery("#rmp-menu-button-font-icon-when-clicked").focus(function() {
			var outputSelector = '#rmp_menu_trigger-' + self.menuId + ' span.rmp-trigger-icon-active';
			let value = jQuery(this).val();
			var iframe =   jQuery(self.iframe);
			if ( "" != value ) {
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box img.rmp-trigger-icon' ).hide();
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .responsive-menu-pro-inner' ).hide();
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .rmp-trigger-icon-active' ).remove();
				iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).append('<span class="rmp-trigger-icon rmp-trigger-icon-active">' + value +'</span>');
			}
		});

		// Ordering elements
		self.toggleElements('#rmp-item-order-title','#rmp-menu-title-' + self.menuId );
		self.toggleElements('#rmp-item-order-additional-content','#rmp-menu-additional-content-' + self.menuId );
		self.toggleElements('#rmp-item-order-search','#rmp-search-box-'+ self.menuId);
		self.toggleElements('#rmp-item-order-menu','#rmp-menu-wrap-' + self.menuId );

		jQuery( '#rmp-menu-ordering-items' ).sortable( {
			update: function( event, ui ) {
				self.orderMenuElements();
			}
		});
	}
};

RMP_Preview.init();

