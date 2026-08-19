/**
 * Deprecations for `rmp/menu-items`.
 *
 * v1 had no arrow-position or indentation-side class on the list, no
 * `data-item-click-opens` / `data-desktop-submenu-trigger`, and a different
 * class order. Keeping its `save` here means menus built on v1 load without
 * the "this block contains unexpected or invalid content" warning.
 */

import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

import { renderLibraryIcon } from '../components/TriggerContent';
import { MENU_ITEMS_ATTRIBUTES } from './attributes';

const v1 = {
	attributes: MENU_ITEMS_ATTRIBUTES,
	supports: {
		html: false,
		background: {
			backgroundImage: true,
			backgroundSize: true,
		},
		customClassName: true,
		spacing: {
			padding: true,
		},
	},
	save({ attributes }) {
		const { id, triggerIcon, submenuBehaviour } = attributes;

		const blockProps = useBlockProps.save({
			className: `rmp-block-menu-items-${id} is-responsive wp-block-navigation wp-block-rmp-menu-items`,
		});

		let triggerIconValue = '';
		let triggerActiveIconValue = '';

		if ('text' === triggerIcon?.type) {
			triggerIconValue = triggerIcon.textShape;
			triggerActiveIconValue = triggerIcon.activeTextShape;
		}
		if ('icon' === triggerIcon?.type) {
			triggerIconValue = triggerIcon.icon;
			triggerActiveIconValue = triggerIcon.activeIcon;
		}
		if ('image' === triggerIcon?.type) {
			triggerIconValue = triggerIcon.image;
			triggerActiveIconValue = triggerIcon.activeImage;
		}

		return (
			<>
				{triggerIcon && 'icon' === triggerIcon.type && (
					<div
						className="rmp-submenu-trigger-icon"
						style={{ display: 'none' }}
					>
						<span className="rmp-inactive-submenu-trigger-icon">
							{renderLibraryIcon(triggerIconValue)}
						</span>
						<span className="rmp-active-submenu-trigger-icon">
							{renderLibraryIcon(triggerActiveIconValue)}
						</span>
					</div>
				)}
				<ul
					{...blockProps}
					data-submenu-icon={triggerIconValue}
					data-submenu-active-icon={triggerActiveIconValue}
					data-submenu-icon-type={triggerIcon?.type}
					data-use-accordion={
						submenuBehaviour.useAccordion ? true : false
					}
					data-auto-expand={
						submenuBehaviour.autoExpandAllSubmenu ? true : false
					}
					data-auto-expand-current={
						submenuBehaviour.autoExpandCurrentSubmenu ? true : false
					}
					data-auto-expand-parent={
						submenuBehaviour.expandSubItemOnParentClick
							? true
							: false
					}
				>
					<InnerBlocks.Content />
				</ul>
			</>
		);
	},
};

export default [v1];
