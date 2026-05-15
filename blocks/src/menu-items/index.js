import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import Edit from './edit';
import Save from './save';

const allowInMenuItems = (settings, name) => {
	const allowedBlocks = [
		'core/navigation-link',
		'core/navigation-submenu',
		'core/button',
		'core/home-link',
		'core/social-links',
		'core/loginout',
	];

	if (allowedBlocks.includes(name)) {
		let newParent = ['rmp/menu-items'];
		if (settings.parent) {
			newParent = settings.parent.includes('rmp/menu-items')
				? settings.parent
				: [...settings.parent, 'rmp/menu-items'];
		}
		return {
			...settings,
			parent: newParent,
		};
	}

	return settings;
};

addFilter(
	'blocks.registerBlockType',
	'rmp/allow-in-menu-items',
	allowInMenuItems
);

registerBlockType('rmp/menu-items', {
	apiVersion: 3,
	title: __('Menu items', 'responeive-menu'),
	description: __('Menu items', 'responeive-menu'),
	icon: 'editor-ul',
	parent: ['rmp/menu'],
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
	attributes: {
		id: {
			type: 'string',
		},
		menuStyle: {
			type: 'object',
			default: {
				itemHeight: 40,
				lineHeight: 40,
				padding: {
					top: '5px',
					right: '5px',
					bottom: '5px',
					left: '5px',
				},
				fontSize: '15px',
				fontWieght: 300,
				fontFamily: '',
				textAlign: 'left',
				letterSpacing: '',
				letterCase: '',
				wordWrap: '',
				color: '#ffffff',
				hoverColor: '#ffffff',
				activeColor: '#ffffff',
				activeHoverColor: '#ffffff',
				background: '',
				backgroundHover: '',
				backgroundActive: '#6fda44',
				backgroundActiveHover: '',
				border: {},
				borderHover: {},
				borderActive: {},
				borderActiveHover: {},
			},
		},
		submenuStyle: {
			type: 'object',
			default: {
				lineHeight: 40,
				padding: {
					top: '5px',
					right: '5px',
					bottom: '5px',
					left: '5px',
				},
				fontSize: '15px',
				fontWieght: 300,
				fontFamily: '',
				textAlign: 'left',
				letterSpacing: '',
				color: '#ffffff',
				hoverColor: '#ffffff',
				activeColor: '#ffffff',
				activeHoverColor: '#ffffff',
				backgroundColor: '#6fda44',
				backgroundHoverColor: '#6fda44',
				backgroundActiveColor: '#6fda44',
				backgroundActiveHoverColor: '#6fda44',
				border: {},
				borderHover: {},
				borderActive: {},
				borderActiveHover: {},
			},
		},
		submenuBehaviour: {
			type: 'object',
			default: {
				useAccordion: '',
				autoExpandAllSubmenu: '',
				autoExpandCurrentSubmenu: '',
				expandSubItemOnParentClick: '',
			},
		},
		submenuIndentation: {
			type: 'object',
			default: {
				side: 'left',
				childLevel1: 5,
				childLevel2: 5,
				childLevel3: 5,
				childLevel4: 5,
			},
		},
		triggerIcon: {
			type: 'object',
			default: {
				type: 'text',
				textShape: '▼',
				activeTextShape: '▲',
				width: 45,
				height: 45,
				color: '#ffffff',
				hoverColor: '#ffffff',
				activeColor: '#ffffff',
				activeHoverColor: '#ffffff',
				backgroundColor: '',
				backgroundHoverColor: '',
				backgroundActiveColor: '',
				backgroundActiveHoverColor: '',
				border: {},
				borderHover: {},
				borderActive: {},
				borderActiveHover: {},
			},
		},
		blockStyles: {
			type: 'object',
		},
		desktopMenuStyle: {
			type: 'object',
			default: {
				color: '',
				hoverColor: '',
				activeColor: '',
				background: '',
				backgroundHover: '',
				backgroundActive: '',
				submenuColor: '',
				submenuHoverColor: '',
				submenuBackground: '',
				submenuBackgroundHover: '',
				dropdownAlign: 'left',
			},
		},
	},
	edit: Edit,
	save: Save,
});
