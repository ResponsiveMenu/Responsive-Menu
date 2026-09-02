/**
 * This file contain the scrips for menu frontend.
 * @author ExpressTech System
 *
 * @since 4.0.0
 */

jQuery( document ).ready( function( jQuery ) {

	/**
	 * RmpMenu Class
	 * This RMP class is handling the frontend events and action on menu elements.
	 * @since      4.0.0
	 * @access     public
	 *
	 * @class      RmpMenu
	 */
	class RmpMenu {

		/**
		 * This is constructor function which is initialize the elements and options.
		 * @access public
		 * @since 4.0.0
		 * @param {Array} options List of options.
		 */
		constructor( options ) {
			RmpMenu.activeToggleClass        = 'is-active';
			RmpMenu.openContainerClass       = 'rmp-menu-open';
			RmpMenu.activeSubMenuArrowClass  = 'rmp-menu-subarrow-active';
			RmpMenu.subMenuClass             = '.rmp-submenu';
			RmpMenu.activeTopMenuClass  	 = 'rmp-topmenu-active';

			this.options = options;
			this.menuId  = this.options['menu_id'];
			this.trigger = '#rmp_menu_trigger-' + this.menuId;

			this.isOpen  = false;

			this.container    =  '#rmp-container-' + this.menuId;
			this.headerBar    =  '#rmp-header-bar-' + this.menuId;
			this.menuWrap     =  'ul#rmp-menu-'+ this.menuId;
			this.subMenuArrow = '.rmp-menu-subarrow';
			this.wrapper      = '.rmp-container';
			this.linkElement  = '.rmp-menu-item-link';
			this.pageWrapper  = this.options['page_wrapper'];
			this.use_desktop_menu = this.options['use_desktop_menu'];
			this.originalHeight = '',
			this.animationSpeed        =  this.options['animation_speed'] * 1000;
			this.hamburgerBreakpoint   =  this.options['tablet_breakpoint'];
			this.subMenuTransitionTime =  this.options['sub_menu_speed'] * 1000;

			// The element focus returns to when the panel closes. `this.trigger` may become
			// a list of selectors once a custom click trigger is configured.
			this.primaryTrigger = '#rmp_menu_trigger-' + this.menuId;

			// Honour the visitor's motion preference: animating a menu they asked to keep
			// still is a WCAG 2.3.3 failure, so collapse every duration to zero instead.
			if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
				this.animationSpeed        = 0;
				this.subMenuTransitionTime = 0;
			}

			if ( this.options['button_click_trigger'].length > 0 ) {
				this.trigger = this.trigger +' , '+ this.options['button_click_trigger'];
			}

			//Append hamburger icon inside an element
			if ( this.options['button_position_type'] == 'inside-element' ) {
				var destination = jQuery(this.trigger).attr('data-destination');
				jQuery(this.trigger).appendTo(jQuery(destination).parent());
			}

			this.init();
		}

		/**
		 * This function register the events and initiate the menu settings.
		 */
		init() {
			const self = this;

			/**
			 * Register click event of trigger.
			 * @fires click
			 */
			jQuery( this.trigger ).on( 'click', function( e ) {
				e.stopPropagation();
				self.triggerMenu();
			} );

			// Show/Hide sub menu item when the item toggle is activated. The toggle is a
			// <button>, so Enter and Space reach this handler through a native click.
			jQuery( self.menuWrap ).find( self.subMenuArrow ).on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();
				self.triggerSubArrow( this );
			});

			if ( 'on' == self.options['menu_close_on_body_click'] ) {
				jQuery( document ).on( 'click', 'body', function ( e ) {
					if ( jQuery( window ).width() < self.hamburgerBreakpoint ) {
						if ( self.isOpen ) {
							if ( jQuery( e.target ).closest( self.container ).length || jQuery( e.target ).closest( self.target ).length ) {
								return;
							}
						}
						self.closeMenu();
					}
				});
			}

			/**
			 * Close the menu when click on menu item link before load.
			 */
			if ( self.options['menu_close_on_link_click'] == 'on') {

				jQuery(  this.menuWrap +' '+ self.linkElement ).on( 'click', function(e) {

					if( jQuery(window).width() < self.hamburgerBreakpoint ) {
						e.preventDefault();

						// When close menu on parent clicks is on.
						if ( self.options['menu_item_click_to_trigger_submenu'] == 'on' ) {
							if( jQuery(this).is( '.rmp-menu-item-has-children > ' + self.linkElement ) ) {
								return;
							}
						}

						let _href = jQuery(this).attr('href');
						let _target = ( typeof jQuery(this).attr('target') ) == 'undefined' ? '_self' : jQuery(this).attr('target');

						if( self.isOpen ) {
							if( jQuery(e.target).closest(self.subMenuArrow).length) {
								return;
							}
							if( typeof _href != 'undefined' ) {
								self.closeMenu();
								setTimeout(function() {
									window.open( _href, _target);
								}, self.animationSpeed);
							}
						}
					}
				});
			}

			// Expand Sub items on Parent Item Click.
			if ( 'on' == self.options['menu_item_click_to_trigger_submenu']  ) {
				jQuery( this.menuWrap +' .rmp-menu-item-has-children > ' + self.linkElement ).on( 'click', function(e) {
					if ( jQuery(window).width() < self.hamburgerBreakpoint ) {
						e.preventDefault();
						self.triggerSubArrow(
							jQuery(this).siblings( '.rmp-menu-subarrow' ).first()
						);
					}
				});
			}

			/*
			 * Keyboard support for the off-canvas panel.
			 *
			 * The previous implementation swallowed every Tab press on the whole document
			 * while a menu was open and drove focus with its own bookkeeping. That is a
			 * keyboard trap (WCAG 2.1.2): once inside the menu there was no way back out to
			 * the page, and Shift+Tab moved forwards like Tab. Tab order is now left to the
			 * browser; we only close the loop at the two ends of the panel, and only when
			 * focus is genuinely inside it.
			 */
			jQuery( document ).on( 'keydown.rmp-' + this.menuId, function ( event ) {
				if ( ! self.isOpen || ! self.isOffCanvas() ) {
					return;
				}

				if ( 'Escape' === event.key || 'Esc' === event.key || 27 === event.keyCode ) {
					self.handleEscape( event );
					return;
				}

				if ( 'Tab' === event.key || 9 === event.keyCode ) {
					self.handleTab( event );
				}
			} );

			// Keep the closed panel out of the tab order and the accessibility tree, and
			// re-evaluate when the viewport crosses the hamburger breakpoint.
			this.syncHiddenState();
			jQuery( window ).on( 'resize.rmp-' + this.menuId, function () {
				self.syncHiddenState();
			} );

			// Add rmp-topmenu-active class to current menu item on load
			this.setActiveMenuItemOnLoad();

		}
		/**
		 * True while the container behaves as an overlay panel, i.e. below the hamburger
		 * breakpoint. Above it the container is display:none and needs no special handling.
		 *
		 * @return {boolean}
		 */
		isOffCanvas() {
			return jQuery( window ).width() < this.hamburgerBreakpoint;
		}

		/**
		 * The elements Tab cycles through while the panel is open: the trigger that opened
		 * it, followed by everything focusable and visible inside the panel.
		 *
		 * @return {Array} Ordered list of DOM elements.
		 */
		focusCycle() {
			const focusableSelector = 'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

			return jQuery( this.primaryTrigger )
				.add( jQuery( this.container ).find( focusableSelector ) )
				.filter( ':visible' )
				.toArray();
		}

		/**
		 * Close the tab loop at the ends of the panel. Anything in between - and anything
		 * outside the menu entirely - keeps the browser's own tab order.
		 *
		 * @param {Event} event Keydown event.
		 */
		handleTab( event ) {
			const cycle = this.focusCycle();

			if ( ! cycle.length ) {
				return;
			}

			const active    = document.activeElement;
			const container = jQuery( this.container ).get( 0 );

			// Focus sits on the panel itself right after opening; it is not tabbable, so
			// wrap it manually rather than letting Shift+Tab escape behind the panel.
			if ( event.shiftKey && active === container ) {
				event.preventDefault();
				cycle[ cycle.length - 1 ].focus();
				return;
			}

			const index = cycle.indexOf( active );

			if ( -1 === index ) {
				return;
			}

			if ( ! event.shiftKey && index === cycle.length - 1 ) {
				event.preventDefault();
				cycle[0].focus();
			} else if ( event.shiftKey && 0 === index ) {
				event.preventDefault();
				cycle[ cycle.length - 1 ].focus();
			}
		}

		/**
		 * Escape collapses the innermost open submenu the visitor is standing in, and
		 * closes the whole panel when there is none.
		 *
		 * @param {Event} event Keydown event.
		 */
		handleEscape( event ) {
			const openSubmenu = jQuery( document.activeElement ).closest( '.rmp-submenu.rmp-submenu-open' );

			if ( openSubmenu.length ) {
				const parentArrow = openSubmenu.siblings( this.subMenuArrow ).first();

				if ( parentArrow.length ) {
					event.preventDefault();
					this.triggerSubArrow( parentArrow );
					parentArrow.trigger( 'focus' );
					return;
				}
			}

			event.preventDefault();
			this.closeMenu();
		}

		/**
		 * Hide the panel from assistive technology and from the tab order whenever it is
		 * closed but still laid out - a slid-out panel is merely translated off screen, so
		 * without this its links stay tabbable and a keyboard user tabs into nothing.
		 */
		syncHiddenState() {
			const container = jQuery( this.container ).get( 0 );

			if ( ! container ) {
				return;
			}

			if ( this.isOpen || ! this.isOffCanvas() ) {
				container.removeAttribute( 'aria-hidden' );
				container.removeAttribute( 'inert' );
				return;
			}

			container.setAttribute( 'aria-hidden', 'true' );
			container.setAttribute( 'inert', '' );
		}

		/**
		 * Move focus into the panel so the next Tab continues from the menu rather than
		 * from wherever the visitor was on the page.
		 */
		focusPanel() {
			const container = jQuery( this.container ).get( 0 );

			if ( ! container ) {
				return;
			}

			// The fade animation shows the container as it starts, so a task-queue turn is
			// enough to make sure the element is focusable by the time we ask.
			window.setTimeout( function () {
				container.focus( { preventScroll: true } );
			}, 0 );
		}

		/**
		 * Return focus to the trigger, but only if it currently sits inside the panel we
		 * are about to hide - closing by a click elsewhere must not steal focus.
		 */
		restoreFocus() {
			const container = jQuery( this.container ).get( 0 );

			if ( ! container || ! container.contains( document.activeElement ) ) {
				return;
			}

			jQuery( this.primaryTrigger ).trigger( 'focus' );
		}

		// Add rmp-topmenu-active class to current menu item on load
		setActiveMenuItemOnLoad() {
			const currentURL = window.location.href;
			const menuItems = jQuery(this.menuWrap).find('a');

			menuItems.each(function() {
				if (this.href === currentURL) {
					jQuery(this).closest('.rmp-menu-item-has-children').addClass(RmpMenu.activeTopMenuClass);
					return false; // Exit the loop once we've found and marked the current item
				}
			});
		}
		/**
		 * Set push translate for toggle and page wrapper.
		 */
		setWrapperTranslate() {
			let translate,translateContainer;
			switch( this.options['menu_appear_from'] ) {
				case 'left':
					translate = 'translateX(' + this.menuWidth() + 'px)';
					translateContainer = 'translateX(-' + this.menuWidth() + 'px)';
					break;
				case 'right':
					translate = 'translateX(-' + this.menuWidth() + 'px)';
					translateContainer = 'translateX(' + this.menuWidth() + 'px)';
					break;
				case 'top':
					translate = 'translateY(' + this.wrapperHeight() + 'px)';
					translateContainer = 'translateY(-' + this.menuHeight() + 'px)';
					break;
				case 'bottom':
					translate = 'translateY(-' + this.menuHeight() + 'px)';
					translateContainer = 'translateY(' + this.menuHeight() + 'px)';
					break;
			}

			if ( this.options['animation_type'] == 'push' ) {
				jQuery(this.pageWrapper).css( { 'transform':translate } );

				//If push Wrapper has body element then handle menu position.
				if	( 'body' == this.pageWrapper ) {
					jQuery( this.container ).css( { 'transform' : translateContainer } );
				}

			}

			if ( this.options['button_push_with_animation'] == 'on' ) {
				jQuery( this.trigger ).css( { 'transform' : translate } );
			}

		}

		/**
		 * Clear push translate on button and page wrapper.
		 */
		clearWrapperTranslate() {

			if ( this.options['animation_type'] == 'push' ) {
				jQuery(this.pageWrapper).css( { 'transform' : '' } );
			}

			if ( this.options['button_push_with_animation'] == 'on' ) {
				jQuery( this.trigger ).css( { 'transform' : '' } );
			}
		}

		/**
		 * Function to fadeIn the hamburger menu container.
		 */
		fadeMenuIn() {
			jQuery(this.container).fadeIn(this.animationSpeed);
		}

		/**
		 * Function to fadeOut the hamburger menu container.
		 */
		fadeMenuOut() {
			jQuery(this.container)
				.fadeOut(this.animationSpeed, function() {
					jQuery(this).css('display', '');
				});
		}

		/**
		 * Function is use to open the hamburger menu.
		 *
		 * @since 4.0.0
		 */
		openMenu() {
			jQuery(this.trigger).addClass(RmpMenu.activeToggleClass);
			jQuery(this.container).addClass(RmpMenu.openContainerClass);

			//this.pushMenuTrigger();

			if ( this.options['animation_type'] == 'fade'){
				this.fadeMenuIn();
			} else {
				this.setWrapperTranslate();
			}

			this.isOpen = true;

			jQuery( this.trigger ).attr( 'aria-expanded', 'true' );
			this.syncHiddenState();
			this.focusPanel();
		}

		/**
		 * Function is use to close the hamburger menu.
		 *
		 * @since 4.0.0
		 */
		closeMenu() {
			// Hand focus back before hiding: leaving the focused element inside an
			// aria-hidden/inert subtree is itself an accessibility failure.
			this.restoreFocus();

			jQuery(this.trigger).removeClass(RmpMenu.activeToggleClass);
			jQuery(this.container).removeClass(RmpMenu.openContainerClass);

			if ( this.options['animation_type'] == 'fade') {
				this.fadeMenuOut();
			} else {
				this.clearWrapperTranslate();
			}

			this.isOpen = false;

			jQuery( this.trigger ).attr( 'aria-expanded', 'false' );
			this.syncHiddenState();
		}

		/**
		 * Function is responsible for checking the menu is open or close.
		 *
		 * @since 4.0.0
		 * @param {Event} e
		 */
		triggerMenu() {
			this.isOpen ? this.closeMenu() : this.openMenu();
		}

		/**
		 * Put one submenu toggle into a given state: glyph, active class and - the part
		 * assistive technology actually reads - aria-expanded.
		 *
		 * @param {Object}  arrow    Toggle button (element or jQuery object).
		 * @param {boolean} expanded Whether the submenu it controls is now open.
		 */
		setArrowState( arrow, expanded ) {
			const $arrow = jQuery( arrow );

			if ( ! $arrow.length ) {
				return;
			}

			$arrow.html( expanded ? this.options['active_toggle_contents'] : this.options['inactive_toggle_contents'] );
			$arrow.toggleClass( RmpMenu.activeSubMenuArrowClass, !! expanded );
			$arrow.attr( 'aria-expanded', expanded ? 'true' : 'false' );
		}

		triggerSubArrow( subArrow ) {
			var self = this;

			// The toggle is a sibling of the item link inside the <li>, so the submenu it
			// controls is a sibling of the toggle itself.
			var sub_menu = jQuery( subArrow ).siblings( RmpMenu.subMenuClass );

			//Accordion animation.
			if ( self.options['accordion_animation'] == 'on' ) {
				// Get Top Most Parent and the siblings.
				var top_siblings   = sub_menu.parents('.rmp-menu-item-has-children').last().siblings('.rmp-menu-item-has-children');
				var first_siblings = sub_menu.parents('.rmp-menu-item-has-children').first().siblings('.rmp-menu-item-has-children');

				// Close up just the top level parents to key the rest as it was.
				top_siblings.children('.rmp-submenu').slideUp(self.subMenuTransitionTime, 'linear').removeClass('rmp-submenu-open');

				// Set each parent arrow to inactive.
				top_siblings.each(function() {
					self.setArrowState( jQuery(this).find(self.subMenuArrow).first(), false );
				});

				// Now Repeat for the current item siblings.
				first_siblings.children('.rmp-submenu').slideUp(self.subMenuTransitionTime, 'linear').removeClass('rmp-submenu-open');
				first_siblings.each(function() {
					self.setArrowState( jQuery(this).find(self.subMenuArrow).first(), false );
				});
			}

			// Active sub menu as default behavior.
			if( sub_menu.hasClass('rmp-submenu-open') ) {
				sub_menu.slideUp(self.subMenuTransitionTime, 'linear',function() {
					jQuery(this).css( 'display', '' );
				} ).removeClass('rmp-submenu-open');
				self.setArrowState( subArrow, false );
			} else {
				sub_menu.slideDown(self.subMenuTransitionTime, 'linear').addClass( 'rmp-submenu-open' );
				self.setArrowState( subArrow, true );
			}

		}

		/**
		 * Function to add tranform style on trigger.
		 *
		 * @version 4.0.0
		 *
		 * @param {Event} e Event object.
		 */
		pushMenuTrigger( e ) {
			if ( 'on' == this.options['button_push_with_animation'] ) {
				jQuery( this.trigger ).css( { 'transform' : this.menuWidth() } );
			}
		}

		/**
		 * Returns the height of container.
		 *
		 * @version 4.0.0
		 *
		 * @return Number
		 */
		menuHeight() {
			return jQuery( this.container ).height();
		}

		/**
		 * Returns the width of the container.
		 *
		 * @version 4.0.0
		 *
		 * @return Number
		 */
		menuWidth() {
			return jQuery( this.container ).width();
		}

		wrapperHeight() {
			return jQuery( this.wrapper ).height();
		}

		backUpSlide( backButton ) {
			let translateTo = parseInt( jQuery( this.menuWrap )[0].style.transform.replace( /^\D+/g, '' ) ) - 100;
			jQuery( this.menuWrap ).css( { 'transform': 'translateX(-' + translateTo + '%)' } );
			let previousSubmenuHeight = jQuery( backButton ).parent( 'ul' ).parent( 'li' ).parent( '.rmp-submenu' ).height();
			if ( ! previousSubmenuHeight ) {
				jQuery( this.menuWrap ).css( { 'height': this.originalHeight } );
			} else {
				jQuery( this.menuWrap + this.menuId ).css( { 'height': previousSubmenuHeight + 'px' } );
			}
		}
	}

	/**
	 * Create multiple instance of menu and pass the options.
	 *
	 * @version 4.0.0
	 */
	for ( let index = 0; index < rmp_menu.menu.length; index++ ) {
		let rmp = new RmpMenu( rmp_menu.menu[index] );
	}

} );
