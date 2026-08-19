import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';

import { MENU_ITEMS_ATTRIBUTES } from './attributes';
import { MENU_ITEM_BLOCKS } from './constants';
import Edit from './edit';
import Save from './save';
import deprecated from './deprecated';

/**
 * Let the core navigation blocks be inserted into our list.
 *
 * `parent` is a RESTRICTION, not a permission: a block that declares one can be
 * inserted ONLY inside those parents. So this may only ever extend a list that
 * already exists — giving `parent` to a block that had none (core/social-links,
 * core/loginout, core/page-list) would remove it from the inserter everywhere
 * else on the site the moment the plugin is activated.
 *
 * Unrestricted blocks need nothing here: the container's own `allowedBlocks`
 * is what admits them to the list.
 *
 * @param {Object} settings Block settings.
 * @param {string} name     Block name.
 * @return {Object} Filtered settings.
 */
const allowInMenuItems = (settings, name) => {
	if (!MENU_ITEM_BLOCKS.includes(name)) {
		return settings;
	}

	const parent = settings.parent;

	if (!Array.isArray(parent) || 0 === parent.length) {
		return settings;
	}

	if (parent.includes('rmp/menu-items')) {
		return settings;
	}

	return { ...settings, parent: [...parent, 'rmp/menu-items'] };
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
