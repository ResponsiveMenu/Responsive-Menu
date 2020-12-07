jQuery(document).ready(function(jQuery) {

	/**
	 * Hooks class.
	 * 
	 * @since 4.0.0
	 */
	var rmpHook = {
		hooks: [ ],
		is_break: false,

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
		 * @param String   name      Hook Name.
		 * @param function arguments Paramter list.
		 */
		call: function( name, arguments ) {
			if ( 'undefined' != typeof ( rmpHook.hooks[name] ) ) {
				for ( i = 0; i < rmpHook.hooks[name].length; ++i ) {
					output = rmpHook.hooks[name][i]( arguments );
					if ( false == output ) {
						rmpHook.is_break = true;
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
		} else if( 'placeholder' == args.state ) {
			args.outputSelector = args.outputSelector + '::placeholder';
		}

		//Prepare css string and return.
		return args.outputSelector + '{ ' + args.attr +' : ' +  args.value + ';}';
	} );

	var RMP_Preview = {
		iframe : '#rmp-preview-iframe',
		menuId : jQuery('#menu_id').val(),
		mobile_breakpoint : jQuery('#rmp-menu-mobile-breakpoint').val() + 'px',
		tablet_breakpoint : jQuery('#rmp-menu-tablet-breakpoint').val() + 'px',
		active_device: jQuery('#rmp_device_mode'),
		menuContainer : '#rmp-container-'+ self.menuId,

		onTyping: function( inputSelector, outputSelector, type) {
			var self = this;
			var iframe  = jQuery(self.iframe);
			jQuery(inputSelector).on( 'keyup change paste', function() {
				switch( type ) {
					case 'border-radius':
						var value = jQuery(this).val();
						css = outputSelector + '{ border-radius : '+ ( value ) +'px;}';
						self.inlineCssInjector(css);
					break;
					case 'section-padding':
						var value = jQuery(this).val();
						var is_linked = jQuery(this).parents('.rmp-input-group-control').find('.rmp-group-input-linked').hasClass('is-linked');
						var attr = 'padding';
						if( ! is_linked ) {
							pos = jQuery(this).attr('data-input');
							attr = attr + '-' + pos;
						}
						css = outputSelector + '{ '+ attr +' : '+ ( value ) +';}';
						self.inlineCssInjector(css);

					break;
					case 'trigger-line-width':
						var unit = jQuery(this).next('.is-unit').val();
						css = outputSelector + '{ width : '+ ( this.value + unit ) +';}';
						self.inlineCssInjector(css);
					break;
					case 'trigger-line-height':
						var unit = jQuery(this).next('.is-unit').val();
						css = outputSelector + '{ height : '+ ( this.value + unit ) +';}';
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
						var unit = jQuery(this).next( '.is-unit' ).val();

						if ( ! unit.length ) {
							unit = 'px';
						}

						var value = jQuery(this).val();
						css = outputSelector + '{ font-size : '+  ( value + unit ) + '!important;}';

						if ( jQuery(this).attr( 'multi-device') ) {
							css = self.mediaQuery( css );
						}

						self.inlineCssInjector(css);

					break;

					case 'width':
						var unit = jQuery(this).next('.is-unit').val();

						if ( ! unit ) {
							unit = 'px';
						}

						css = outputSelector + '{ width : '+ (this.value + unit) +';}';
						self.inlineCssInjector(css);

					break;

					case 'height':
						var unit = jQuery(this).next('.is-unit').val();

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
						var unit = jQuery(this).next('.is-unit').val();

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
						var unit = jQuery(this).next('.is-unit').val();

						if ( ! unit.length ) {
							unit = 'px';
						}

						css = outputSelector + '{ min-width : '+ (this.value + unit) +';}';
						
						self.inlineCssInjector(css);

					break;
					case 'max-width':
						var unit = jQuery(this).next('.is-unit').val();

						if ( ! unit.length ) {
							unit = 'px';
						}
						css = outputSelector + '{ max-width : '+ (this.value + unit) +';}';
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
						css = outputSelector + '{ background-image : url('+  attachment.url + ');}';
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

			self.mobile_breakpoint = jQuery('#rmp-menu-mobile-breakpoint').val() + 'px';
			self.tablet_breakpoint = jQuery('#rmp-menu-tablet-breakpoint').val() + 'px';
			self.active_device = jQuery('#rmp_device_mode');

			if( 'desktop' === self.active_device.val() ) {
				$css = '@media screen and (min-width: '+ self.tablet_breakpoint +' ) {' + css + '}';
				return $css; 
			} else if( 'tablet' === self.active_device.val() ) {
				$css = '@media screen and (max-width: '+ self.tablet_breakpoint +') and (min-width : '+ self.mobile_breakpoint +') {' + css + '}';
				return $css; 
			} else if( 'mobile' === self.active_device.val() ) {
				$css = '@media screen and (max-width: '+ self.mobile_breakpoint +' ) {' + css + '}';
				return $css; 
			}

			return css;
		},
		inlineCssInjector: function( css ) {
			var self = this;
			var iframe = jQuery(self.iframe);
			var styleElement = iframe.contents().find( '#rmp-inline-css-' + self.menuId );
			if ( styleElement.length ) {
				styleElement.append(css);
			} else {
				style = '<style id="rmp-inline-css-'+ self.menuId +'">'+ css + '</style>';
				iframe.contents().find('head').append(style);
			}
		},
		changeInput: function( inputSelector, outputSelector , attr, meta = '' ) {
			var self = this;
			var iframe = jQuery(self.iframe);
			jQuery(inputSelector).on( 'change', function(e) {
				switch (attr) {
					case 'height-unit':
						value = jQuery(this).prev('input').val();
						unit = jQuery(this).val(); 

						css = outputSelector + '{ height : '+  ( value + unit ) + ';}';
						if ( jQuery(this).attr( 'multi-device') ) {
							css = self.mediaQuery( css );
						}

						self.inlineCssInjector(css);
					break;
					case 'line-height-unit':
						value = jQuery(this).prev('input').val();
						unit = jQuery(this).val(); 

						css = outputSelector + '{ line-height : '+  ( value+unit ) + ';}';

						if ( jQuery(this).attr( 'multi-device') ) {
							css = self.mediaQuery( css );
						}

						self.inlineCssInjector(css);
					break;
					case 'width-unit':
						value = jQuery(this).prev('input').val();
						unit = jQuery(this).val(); 
						iframe.contents().find( outputSelector ).css({
							'width' : value+unit,
						} );
					break;
					case 'font-size':
						value = jQuery(this).prev('input').val();
						unit = jQuery(this).val(); 
						css = outputSelector + '{ font-size :' + value + unit + ' !important;}';

						if ( jQuery(this).attr( 'multi-device') ) {
							css = self.mediaQuery( css );
						}

						self.inlineCssInjector(css);
					break;
					case 'font-family':
						value = jQuery(this).val();
						css = outputSelector + '{ font-family :' + value +' !important;}';
						
						if ( jQuery(this).attr( 'multi-device') ) {
							css = self.mediaQuery( css );
						}

						self.inlineCssInjector(css);
					break;
					case 'font-weight':
						value = jQuery(this).val();
						css = outputSelector + '{ font-weight :' + value +';}';
						self.inlineCssInjector(css);
					break;
					case 'padding':
						var unit = jQuery(this).next('.is-unit').val();

						if ( ! unit ) {
							unit = 'px';
						}

						if( meta == 'lr') {
							css = outputSelector + '{ padding : 0 '+ (this.value + unit) +';}';
						}

						self.inlineCssInjector(css);
					break;
					case 'letter-spacing':
						value = jQuery(this).val();
						css = outputSelector + '{ letter-spacing :' + value +'px; }';
						self.inlineCssInjector(css);
					break;
					case 'position-alignment':
						
						if ( iframe.contents().find( outputSelector ).length ) {
							position  = jQuery(this).val();
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
						value = jQuery(this).val();
						var new_class = 'rmp-menu-trigger-' + value;
						all_class =  iframe.contents().find( outputSelector ).attr('class').split(" ");
						all_class.forEach( function( value ) {
							if ( value.includes( 'rmp-menu-trigger-' ) ) {
								iframe.contents().find( outputSelector ).removeClass(value);
								iframe.contents().find( outputSelector ).addClass(new_class);
							}
						});
					break;
					case 'top':
						value = jQuery(this).val();
						unit  = jQuery('#rmp-menu-button-top-unit').val();
						css = outputSelector + '{ top :' + (value + unit) +' !important;}';
						self.inlineCssInjector(css);
					break;
					case 'trigger-side-position':
						side = jQuery('#rmp-menu-button-left-or-right').val();
						unit  = jQuery('#rmp-menu-button-distance-from-side-unit').val();
						value = jQuery('#rmp-menu-button-distance-from-side').val();
						css = outputSelector + '{ '+ side +' :'+ (value + unit) +' !important;}';
						self.inlineCssInjector(css);
					break;
					case 'trigger-side':
						side  = jQuery(this).val();
						value = jQuery('#rmp-menu-button-distance-from-side').val();
						unit  = jQuery('#rmp-menu-button-distance-from-side-unit').val();

						if( side == 'left' ) {
							css = outputSelector + '{' + side + ':'+ ( value + unit ) +' !important;right:unset !important}';
						} else {
							css = outputSelector + '{' + side + ':'+ ( value + unit ) +' !important;left:unset !important}';
						}

						self.inlineCssInjector(css);

					break;
					case 'position':
						value = jQuery(this).val();
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
						if ( jQuery(this).is(':checked') ) {
							iframe.contents().find(outputSelector).attr('target', '_blank' );
						} else {
							iframe.contents().find(outputSelector).attr('target', '_self' );
						}
					break;
					case 'text-align':
						var value =  jQuery(this).val();
						iframe.contents().find( outputSelector ).css({
							'text-align' : value,
						} );
					break;
					case 'border-width':
						var unit = jQuery(this).next('.is-unit').val();

						if ( ! unit ) {
							unit = 'px';
						}

						css = outputSelector + '{ border-width : '+ (this.value + unit) +';}';
						self.inlineCssInjector(css);

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
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow.rmp-menu-subarrow-active',
				'border-color'
			);


			self.bindColor(
				'#rmp-menu-sub-arrow-border-hover-colour-active',
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow.rmp-menu-subarrow-active',
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
				'#rmp-submenu-item-border-colour-hover',
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow',
				'color',
				'hover'
			);


			self.bindColor(
				'#rmp-submenu-sub-arrow-shape-colour-active',
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow.rmp-menu-subarrow-active',
				'color'
			);


			self.bindColor(
				'#rmp-submenu-sub-arrow-shape-hover-colour-active',
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow.rmp-menu-subarrow-active',
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
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow.rmp-menu-subarrow-active',
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
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow.rmp-menu-subarrow-active',
				'background'
			);

			self.bindColor(
				'#rmp-submenu-sub-arrow-background-hover-colour-active',
				'#rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-subarrow.rmp-menu-subarrow-active',
				'background',
				'hover'
			);

			self.bindColor('#rmp-menu-title-colour', '#rmp-menu-title-' + self.menuId + ' > a' , 'color');
			self.bindColor('#rmp-menu-title-hover-colour', '#rmp-menu-title-' + self.menuId + ' > a' , 'color','hover');
			self.bindColor('#rmp-menu-additional-content-color', '#rmp-container-'+ self.menuId + ' #rmp-menu-additional-content-' + self.menuId  , 'color');
			self.bindColor('#rmp-menu-search-box-text-colour', '#rmp-container-'+ self.menuId + ' #rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'color');
			self.bindColor('#rmp-menu-search-box-background-colour', '#rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'background');
			self.bindColor('#rmp-menu-search-box-border-colour', '#rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'border-color');
			self.bindColor('#rmp-menu-search-box-placeholder-colour', '#rmp-search-box-'+ self.menuId + ' .rmp-search-box'  , 'color', 'placeholder');
			
			//Menu Trigger
			self.bindColor('#rmp-menu-button-background-colour', '#rmp_menu_trigger-' + self.menuId , 'background', '' );
			self.bindColor('#rmp-menu-button-background-colour-hover', '#rmp_menu_trigger-' + self.menuId , 'background-color', 'hover' );
			self.bindColor('#rmp-menu-button-background-colour-active', '#rmp_menu_trigger-' + self.menuId + '.is-active' , 'background', '' );

			self.bindColor('#rmp-menu-button-line-colour', '#rmp_menu_trigger-' + self.menuId + ' .responsive-menu-pro-inner,#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after,#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before', 'background', '' );
			self.bindColor('#rmp-menu-button-line-colour-active', '.is-active#rmp_menu_trigger-' + self.menuId + ' .responsive-menu-pro-inner,.is-active#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after,.is-active#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before', 'background','' );
			self.bindColor('#rmp-menu-button-line-colour-hover', '#rmp_menu_trigger-' + self.menuId + ':hover .responsive-menu-pro-inner,#rmp_menu_trigger-' + self.menuId +':hover .responsive-menu-pro-inner:after,#rmp_menu_trigger-' + self.menuId +':hover .responsive-menu-pro-inner:before', 'background','' );
			self.bindColor('#rmp-menu-button-text-colour', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'color' );

			self.onTyping('.rmp-menu-container-padding','#rmp-container-'+ self.menuId , 'section-padding' );
			self.onTyping('.rmp-menu-title-section-padding','#rmp-menu-title-'+ self.menuId , 'section-padding' );
			self.onTyping('.rmp-menu-section-padding','#rmp-menu-wrap-'+ self.menuId , 'section-padding' );
			self.onTyping('.rmp-menu-search-section-padding','#rmp-search-box-'+ self.menuId , 'section-padding' );
			self.onTyping('.rmp-menu-additional-section-padding','#rmp-menu-additional-content-'+ self.menuId , 'section-padding' );

			// CONTENT BASED ELEMENTS.

			self.onTyping('#rmp-menu-search-box-height','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','height' );
			self.changeInput('#rmp-menu-search-box-height-unit','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','height-unit' );


			self.onTyping('#rmp-menu-search-box-border-radius','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','border-radius' );


			self.onTyping('#rmp-menu-menu-title','#rmp-menu-title-'+ self.menuId +' #rmp-menu-title-link span', 'text' );
			self.onTyping('#rmp-menu-additional-content','#rmp-menu-additional-content-'+ self.menuId,'text');
			self.onTyping('#rmp-menu-search-box-text','#rmp-search-box-'+ self.menuId + ' .rmp-search-box','placeholder');
			self.onTyping('#rmp-menu-title-link', '#rmp-menu-title-' + self.menuId + ' #rmp-menu-title-link','href');
			self.onTyping('#rmp-menu-title-image-alt', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image','alt');
			self.onTyping('#rmp-menu-title-font-size', '#rmp-menu-title-' + self.menuId + ' > a','font-size');
			self.changeInput('#rmp-menu-title-font-size-unit', '#rmp-menu-title-' + self.menuId + ' > a','font-size' );
			self.changeInput('#rmp-menu-additional-content-font-size-unit', '#rmp-menu-additional-content-' + self.menuId ,'font-size' );

			self.onTyping('#rmp-menu-title-image-width', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image','width');
			self.onTyping('#rmp-menu-title-image-height', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image','height');
			self.bindImage('#rmp-menu-title-image-selector', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image', 'img-src' );

			self.onTyping('#rmp-menu-additional-content-font-size', '#rmp-menu-additional-content-' + self.menuId ,'font-size' );

			self.onTyping('#rmp-menu-container-width', '#rmp-container-'+ self.menuId, 'width' );
			self.changeInput('#rmp-menu-container-width-unit', '#rmp-container-'+ self.menuId, 'width-unit' );
			self.changeInput('#rmp-menu-button-width-unit', '#rmp_menu_trigger-' + self.menuId, 'width-unit' );

			self.changeInput('#rmp-menu-button-height-unit', '#rmp_menu_trigger-' + self.menuId , 'height-unit');


			self.onTyping('#rmp-menu-container-min-width', '#rmp-container-'+ self.menuId, 'min-width' );
			self.onTyping('#rmp-menu-container-max-width', '#rmp-container-'+ self.menuId, 'max-width' );

			self.onTyping('#rmp-menu-button-image-alt-when-clicked', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-active', 'alt' );
			self.onTyping('#rmp-menu-button-image-alt', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-inactive', 'alt' );

			self.onTyping('#rmp-menu-button-title-open', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-text-open', 'trigger-text-open' );
			self.onTyping('#rmp-menu-button-title', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-text', 'trigger-text' );
			self.onTyping('#rmp-menu-button-font-size', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'font-size' );
			self.changeInput('#rmp-menu-button-font-size-unit', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'font-size' );

			self.onTyping('#rmp-menu-button-title-line-height', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'line-height' );
			self.changeInput('#rmp-menu-button-title-line-height-unit', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'line-height-unit' );

			//Menu Trigger
			self.onTyping('#rmp-menu-button-width', '#rmp_menu_trigger-' + self.menuId, 'width' );
			self.onTyping('#rmp-menu-button-height', '#rmp_menu_trigger-' + self.menuId , 'height');

			self.onTyping('#rmp-menu-button-line-width', '#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner', 'trigger-line-width' );
			self.onTyping('#rmp-menu-button-line-width', '#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after', 'trigger-line-width' );
			self.onTyping('#rmp-menu-button-line-width', '#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before', 'trigger-line-width' );
			self.onTyping('#rmp-menu-button-line-height', '#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner', 'trigger-line-height' );
			self.onTyping('#rmp-menu-button-line-height', '#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:after', 'trigger-line-height' );
			self.onTyping('#rmp-menu-button-line-height', '#rmp_menu_trigger-' + self.menuId +' .responsive-menu-pro-inner:before', 'trigger-line-height' );

			self.bindImage('#rmp-button-title-image', '#rmp-menu-title-' + self.menuId + ' .rmp-menu-title-image', 'img-src' );
			self.bindImage('#rmp-menu-background-image-selector', '#rmp-container-'+ self.menuId, 'background' );
			
			self.bindImage('#rmp-menu-button-image-when-clicked-selector', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-active', 'trigger-icon-open' );
			self.bindImage('#rmp-menu-button-image-selector', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-icon-inactive', 'trigger-icon' );


			

			self.changeInput('#rmp-menu-title-link-location', '#rmp-menu-title-' + self.menuId + ' #rmp-menu-title-link','target');
			self.changeInput('.rmp-menu-title-alignment', '#rmp-menu-title-' + self.menuId ,'text-align');
			self.changeInput('.rmp-menu-additional-content-alignment', '#rmp-menu-additional-content-'+ self.menuId,'text-align');

			//Top menu item links
			self.onTyping('#rmp-menu-links-height', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'height');
			self.onTyping('#rmp-menu-links-line-height', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'line-height');
			self.onTyping('#rmp-menu-font-size', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'font-size');
			self.changeInput('#rmp-menu-font-size-unit', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'font-size');


			self.changeInput('#rmp-menu-font', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'font-family' );
			self.changeInput('#rmp-menu-font-weight', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'font-weight' );
			self.changeInput('.rmp-menu-text-alignment', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'text-align' );
			self.changeInput('#rmp-menu-text-letter-spacing', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'letter-spacing' );
			self.changeInput('#rmp-menu-depth-level-0', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'padding', 'lr' );

			self.changeInput('#rmp-menu-border-width', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'border-width' );
			self.changeInput('#rmp-menu-sub-arrow-border-width', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow', 'border-width' );
			self.changeInput('#rmp-submenu-sub-arrow-border-width', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-sub-level-item .rmp-menu-subarrow', 'border-width' );
 
			self.bindColor('#rmp-menu-link-color', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'color');
			self.bindColor('#rmp-menu-link-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'color','hover');
			self.bindColor('#rmp-menu-current-link-active-color', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item.rmp-menu-current-item .rmp-menu-item-link', 'color');
			self.bindColor('#rmp-menu-current-link-active-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item.rmp-menu-current-item .rmp-menu-item-link', 'color','hover');

			self.bindColor('#rmp-menu-item-background-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'background');
			self.bindColor('#rmp-menu-item-background-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'background','hover');
			self.bindColor('#rmp-menu-current-item-background-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item.rmp-menu-current-item .rmp-menu-item-link', 'background');
			self.bindColor('#rmp-menu-current-item-background-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item.rmp-menu-current-item .rmp-menu-item-link', 'background','hover');

			self.bindColor('#rmp-menu-item-border-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-item-link', 'border-color');

			// Trigger of top level
			self.bindImage('#rmp-menu-inactive-arrow-image-selector', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow', 'background' );
			self.bindImage('#rmp-menu-active-arrow-image-selector', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow-active', 'background' );

			self.onTyping('#rmp-submenu-arrow-height', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow', 'height');
			self.onTyping('#rmp-submenu-arrow-width', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow', 'width');
			self.changeInput('#rmp-submenu-arrow-width-unit', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow', 'width-unit');
			self.changeInput('#rmp-submenu-arrow-height-unit', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-subarrow', 'height-unit');

			self.onTyping('#rmp-submenu-child-arrow-height', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-sub-level-item .rmp-menu-subarrow', 'height');
			self.onTyping('#rmp-submenu-child-arrow-width', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-sub-level-item .rmp-menu-subarrow', 'width');
			self.changeInput('#rmp-submenu-child-arrow-width-unit', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-sub-level-item .rmp-menu-subarrow', 'width-unit');
			self.changeInput('#rmp-submenu-child-arrow-height-unit', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-sub-level-item .rmp-menu-subarrow', 'height-unit');


			self.bindColor('#rmp-menu-sub-arrow-background-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow', 'background');
			self.bindColor('#rmp-menu-sub-arrow-background-hover-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow', 'background','hover');
			self.bindColor('#rmp-menu-sub-arrow-background-colour-active', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow-active', 'background');
			self.bindColor('#rmp-menu-sub-arrow-background-hover-colour-active', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-menu-top-level-item .rmp-menu-subarrow-active', 'background','hover' );

			 //sub menu item links
			 self.onTyping('#rmp-submenu-links-height', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'height');
			 self.onTyping('#rmp-submenu-links-line-height', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'line-height');
			 self.onTyping('#rmp-submenu-font-size', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'font-size');
			 self.changeInput('#rmp-submenu-font', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'font-family' );
			 self.changeInput('#rmp-submenu-font-weight', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'font-weight' );
			 self.changeInput('.rmp-submenu-text-alignment', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'text-align' );
			 self.changeInput('#rmp-submenu-text-letter-spacing', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'letter-spacing' );
 
			 self.changeInput('#rmp-submenu-border-width', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'border-width' );
			 self.bindColor('#rmp-submenu-item-border-colour', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'border-color');

			 self.bindColor('#rmp-submenu-link-color', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'color');
			 self.bindColor('#rmp-submenu-link-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'color','hover');
			 self.bindColor('#rmp-submenu-link-colour-active', '#rmp-container-' + self.menuId +' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link', 'color');
			 self.bindColor('#rmp-submenu-link-active-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link', 'color','hover');
 
			 self.bindColor('#rmp-submenu-item-background-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'background');
			 self.bindColor('#rmp-submenu-item-background-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-item-link', 'background','hover');
			 self.bindColor('#rmp-submenu-current-item-background-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link', 'background');
			 self.bindColor('#rmp-submenu-current-item-background-hover-color', ' #rmp-menu-wrap-' + self.menuId + ' .rmp-submenu .rmp-menu-current-item .rmp-menu-item-link', 'background','hover');
 
			//Menu Trigger
			self.changeInput('.rmp-menu-button-transparent-background', '#rmp_menu_trigger-' + self.menuId , 'background','');
			self.changeInput('#rmp-menu-button-position-type', '#rmp_menu_trigger-' + self.menuId , 'position');
			self.changeInput('.rmp-menu-button-left-or-right', '#rmp_menu_trigger-' + self.menuId , 'trigger-side');
			self.changeInput('#rmp-menu-button-distance-from-side', '#rmp_menu_trigger-' + self.menuId , 'trigger-side-position');
			self.changeInput('#rmp-menu-button-top', '#rmp_menu_trigger-' + self.menuId , 'top');
			self.changeInput('#rmp-menu-button-click-animation', '#rmp_menu_trigger-' + self.menuId , 'trigger-animation');
			self.changeInput('#rmp-menu-button-font', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'font-family' );
			self.changeInput('.rmp-menu-button-title-position', '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-label', 'position-alignment' );

			jQuery("#rmp-menu-button-font-icon").focus(function() {
				var outputSelector = '#rmp_menu_trigger-' + self.menuId + ' span.rmp-trigger-icon-inactive';
				value = jQuery(this).val();
				var iframe =   jQuery(self.iframe);
				if ( iframe.contents().find(outputSelector).length ) {
					iframe.contents().find( outputSelector ).addClass(value);
				} else {
					iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box img.rmp-trigger-icon' ).hide();
					iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .responsive-menu-pro-inner' ).hide();
					iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).append('<span class="rmp-trigger-icon rmp-trigger-icon-inactive ' + value +'"></span>')
				}
			});

			jQuery("#rmp-menu-button-font-icon-when-clicked").focus(function() {
				var outputSelector = '#rmp_menu_trigger-' + self.menuId + ' span.rmp-trigger-icon-active';
				value = jQuery(this).val();
				var iframe =   jQuery(self.iframe);
				if ( iframe.contents().find(outputSelector).length ) {
					iframe.contents().find( outputSelector ).addClass(value);
				} else {
					iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box img.rmp-trigger-icon' ).hide();
					iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box .responsive-menu-pro-inner' ).hide();
					iframe.contents().find( '#rmp_menu_trigger-' + self.menuId + ' .rmp-trigger-box' ).append('<span class="rmp-trigger-icon rmp-trigger-icon-active ' + value +'"></span>')
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
});

