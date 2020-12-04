/**
 * This file container the editor multi level features.
 *
 * @version 4.0.0
 *
 * @type {Object}
 */
const rmpEditor = {
	editorSidebar: '#rmp-editor-wrapper',
	sidebarDrawer: 'button.collapse-sidebar',
	mainForm: '#rmp-editor-form',
	editorContainer: '#rmp-editor-main',
	topParentNav: '#rmp-editor-nav',
	topParentTab: '#rmp-editor-pane',
	childTabs: '.rmp-accordions',
	tabItem: 'li.rmp-tab-item',
	titleLogo: '.rmp-editor-header-logo',
	closeButton: '.rmp-editor-header-close',
	titleText: '.rmp-editor-header-title',
	backButton: '.rmp-editor-header-back',
	tabId: null,
	level: 0,
	close: function () {
		jQuery( window ).bind( 'beforeunload', function() {
			return;
		} );
	},
	triggerBack: function() {

		this.level--;
		parentId =  jQuery( '#' +  this.tabId ).attr( 'aria-parent' );
		jQuery( '#' + parentId ).show();

		let title = jQuery( '#' + parentId ).attr( 'aria-label' );
		this.updateHeader( title );

		jQuery( '#' +  this.tabId ).hide();
		this.tabId = parentId;
	},
	updatePanel: function( current ) {
		this.tabId = current.attr( 'aria-owns' );        
		jQuery( '#' + this.tabId ).show();
		parentId = current.parent( 'ul' ).parent( 'div' ).attr( 'id' );
		jQuery( '#' +  this.tabId ).attr( 'aria-parent', parentId );
		jQuery( '#' + parentId ).hide();
	},
	updateHeader: function( title ) {

		if ( 0 == this.level ) {
			jQuery( this.titleLogo ).find( 'img' ).show();
			jQuery( this.closeButton ).show();
			jQuery( this.backButton ).hide();
		} else if ( 1 == this.level ) {
			jQuery( this.backButton ).css( 'display', 'flex' );
			jQuery( this.titleLogo ).find( 'img' ).hide();
			jQuery( this.closeButton ).hide();
		}

		jQuery( this.titleText ).text( title );
	},
	init: function() {
		var self = this;

		// Move on next panel when click on item.
		jQuery( self.editorContainer ).on( 'click', self.tabItem, function( e ) {
			e.stopPropagation();
			e.preventDefault();
			current = jQuery( this );
			self.level++;
			self.updateHeader( current.text() );
			self.updatePanel( current );
		} );

		// Back from inner panel when click on back button.
		jQuery( self.backButton ).on( 'click', function( e ) {
			e.stopPropagation();
			self.triggerBack();
		} );

		// Close the editor and back to menu admin.
		jQuery( this.closeButton ).on( 'click', function( e ) {
			e.stopPropagation();
			self.close();
		} );

		// Open/Close the editor setting sidebar.
		jQuery( self.sidebarDrawer ).on( 'click', function(e) {
			jQuery( self.editorSidebar ).toggleClass( 'expanded collapsed' );
		} );
	}
};

rmpEditor.init();


