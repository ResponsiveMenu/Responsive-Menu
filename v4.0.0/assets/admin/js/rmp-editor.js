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
	parentTabItem: '.rmp-editor-pane-parent li.rmp-tab-item',
	tabItem: 'li.rmp-tab-item',
	quickItem: '.rmp-quick-edit-link',
	tabItemTitle: '.rmp-tab-item-title',
	titleLogo: '.rmp-editor-header-logo',
	closeButton: '.rmp-editor-header-close',
	searchButton: '.rmp-search-settings-btn',
	searchForm: '.rmp-search-settings',
	titleText: '.rmp-editor-header-title',
	backButton: '.rmp-editor-header-back',
	accordionItem: 'li.rmp-accordion-item',
	tabId: null,
	level: 0,
	triggerBack: function() {

		this.level--;
		let parentId =  jQuery( '#' +  this.tabId ).attr( 'aria-parent' );
		jQuery( '#' + parentId ).show();

		let title = jQuery( '#' + parentId ).attr( 'aria-label' );
		this.updateHeader( title );

		jQuery( '#' +  this.tabId ).hide();
		this.tabId = parentId;
	},
	updatePanel: function( current ) {
		this.tabId = current.attr( 'aria-owns' );
		jQuery( '#' + this.tabId ).show();
		let parentId = current.parent( 'ul' ).parent( 'div' ).attr( 'id' );
		jQuery( '#' +  this.tabId ).attr( 'aria-parent', parentId );
		jQuery( '#' + parentId ).hide();
	},
	updateQuickPanel: function( current ) {
		this.tabId = current.attr( 'aria-owns' );
		var accordionId = current.attr( 'accordion-id' );
		var subAccordionId = current.attr( 'sub-accordion-id' );
		var subTabId = current.attr( 'sub-tab-id' );
		let parentId = jQuery(".rmp-accordions:visible").attr('id');
		jQuery( '#' +  this.tabId ).attr( 'aria-parent', parentId );
		jQuery( '#' + parentId ).hide();
		jQuery( '#' + this.tabId ).show();
		if(accordionId!=''){
			if (!jQuery( '#' + accordionId ).hasClass("ui-state-active")) {
				jQuery( '#' + accordionId ).click();
			}
			if (subAccordionId !='') {
				if (!jQuery( '#' + subAccordionId).hasClass("ui-state-active")) {
					jQuery( '#' + subAccordionId ).click();
				}
				accordionId = subAccordionId;
			}
			setTimeout( function() {
					var topPos = document.getElementById(accordionId).offsetTop;
					jQuery( '#rmp-editor-main' ).animate({scrollTop: topPos - 60+'px'}, 500);
				}, 400);

		}
		if(subTabId!=''){
			jQuery( '#' + subTabId ).click();
		}
	},
	updateHeader: function( title ) {

		if ( 0 == this.level ) {
			jQuery( this.titleLogo ).find( 'img' ).show();
			jQuery( this.closeButton ).show();
			jQuery( this.backButton ).hide();
			jQuery(	this.searchForm	).css('width','200');
		} else if ( 1 == this.level ) {
			jQuery( this.backButton ).css( 'display', 'flex' );
			jQuery( this.titleLogo ).find( 'img' ).hide();
			jQuery( this.closeButton ).hide();
			jQuery(	this.searchForm	).css('width','255');
		}

		jQuery( this.titleText ).text( title );
	},
	init: function() {
		var self = this;

		// Move on next panel when click on item.
		jQuery( self.editorContainer ).on( 'click', self.tabItem, function( e ) {
			e.stopPropagation();
			e.preventDefault();
			let current = jQuery( this );
			self.level++;
			self.updateHeader( current.text() );
			self.updatePanel( current );
		} );

		// Move on next panel when click on item.
		jQuery( self.editorContainer ).on( 'click', self.quickItem, function( e ) {
			e.stopPropagation();
			e.preventDefault();
			let current = jQuery( this );
			var tabId = current.attr( 'aria-owns' );
			var title = jQuery('.rmp-tab-item[aria-owns="'+tabId+'"]').find('.rmp-tab-item-title').html();
			self.level++;
			self.updateHeader( title );
			self.updateQuickPanel( current );
		} );

		// Back from inner panel when click on back button.
		jQuery( self.backButton ).on( 'click', function( e ) {
			e.stopPropagation();
			self.triggerBack();
		} );

		// Open/Close the editor setting sidebar.
		jQuery( self.sidebarDrawer ).on( 'click', function(e) {
			jQuery( self.editorSidebar ).toggleClass( 'expanded collapsed' );
		} );

		// Open/Close the search form.
		jQuery(self.searchButton).on( 'click', function( e ) {
			jQuery(self.searchForm).toggle();
		} );

		//Search settings
		jQuery.expr[':'].containsIgnoreCase = function (n, i, m) {
            return jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
		};
		jQuery.fn.highlight = function(pat) {
			function innerHighlight(node, pat) {
				var skip = 0;
				if(jQuery(node).is("select,input,textarea, .rmp-tooltip-content ")){
					return skip;
				}
				if (node.nodeType == 3) {
					var pos = node.data.toUpperCase().indexOf(pat);
					if (pos >= 0) {
						var spannode = document.createElement('i');
						spannode.className = 'rmp-highlight';
						var middlebit = node.splitText(pos);
						middlebit.splitText(pat.length);
						var middleclone = middlebit.cloneNode(true);
						spannode.appendChild(middleclone);
						middlebit.parentNode.replaceChild(spannode, middlebit);
						skip = 1;
					}
				} else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
					for (var i = 0; i < node.childNodes.length; ++i) {
						i += innerHighlight(node.childNodes[i], pat);
					}
				}
				return skip;
			}
			return this.each(function() {
				innerHighlight(this, pat.toUpperCase());
			});
		};

		jQuery.fn.removeHighlight = function() {
			function newNormalize(node) {
				for (var i = 0, children = node.childNodes, nodeCount = children.length; i < nodeCount; i++) {
					var child = children[i];
					if (child.nodeType == 1) {
						newNormalize(child);
						continue;
					}
					if (child.nodeType != 3) {
						continue;
					}
					var next = child.nextSibling;
					if (next == null || next.nodeType != 3) {
						continue;
					}
					var combined_text = child.nodeValue + next.nodeValue;
					var new_node = node.ownerDocument.createTextNode(combined_text);
					node.insertBefore(new_node, child);
					node.removeChild(child);
					node.removeChild(next);
					i--;
					nodeCount--;
				}
			}

			return this.find("i.rmp-highlight").each(function() {
				var thisParent = this.parentNode;
				thisParent.replaceChild(this.firstChild, this);
				newNormalize(thisParent);
			}).end();
		};

		jQuery(document).on('keyup change search', self.searchForm, function(){
			var searchTerm = jQuery(this).val();
			jQuery('#rmp-editor-main').removeHighlight();
			jQuery('.rmp-search-results-found').remove();
			if(searchTerm == '') return false;
			jQuery('#rmp-editor-main').highlight( searchTerm );
			jQuery(self.parentTabItem).each(function() {
				var target = "#"+jQuery( this ).attr( "aria-owns" );
				var count = jQuery(target).find("i.rmp-highlight:containsIgnoreCase("+searchTerm+")").length;
				jQuery(target).find(self.tabItem).each(function() {
					var childTarget = "#"+jQuery( this ).attr( "aria-owns" );
					var childCount = jQuery(childTarget).find("i.rmp-highlight:containsIgnoreCase("+searchTerm+")").length;
					if(childCount>0){
						jQuery(this).append('<span class="rmp-search-results-found">'+childCount+' Results</span>');
						count = Number(count)+Number(childCount);
					}
				});
				if(count>0){
					jQuery(target).find(self.accordionItem).each(function() {
						var accordionItemCount = jQuery(this).find(":not(.accordion-item-title, .item-title) > i.rmp-highlight:containsIgnoreCase("+searchTerm+")").length;
						if(accordionItemCount>0){
							jQuery(this).find('.rmp-accordion-title:first > .accordion-item-title, .rmp-accordion-title:first > .item-title').append('<span class="rmp-search-results-found">'+accordionItemCount+' Results</span>');
						}
					});
					jQuery(this).append('<span class="rmp-search-results-found">'+count+' Results</span>');
				}
			});
		});

	}
};

rmpEditor.init();