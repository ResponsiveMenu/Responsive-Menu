jQuery(document).ready(function(jQuery) {

	var  RMP_Mega_Menu = {
		megaMenuContainer : '.rmp-mega-menu-dialog',
		megaMenuWrap : '.rmp-mega-menu-dialog .rmp-dialog-wrap',
		closeSelector : '.rmp-mega-menu-dialog .rmp-dialog-header span.close',
		addNewRowSelector : '.rmp-mega-menu-add-row',
		addNewColumnSelector : '.rmp-mega-menu-add-column',
		rowStructure : '.rmp-mega-menu-dialog .rmp-mega-menu-row-structure',
		closeStructureSelector : '.rmp-mega-menu-row-structure-cancel',
		megaRowContainer : '#rmp-mega-menu-grid',
		megaColumnWrap : '.rmp-mega-menu-col',
		megaRowWrap : '.rmp-mega-menu-row',
		columnEditSelector : '.rmp-action-col-edit',
		columnDeleteSelector : '.rmp-action-col-delete',
		columnExpandSelector : '.rmp-mega-menu-col-expand',
		columnSizeText : '.rmp-mega-menu-col-size',
		columnCollapseSelector : '.rmp-mega-menu-col-collapse',
		rowEditSelector : '.rmp-action-row-edit',
		rowDeleteSelector : '.rmp-action-row-delete',
		megaMenuItem : '#rmp-mega-menu-grid .widget',
		columnShowWidgets: '.rmp-action-col-add-widget',
		clear : function () {
			var self = this;
			jQuery(self.megaMenuContainer).remove();
		},
		closePanel : function() {
			var self = this;
			jQuery('body').on('click',self.closeSelector, function() {
				jQuery(self.megaMenuContainer).remove();
			});
		},
		closeStructure : function() {
			var self = this;
			jQuery(self.rowStructure).on('click', self.closeStructureSelector, function() {
				jQuery(self.rowStructure).hide();
				jQuery(self.addNewRowSelector).show();

			});
		},
		showSaveButton : function() {
			jQuery('#rmp-mega-menu-sync').show();
		},
		reloadIframe : function() {
			jQuery('#rmp-preview-iframe').hide();
			jQuery('#rmp-preview-iframe-loader').show();
			document.getElementById('rmp-preview-iframe').contentWindow.location.reload();
		},
		deleteWidget : function( widget, event ) {
			event.preventDefault();

			var widget_id = widget.attr( 'widget-id' );
			var data = {
				action    : 'rmp_delete_widget',
				widget_id : widget_id,
				ajax_nonce  : rmpObject.ajax_nonce,
			};

			jQuery.ajax({
				url  : rmpObject.ajaxURL,
				data : data,
				type : 'POST',
				dataType: 'json',
				error: function( error ) {
					console.log(error.statusText);
				},
				success: function( response ) {
					widget.remove();
					RMP_Mega_Menu.updateWidgetCount();
				}
			});

		},
		closeWidget : function( widget, e ) {
			e.preventDefault();
			widget.find('.widget-inner').hide();
			widget.removeClass('open');
		},
		saveWidget: function(e) {
			e.preventDefault();
			var form = jQuery(this).parents('form');

			jQuery(e.target).siblings( '.spinner' ).addClass( 'is-active' );

			jQuery.post(rmpObject.ajaxURL, form.serialize(), function(response) {
				jQuery(e.target).siblings( '.spinner' ).removeClass( 'is-active' );
			});
		},
		editWidget: function() {
			var widget       = jQuery(this).parents('.widget');
			var widget_id    = widget.attr( 'widget-id' );
			var widget_inner = widget.find( '.widget-inner' );
			widget.toggleClass('open');
			if ( ! widget.hasClass('loaded') ) {
				widget.addClass('loaded');
				jQuery.ajax({
					url: rmpObject.ajaxURL,
					data: {
						'action'   : 'rmp_edit_widget',
						'ajax_nonce' : rmpObject.ajax_nonce,
						'widget_id'  : widget_id,
					},
					type: 'POST',
					dataType: 'json',
					error: function( error ) {
						console.log(error.statusText);
					},
					success: function( response ) {
						
						var $response = jQuery(response.data);
						widget_inner.html( $response );

						$response.find('.widget-delete').click( function(e) {
							RMP_Mega_Menu.deleteWidget( widget,e );
						});

						$response.find('.widget-close').click( function(e) {
							RMP_Mega_Menu.closeWidget( widget, e );
						});

						// Init Black Studio TinyMCE
						if (widget.is('[id*=black-studio-tinymce]')) {
							bstw(widget).deactivate().activate();
						}

						setTimeout(function(){
							// Fix WordPress widgets open and reopen.
							if (wp.textWidgets !== undefined) {
								wp.textWidgets.widgetControls = {};
							}

							if (wp.mediaWidgets !== undefined) {
								wp.mediaWidgets.widgetControls = {};
							}

							if (wp.customHtmlWidgets !== undefined) {
								wp.customHtmlWidgets.widgetControls = {};
							}
							
							jQuery(document).trigger("widget-added", [widget]);

							if ('acf' in window) {
								acf.getFields(document);
							}

						}, 100);

					}
				});
			}
		},
		addWidget: function(e) {
			var widget_base_id = jQuery(this).attr('base-id');
			var widget_title   = jQuery(this).text();
			var menu_item_id   =  jQuery(this).parents('.rmp-mega-menu-dialog').attr('data-item-id');
			jQuery.ajax({
				url: rmpObject.ajaxURL,
				data: {
					'action'   : 'rmp_add_widget',
					'ajax_nonce' : rmpObject.ajax_nonce,
					'widget_base_id'  : widget_base_id,
					'widget_title'    : widget_title,
					'menu_item_id'    : menu_item_id 
				},
				type: 'POST',
				dataType: 'json',
				error: function( error ) {
					console.log(error.statusText);
				},
				success: function( response ) {
					var active_col =  jQuery('.rmp-mega-menu-col[widget-open=1]');
					active_col.find('.rmp-mega-menu-widgets').append( response.data );
					active_col.removeAttr('widget-open');
					RMP_Mega_Menu.updateWidgetCount();
					jQuery('#rmp-widget-container').removeClass('active');

					var $response = jQuery(response.data);
					$response.find('.rmp-widget-edit').click( function(e) {
						RMP_Mega_Menu.editWidget();
					});

				}
			});
		},
		showWidgets : function(e) {

			var clone = jQuery('#rmp-widget-container').css({
				'position': 'fixed',
				'left' : ( e.pageX - 150 ),
				'top' :  ( e.pageY + 25 ),
			}).toggleClass('active');
			jQuery('.rmp-mega-menu-col').removeAttr('widget-open');
			jQuery(e.target).closest('.rmp-mega-menu-col').attr('widget-open',1);
		},
		makePanelDraggable : function() {
			var self = this;
			jQuery( self.megaMenuWrap ).draggable({
				scroll: true,
				scrollSensitivity: 100,
				cursor: "move",
				delay: 100,
				cancel: '.rmp-mega-menu-row, .rmp-popup-controls',
			});
		},
		makeRowsSortable: function() {
			jQuery(".rmp-mega-menu-grid")
			.sortable({
				placeholder: "sortable-placeholder",
				opacity: 0.9,
				cursor: "move",
				delay: 150,
				forcePlaceholderSize: true,
				cancel: '.rmp-mega-menu-col',
			});
		},
		makeWidgetSortable : function() {
			var self = this;
			jQuery( ".rmp-mega-menu-widgets" ).sortable( {
				connectWith: "div.rmp-mega-menu-widgets",
				tolerance: "pointer",
				forcePlaceholderSize: true,
				placeholder: "sortable-placeholder",
				cancel : '.widget-inner',
				stop: function( event, ui ) {
				   self.updateWidgetCount();
				}
			} );
		},
		updateWidgetCount : function() {
			jQuery(this.megaColumnWrap).each(function() {
				var total = jQuery(this).find('.rmp-mega-menu-widgets > .widget' ).length
				jQuery(this).attr('data-total-items', total );
			});
		},
		addRow : function() {
			var self = this;
			jQuery(self.addNewRowSelector).click( function(e) {
				jQuery(self.rowStructure).show();
				jQuery(this).hide();
			});
			self.addRowColStructure();
			self.makeRowsSortable();
			self.closeStructure();
			self.makeWidgetSortable();
		},
		deleteRow : function() {
			var self = this;
			jQuery(self.megaMenuContainer).on('click', self.rowDeleteSelector, function(e) {
				jQuery(this).parents( self.megaRowWrap).remove();
			});
		},
		editRow : function() {
			var self = this;
			jQuery(self.megaMenuContainer).on('click', self.rowEditSelector, function(e) {
			   console.log( 'Coming soon');
			});
		},
		addCol : function() {
			var self = this;
			this.deleteCol();
			this.expandCol();
			this.collapseCol();

			jQuery(self.megaMenuContainer).on('click',self.addNewColumnSelector, function() {
				var row    = jQuery(this).parents(self.megaRowWrap);
				rowUsedCols      = parseInt( row.attr('data-row-used-cols') );
				rowAvailableCols = parseInt( row.attr('data-row-available-cols') );
				if( rowAvailableCols < 1 ) {
					alert('There is not enough space on this row to add a new column.');
					return;
				}

				columnSize  = ( rowAvailableCols >= 4 ) ? 4 : rowAvailableCols;
				item_id     =  jQuery(this).parents('.rmp-mega-menu-dialog').attr('data-item-id');

				jQuery.ajax({
					url: rmpObject.ajaxURL,
					data: {
						'action'       : 'rmp_add_mega_menu_column',
						'ajax_nonce'     : rmpObject.ajax_nonce,
						'column_size'  : columnSize,
						'item_id'      : item_id,
					},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						jQuery(this).prop('disabled' , true);
					},
					error: function( error ) {
						console.log('Internal Error !');
						jQuery(this).prop('disabled', false);
					},
					success: function( response ) {
						row.append(response.html);
						row.attr('data-row-used-cols', ( rowUsedCols + columnSize) );
						row.attr('data-row-available-cols', ( rowAvailableCols - columnSize));
						self.makeWidgetSortable();

						jQuery(response.html).find('.rmp-color-input').wpColorPicker();
					}
				});

			});
		},
		deleteCol: function() {
			var self= this;
			jQuery(self.megaMenuContainer).on('click', self.columnDeleteSelector, function(e) {
				var row    = jQuery(this).parents(self.megaRowWrap);
				var column = jQuery(this).parents(self.megaColumnWrap);
				rowUsedCols      = parseInt( row.attr('data-row-used-cols') );
				rowSize          = parseInt( row.attr('data-row-size') );
				rowAvailableCols = parseInt( row.attr('data-row-available-cols') );
				colSize          = parseInt( column.attr('data-col-size') );
				row.attr('data-row-used-cols', ( rowUsedCols - colSize) );
				row.attr('data-row-available-cols', (rowAvailableCols + colSize) );
				column.remove();
			});
		},
		expandCol() {
			var self= this;
			jQuery(self.megaMenuContainer).on('click', self.columnExpandSelector, function(e) {
				var row    = jQuery(this).parents(self.megaRowWrap);
				var column = jQuery(this).parents(self.megaColumnWrap);
				rowUsedCols      = parseInt( row.attr('data-row-used-cols') );
				rowSize          = parseInt( row.attr('data-row-size') );
				rowAvailableCols = parseInt( row.attr('data-row-available-cols') );
				colSize          = parseInt( column.attr('data-col-size') );
				if ( rowAvailableCols > 0 ) {
					colSize = colSize+1;
					column.attr('data-col-size', colSize );
					row.attr('data-row-available-cols', ( rowAvailableCols - 1) );
					row.attr('data-row-used-cols', (rowUsedCols + 1) );
					column.find(self.columnSizeText).text(  colSize + '/' + rowSize );
				} else {
					alert( 'col size exceeds');
				}
			});
		},
		collapseCol() {
			var self= this;
			jQuery(self.megaMenuContainer).on('click', self.columnCollapseSelector, function(e) {
				var row    = jQuery(this).parents(self.megaRowWrap);
				var column = jQuery(this).parents(self.megaColumnWrap);
				rowUsedCols      = parseInt( row.attr('data-row-used-cols') );
				rowSize          = parseInt( row.attr('data-row-size') );
				rowAvailableCols = parseInt( row.attr('data-row-available-cols') );
				colSize          = parseInt( column.attr('data-col-size') );
				if ( colSize > 1  ) {
					colSize = colSize-1;
					column.attr('data-col-size', colSize );
					row.attr('data-row-available-cols', ( rowAvailableCols + 1 ) );
					row.attr('data-row-used-cols', (rowUsedCols-1) );
					column.find(self.columnSizeText).text(  colSize + '/' + rowSize );
				} else {
					alert( 'col size exceeds');
				}
			});
		},
		addRowColStructure : function() {
			var self = this;
			jQuery(self.rowStructure).on('click', '.col-structure', function(e) {

				var column_sizes =  jQuery(this).attr('col-size'),
					item_id      =  jQuery(this).parents('.rmp-mega-menu-dialog').attr('data-item-id');

				jQuery.ajax({
					url: rmpObject.ajaxURL,
					data: {
						'action'       : 'rmp_add_mega_menu_row',
						'ajax_nonce'     : rmpObject.ajax_nonce,
						'column_sizes' : column_sizes,
						'item_id'      : item_id,
					},
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						jQuery(this).prop('disabled' , true);
					},
					error: function( error ) {
						console.log('Internal Error !');
						jQuery(this).prop('disabled', false);
					},
					success: function( response ) {
						jQuery(self.megaRowContainer).append(response.data.html);
						self.makeWidgetSortable();
						self.deleteRow();
						jQuery(self.rowStructure).hide();
						jQuery(self.addNewRowSelector).show();
					}
				});

			});
		},
		openPanel : function(menuItem) {
			var self = this;
			jQuery('body').on( 'click', menuItem, function(e) {
				e.stopPropagation();
				self.clear();

				jQuery(this).addClass('rmp-icon-rotate');
				jQuery('.rmp-mega-menu-top-item').removeClass('current-active-item');
				jQuery(this).parents('.rmp-mega-menu-top-item').addClass('current-active-item');

				var menu_item_id = jQuery(this).parents('.rmp-mega-menu-top-item').attr('aria-item-id');

				jQuery.ajax({
					url: rmpObject.ajaxURL,
					data: {
						'action'       : 'rmp_open_mega_menu_item',
						'ajax_nonce'     : rmpObject.ajax_nonce,
						'menu_id'      : jQuery('#menu_id').val(),
						'menu_item_id' : menu_item_id,
					},
					type: 'POST',
					dataType: 'json',
					error: function( error ) {
						console.log('Internal Error !');
						jQuery(this).prop('disabled', false);
					},
					success: function( response ) {
						jQuery('body').append(response.html);    
						self.addRow();
						self.addCol();
						self.deleteRow();
						jQuery('.rmp-color-input').wpColorPicker();
						jQuery(e.target).removeClass('rmp-icon-rotate');
					}
				});

			});
		},
		init: function( menuItem ) {  
			var self = this; 
			self.openPanel(menuItem);
			self.optionPopupWindow();
			self.clear();
			self.closePanel();

			/**
			 * Show the list of widget.
			 */
			jQuery(document).on( 'click', self.columnShowWidgets, self.showWidgets );

			/**
			 * Add new widget item.
			 */
			jQuery(document).on( 'click', '#rmp-widget-container .widget-item', self.addWidget );

			/**
			 * Edit the widget.
			 */
			jQuery(document).on( 'click', '.rmp-widget-edit', self.editWidget );

			/**
			 * Save the widget.
			 */
			jQuery(document).on( 'click', '.rmp-save-widget', self.saveWidget );

			/**
			 * Sync mega menu data.
			 */
			jQuery(document).on('click','#rmp-mega-menu-sync', function(){
				self.saveMegaMenuOptions();

			});

		},
		optionPopupWindow : function() {
			// Popup control group
			var self= this;
			jQuery(document).on('mousedown', '.popup-controls-toggle', function(e) {
				e.stopPropagation();

				if ( ! jQuery(this).hasClass('is-active') ) {
					jQuery('.popup-controls-toggle').removeClass('is-active');
					jQuery('.rmp-popup-controls').hide();
				}

				jQuery(this).toggleClass('is-active');
				jQuery(this).siblings('.rmp-popup-controls').css({
					'position': 'fixed',
					'left' : ( e.pageX - 150 ),
					'top' :  ( e.pageY + 25 ),
				}).toggle();

			});
	
			jQuery(document).on('mousedown', function(e) {
				jQuery('.popup-controls-toggle').removeClass('is-active');
				jQuery('.rmp-popup-controls').removeClass('active');
				jQuery('.rmp-popup-controls').hide();
			});
	
			jQuery(document).on('mousedown', '.rmp-popup-controls', function(e){
				e.stopPropagation();
			});
		},
		saveMegaMenuOptions : function() {
			var self = this;

			var panel                  = jQuery(self.megaMenuContainer);
			var panel_width            = panel.find('#rmp-desktop-mega-menu-panel-width').val();
			var panel_width_unit       = panel.find('#rmp-desktop-mega-menu-panel-width-unit').val();
			var panel_padding_left     = panel.find('#rmp-mega-menu-panel-padding-left').val();
			var panel_padding_top      = panel.find('#rmp-mega-menu-panel-padding-top').val();
			var panel_padding_right    = panel.find('#rmp-mega-menu-panel-padding-right').val();
			var panel_padding_bottom   = panel.find('#rmp-mega-menu-panel-padding-bottom').val();
			var panel_background_color = panel.find('#rmp-mega-menu-panel-background').val();
			var panel_background_image = panel.find('#rmp-mega-menu-panel-background-image').val();

			var panel   = {
				'meta' : {
					'panel_width'      : panel_width,
					'panel_width_unit' : panel_width_unit,
					'panel_padding'    : {
						'left'   : panel_padding_left,
						'top'    : panel_padding_top,
						'right'  : panel_padding_right,
						'bottom' : panel_padding_bottom,
					},
					'panel_background' : {
						'color' : panel_background_color,
						'image' : panel_background_image,
					},
				},
				'rows' : []
			};


			jQuery( self.megaRowWrap ).each( function() {
				var row_index = jQuery(this).index();
				var row_size = jQuery(this).attr('data-row-size');
				panel['rows'][ row_index ] = {
					'meta' : {
						'row_size' : row_size
					},
					'columns' : []
				}
			});

			jQuery( self.megaColumnWrap ).each( function() {

				var column = jQuery(this);
				var row_index = column.closest(self.megaRowWrap).index();
				var col_index = column.closest(self.megaRowWrap).children(self.megaColumnWrap).index(column);
				var col_size  = column.attr('data-col-size');

				var col_padding_left       = column.find('#rmp-mega-menu-column-padding-left').val();
				var col_padding_top        = column.find('#rmp-mega-menu-column-padding-top').val();
				var col_padding_right      = column.find('#rmp-mega-menu-column-padding-right').val();
				var col_padding_bottom     = column.find('#rmp-mega-menu-column-padding-bottom').val();
				var col_background_color   = column.find('#rmp-mega-menu-column-background').val();
				var col_background_hover   = column.find('#rmp-mega-menu-column-background-hover').val();

				panel['rows'][row_index]['columns'][col_index] = {
					'meta' : {
						'column_size' : col_size,
						'column_padding' : {
							'left'   : col_padding_left,
							'top'    : col_padding_top,
							'right'  : col_padding_right,
							'bottom' : col_padding_bottom,
						},
						'column_background' : {
							'color' : col_background_color,
							'hover' : col_background_hover,
						},
					},
					'menu_items' : []
				}
			});

			jQuery( self.megaMenuItem ).each( function() {
				var item       = jQuery(this)
				var item_index = item.index();
				var item_id    = item.attr("widget-id");
				var item_type  = item.attr("widget-type");
				var item_title  = item.attr("widget-title");
				var row_index  = item.closest(self.megaRowWrap).index();
				var col        = item.closest(self.megaColumnWrap);
				var col_index  = col.parent( self.megaRowWrap).children(self.megaColumnWrap).index(col);

				panel['rows'][row_index]['columns'][col_index]['menu_items'][item_index] = {
					'item_id'    : item_id,
					'item_type'  : item_type,
					'item_title' : item_title
				};

			});

			var item_id = jQuery(self.megaMenuContainer).attr('data-item-id');
			var menu_id = jQuery('#menu_id').val();

			var data = {
				'action'       : 'rmp_save_mega_menu_item',
				'ajax_nonce'     : rmpObject.ajax_nonce,
				'menu_id'      : menu_id,
				'item_id'      : item_id,
				'item_meta'    : panel,
			};

			jQuery.ajax( {
				url: rmpObject.ajaxURL,
				data: data,
				type: 'POST',
				dataType: 'json',
				error: function( error ) {
					console.log('Internal Error !');
				},
				success: function( response ) {
					self.reloadIframe();
				}
			});
		}
	};

	RMP_Mega_Menu.init( '.rmp-mega-menu-edit-icon' );

});

