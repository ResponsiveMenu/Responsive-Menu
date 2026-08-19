/**
 * Shared constants for the menu list, kept out of `index.js` so the editor and
 * the deprecations can import them without an import cycle.
 */

/**
 * Blocks that make sense as menu entries. They are allowed as children of
 * `rmp/menu-items` by extending their own `parent` list, which is the only way
 * to let a core block live in a third-party container.
 */
export const MENU_ITEM_BLOCKS = [
	'core/navigation-link',
	'core/navigation-submenu',
	'core/button',
	'core/home-link',
	'core/social-links',
	'core/loginout',
	'core/page-list',
];
