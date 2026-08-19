import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';

import { MENU_ITEMS_ATTRIBUTES } from './attributes';
import { MENU_ITEM_BLOCKS } from './constants';
import Edit from './edit';
import Save from './save';
import deprecated from './deprecated';

const allowInMenuItems = (settings, name) => {
	if (!MENU_ITEM_BLOCKS.includes(name)) {
		return settings;
	}

	const parent = Array.isArray(settings.parent) ? settings.parent : [];

	return {
		...settings,
		parent: parent.includes('rmp/menu-items')
			? parent
			: [...parent, 'rmp/menu-items'],
	};
};

addFilter(
	'blocks.registerBlockType',
	'rmp/allow-in-menu-items',
	allowInMenuItems
);

registerBlockType('rmp/menu-items', {
	apiVersion: 3,
	title: __('Menu items', 'responsive-menu'),
	description: __(
		'The list of links inside a Responsive Menu.',
		'responsive-menu'
	),
	icon: 'editor-ul',
	parent: ['rmp/menu'],
	usesContext: [
		'rmp/breakpoint',
		'rmp/tabletBreakpoint',
		'rmp/mobileBreakpoint',
	],
	supports: {
		html: false,
		customClassName: true,
		reusable: false,
		// Kept from the first version: removing a support drops the attributes
		// it owns, which would strip these styles from every existing menu.
		background: {
			backgroundImage: true,
			backgroundSize: true,
		},
		spacing: {
			padding: true,
		},
	},
	attributes: MENU_ITEMS_ATTRIBUTES,
	edit: Edit,
	save: Save,
	deprecated,
});
