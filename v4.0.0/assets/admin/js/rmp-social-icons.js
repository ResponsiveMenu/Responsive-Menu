/**
 * Social icons repeater behavior.
 *
 * @since 4.6.0
 */

jQuery(document).ready(function ($) {
	const $repeater = $('#rmp-social-icons-repeater');
	if (!$repeater.length) {
		return;
	}

	const templateHtml = $('#rmp-social-icon-template').html() || '';
	let nextIndex = parseInt($repeater.data('nextIndex'), 10);
	if (isNaN(nextIndex)) {
		nextIndex = 0;
	}

	function addUpdateNotification() {
		if (!$('#rmp-editor-main').find('#rmp-menu-update-notification').length) {
			$('#rmp-editor-main').prepend(
				'<div id="rmp-menu-update-notification" class="rmp-order-item rmp-order-item-description">' +
					'<span> <span class="rmp-font-icon dashicons dashicons-warning "></span> Update Required </span>' +
					'<a href="javascript:void(0)" id="rmp-menu-quick-update-button">UPDATE</a>' +
				'</div>'
			);
		}
	}

	function initColorPicker($scope) {
		$scope.find('.rmp-color-input').each(function () {
			const $input = $(this);
			if ($input.hasClass('wp-color-picker')) {
				return;
			}
			$input.wpColorPicker();
			$input.removeAttr('style');
			$input.off('focus');
		});
	}

	function bindIconPicker($scope) {
		$scope.find('.rmp-icon-picker')
			.off('click.rmpSocialIcons')
			.on('click.rmpSocialIcons', function (e) {
				e.stopPropagation();
				$('.rmp-menu-icons-dialog').show();
				$('#rmp-icon-dialog-select').attr('data-click', $(this).attr('id'));
			});

		$scope.find('.rmp-icon-picker')
			.off('click.rmpSocialIconsTrash')
			.on('click.rmpSocialIconsTrash', '.rmp-icon-picker-trash', function (e) {
				e.preventDefault();
				e.stopPropagation();

				const $picker = $(this).closest('.rmp-icon-picker');
				$picker.siblings('input.rmp-icon-hidden-input').val('');
				$picker.find('.rmp-font-icon').remove();
				$picker.removeAttr('data-icon');
				$(this).remove();
				addUpdateNotification();
				schedulePreviewRefresh();
			});
	}

	function clearRow($row) {
		$row.find('input[type="text"]').val('');
		const $color = $row.find('.rmp-color-input');
		if ($color.length && $color.hasClass('wp-color-picker')) {
			$color.wpColorPicker('color', '');
		} else {
			$color.val('');
		}

		const $picker = $row.find('.rmp-icon-picker');
		$picker.siblings('input.rmp-icon-hidden-input').val('');
		$picker.find('.rmp-font-icon').remove();
		$picker.removeAttr('data-icon');
		$picker.find('.rmp-icon-picker-trash').remove();
	}

	function initRow($row) {
		initColorPicker($row);
		bindIconPicker($row);
	}

	let previewRefreshTimer = null;
	function schedulePreviewRefresh() {
		if (previewRefreshTimer) {
			clearTimeout(previewRefreshTimer);
		}
		previewRefreshTimer = setTimeout(function () {
			refreshPreview();
		}, 250);
	}

	function refreshPreview() {
		if (typeof window.RMP_Preview === 'undefined') {
			return;
		}

		const $toggle = $('#rmp-item-order-social-icons');
		if ($toggle.length && !$toggle.is(':checked')) {
			return;
		}

		const menuId = $('#menu_id').val();
		if (!menuId) {
			return;
		}

		jQuery.ajax({
			url: rmpObject.ajaxURL,
			data: {
				'action': 'rmp_enable_menu_item',
				'ajax_nonce': rmpObject.ajax_nonce,
				'menu_id': menuId,
				'menu_element': 'social-icons'
			},
			type: 'POST',
			dataType: 'json',
			success: function (response) {
				if (!response || !response.data || !response.data.markup) {
					return;
				}
				const iframe = jQuery(window.RMP_Preview.iframe);
				const $contents = iframe.contents();
				const selector = '#rmp-menu-social-icons-' + menuId;
				if ($contents.find(selector).length) {
					$contents.find(selector).replaceWith(response.data.markup);
				} else {
					$contents.find('#rmp-container-' + menuId).append(response.data.markup);
				}
				if (typeof window.RMP_Preview.orderMenuElements === 'function') {
					window.RMP_Preview.orderMenuElements();
				}
			}
		});
	}

	// Init existing rows (color picker only).
	$repeater.find('.rmp-social-icon-item').each(function () {
		initColorPicker($(this));
	});

	// Add new row.
	$(document).on('click', '#rmp-social-icons-add', function (e) {
		e.preventDefault();
		if (!templateHtml) {
			return;
		}
		const rowHtml = templateHtml.replace(/__INDEX__/g, nextIndex);
		nextIndex += 1;
		$repeater.data('nextIndex', nextIndex);
		const $row = $(rowHtml);
		$repeater.find('.rmp-social-icons-items').append($row);
		initRow($row);
		addUpdateNotification();
		schedulePreviewRefresh();
	});

	// Remove row.
	$(document).on('click', '.rmp-social-icon-remove', function (e) {
		e.preventDefault();
		const $row = $(this).closest('.rmp-social-icon-item');
		const $items = $repeater.find('.rmp-social-icon-item');
		if ($items.length <= 1) {
			clearRow($row);
		} else {
			$row.remove();
		}
		addUpdateNotification();
		schedulePreviewRefresh();
	});

	// Live preview updates for icon fields.
	$(document).on('click', '#rmp-icon-dialog-select', function () {
		const clickerId = $(this).attr('data-click');
		if (clickerId && clickerId.indexOf('rmp-social-icon-picker-') === 0) {
			schedulePreviewRefresh();
		}
	});

	$(document).on('change', '.rmp-social-icons-repeater .rmp-color-input', function () {
		schedulePreviewRefresh();
	});

	$(document).on('keyup change paste', '.rmp-social-icons-repeater input[type="text"]', function () {
		schedulePreviewRefresh();
	});

	// Extend preview behavior for social icons.
	if (typeof window.RMP_Preview !== 'undefined') {
		window.RMP_Preview.menuSocialIcons = '#rmp-menu-social-icons-' + window.RMP_Preview.menuId;
		window.RMP_Preview.toggleElements('#rmp-item-order-social-icons', window.RMP_Preview.menuSocialIcons);

		window.RMP_Preview.orderMenuElements = function () {
			var list = [];
			var self = this;
			var iframeContents = jQuery(self.iframe).contents();
			jQuery('#tab-container .item-title').each(function () {
				var val = jQuery(this).text().toLocaleLowerCase().trim();

				if (val === 'title') {
					list.push(iframeContents.find(self.menuTitle));
					iframeContents.find(self.menuTitle).remove();
				} else if (val === 'search') {
					list.push(iframeContents.find(self.menuSearch));
					iframeContents.find(self.menuSearch).remove();
				} else if (val === 'menu') {
					list.push(iframeContents.find(self.menuWrap));
					iframeContents.find(self.menuWrap).remove();
				} else if (val === 'social icons') {
					list.push(iframeContents.find(self.menuSocialIcons));
					iframeContents.find(self.menuSocialIcons).remove();
				} else {
					list.push(iframeContents.find(self.menuContents));
					iframeContents.find(self.menuContents).remove();
				}
			});

			list.forEach(function (menuElement) {
				iframeContents.find(self.menuContainer).append(menuElement);
			});
		};
	}
});
