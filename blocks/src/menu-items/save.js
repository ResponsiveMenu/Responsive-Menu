/**
 * Saved markup for the menu list.
 *
 * The list carries its behaviour as data attributes so the frontend runtime can
 * configure itself per menu with no inline script, and — when the sub-menu
 * arrow is an icon — a hidden template the runtime clones instead of injecting
 * markup from an attribute.
 */

import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

import { resolveTriggerIcons, ArrowIconTemplate } from './arrow-icons';

export default function Save({ attributes }) {
	const {
		id,
		triggerIcon = {},
		submenuBehaviour = {},
		submenuIndentation = {},
		desktopMenuStyle = {},
	} = attributes;

	const blockProps = useBlockProps.save({
		className: [
			'wp-block-navigation',
			'wp-block-rmp-menu-items',
			'is-responsive',
			`rmp-block-menu-items-${id}`,
			`rmp-submenu-arrow-${triggerIcon.position || 'right'}`,
			`rmp-submenu-indent-${submenuIndentation.side || 'left'}`,
			`rmp-desktop-submenu-${desktopMenuStyle.submenuAnimation || 'fade'}`,
		].join(' '),
	});

	const { inactive, active } = resolveTriggerIcons(triggerIcon);

	return (
		<>
			<ArrowIconTemplate
				triggerIcon={triggerIcon}
				inactive={inactive}
				active={active}
			/>
			<ul
				{...blockProps}
				data-submenu-icon={inactive}
				data-submenu-active-icon={active}
				data-submenu-icon-type={triggerIcon.type}
				data-use-accordion={
					submenuBehaviour.useAccordion ? 'true' : 'false'
				}
				data-auto-expand={
					submenuBehaviour.autoExpandAllSubmenu ? 'true' : 'false'
				}
				data-auto-expand-current={
					submenuBehaviour.autoExpandCurrentSubmenu ? 'true' : 'false'
				}
				data-auto-expand-parent={
					submenuBehaviour.expandSubItemOnParentClick
						? 'true'
						: 'false'
				}
				data-item-click-opens={
					submenuBehaviour.itemClickOpens ? 'true' : 'false'
				}
				data-desktop-submenu-trigger={
					submenuBehaviour.desktopTrigger || 'click'
				}
			>
				<InnerBlocks.Content />
			</ul>
		</>
	);
}
