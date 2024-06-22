import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import Edit from './edit';
import Save from './save';

registerBlockType('rmp/menu-items', {
	title: __('Menu items', 'responeive-menu'),
	description: __('Menu items', 'responeive-menu'),
	icon: 'editor-ul',
	parent: ['rmp/menu'],
	supports: {
		__experimentalSettings: true,
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
			type: "string"
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
				childLevel1: 10,
				childLevel2: 15,
				childLevel3: 20,
				childLevel4: 25,
			},
		},
		triggerIcon: {
			type: 'object',
			default: {
				type: 'text',
				textShape: '▼',
				activeTextShape: '▲',
				width: 40,
				height: 40,
				position: 'left',
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
			type: 'object'
		}
	},
	edit: Edit,
	save: Save,
});
