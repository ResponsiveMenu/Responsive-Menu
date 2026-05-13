/**
 * Social icons repeater behavior.
 *
 * @since 4.6.0
 */


function rmpClearRow($row) {
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

jQuery(document).ready(function ($) {
	const $repeater = $('#rmp-social-icons-repeater');
	if (!$repeater.length) {
		return;
	}

	const templateHtml = $('#rmp-social-icon-template').html() || '';
	let nextIndex = Number.parseInt($repeater.data('nextIndex'), 10);
	if (Number.isNaN(nextIndex)) {
		nextIndex = 0;
	}

	function rmpAddUpdateNotification() {
		if (!$('#rmp-editor-main').find('#rmp-menu-update-notification').length) {
			$('#rmp-editor-main').prepend(
				'<div id="rmp-menu-update-notification" class="rmp-order-item rmp-order-item-description">' +
					'<span> <span class="rmp-font-icon dashicons dashicons-warning "></span> Update Required </span>' +
					'<a href="javascript:void(0)" id="rmp-menu-quick-update-button">UPDATE</a>' +
				'</div>'
			);
		}
	}

	function rmpInitColorPicker($scope) {
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

	function rmpBindIconPicker($scope) {
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
				rmpAddUpdateNotification();
				rmpSchedulePreviewRefresh();
			});
	}

	function rmpHasIcons() {
		let hasIcons = false;
		$repeater.find('.rmp-social-icon-item').each(function () {
			const value = $(this).find('input.rmp-icon-hidden-input').val();
			if (value && String(value).trim() !== '') {
				hasIcons = true;
				return false;
			}
		});
		return hasIcons;
	}

	function rmpInitRow($row) {
		rmpInitColorPicker($row);
		rmpBindIconPicker($row);
	}

	let previewRefreshTimer = null;
	function rmpSchedulePreviewRefresh() {
		if (previewRefreshTimer) {
			clearTimeout(previewRefreshTimer);
		}
		previewRefreshTimer = setTimeout(function () {
			rmpRefreshPreview();
		}, 250);
	}

	function rmpRefreshPreview() {
		if (globalThis.RMP_Preview === undefined) {
			return;
		}

		const menuId = $('#menu_id').val();
		if (!menuId) {
			return;
		}

		const iframe = jQuery(globalThis.RMP_Preview.iframe);
		const $contents = iframe.contents();
		const selector = '#rmp-menu-social-icons-' + menuId;
		const hasIcons = rmpHasIcons();

		if (!hasIcons) {
			if ($contents.find(selector).length) {
				$contents.find(selector).remove();
				globalThis.RMP_Preview.orderMenuElements?.();
			}
			return;
		}

		const $toggle = $('#rmp-item-order-social-icons');
		if ($toggle.length && !$toggle.is(':checked')) {
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
				if (!response?.data?.markup) {
					return;
				}
				if ($contents.find(selector).length) {
					$contents.find(selector).replaceWith(response.data.markup);
				} else {
					$contents.find('#rmp-container-' + menuId).append(response.data.markup);
				}
				globalThis.RMP_Preview.orderMenuElements?.();
			}
		});
	}

	// Init existing rows (color picker only).
	$repeater.find('.rmp-social-icon-item').each(function () {
		rmpInitColorPicker($(this));
	});

	// Add new row.
	$(document).on('click', '#rmp-social-icons-add', function (e) {
		e.preventDefault();
		if (!templateHtml) {
			return;
		}
		const rowHtml = templateHtml.replaceAll(/__INDEX__/g, nextIndex);
		nextIndex += 1;
		$repeater.data('nextIndex', nextIndex);
		const $row = $(rowHtml);
		$repeater.find('.rmp-social-icons-items').append($row);
		rmpInitRow($row);
		rmpAddUpdateNotification();
		rmpSchedulePreviewRefresh();
	});

	// Remove row.
	$(document).on('click', '.rmp-social-icon-remove', function (e) {
		e.preventDefault();
		const $row = $(this).closest('.rmp-social-icon-item');
		const $items = $repeater.find('.rmp-social-icon-item');
		if ($items.length <= 1) {
			rmpClearRow($row);
		} else {
			$row.remove();
		}
		rmpAddUpdateNotification();
		rmpSchedulePreviewRefresh();
	});

	// Live preview updates for icon fields.
	$(document).on('click', '#rmp-icon-dialog-select', function () {
		const clickerId = $(this).attr('data-click');
		if (clickerId?.startsWith('rmp-social-icon-picker-')) {
			rmpSchedulePreviewRefresh();
		}
	});

	$(document).on('change', '.rmp-social-icons-repeater .rmp-color-input', function () {
		rmpSchedulePreviewRefresh();
	});

	$(document).on('keyup change paste', '.rmp-social-icons-repeater input[type="text"]', function () {
		rmpSchedulePreviewRefresh();
	});

	// Extend preview behavior for social icons.
	if (globalThis.RMP_Preview !== undefined) {
		const preview = globalThis.RMP_Preview;
		preview.menuSocialIcons = '#rmp-menu-social-icons-' + preview.menuId;
		preview.toggleElements('#rmp-item-order-social-icons', preview.menuSocialIcons);

		preview.orderMenuElements = function () {
			const previewRef = globalThis.RMP_Preview;
			const iframeContents = jQuery(previewRef.iframe).contents();
			const list = [];
			jQuery('#tab-container .item-title').each(function () {
				const val = jQuery(this).text().toLocaleLowerCase().trim();

				if (val === 'title') {
					list.push(iframeContents.find(previewRef.menuTitle));
					iframeContents.find(previewRef.menuTitle).remove();
				} else if (val === 'search') {
					list.push(iframeContents.find(previewRef.menuSearch));
					iframeContents.find(previewRef.menuSearch).remove();
				} else if (val === 'menu') {
					list.push(iframeContents.find(previewRef.menuWrap));
					iframeContents.find(previewRef.menuWrap).remove();
				} else if (val === 'social icons') {
					list.push(iframeContents.find(previewRef.menuSocialIcons));
					iframeContents.find(previewRef.menuSocialIcons).remove();
				} else {
					list.push(iframeContents.find(previewRef.menuContents));
					iframeContents.find(previewRef.menuContents).remove();
				}
			});

			list.forEach(function (menuElement) {
				iframeContents.find(previewRef.menuContainer).append(menuElement);
			});
		};
	}
});
