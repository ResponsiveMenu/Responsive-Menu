import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import DynamicStyles from '../styles';
import { flattenIconsArray } from '../utils/icon-functions';
import parseIcon from '../utils/parse-icon';
import getIcons from '../icons';

export default function Save({ attributes }) {
	const { id, triggerIcon, submenuBehaviour } = attributes;
	const blockProps = useBlockProps.save({
		className: `rmp-block-menu-items-${id} is-responsive wp-block-navigation wp-block-rmp-menu-items`,
	});
	const dynamicStyles = DynamicStyles(attributes);
	const iconsAll = flattenIconsArray(getIcons());
	const iconsObj = iconsAll.reduce((acc, value) => {
		acc[value?.name] = value?.icon;
		return acc;
	}, {});

	const renderSVG = (svg, size) => {
		let renderedIcon = iconsObj?.[svg];
		if (typeof renderedIcon === 'string') {
			renderedIcon = parseIcon(renderedIcon);
		}

		return <Icon icon={renderedIcon} size={size} />;
	};
	let triggerIconValue = '';
	let triggerActiveIconValue = '';
	if (triggerIcon?.type === 'text') {
		triggerIconValue = triggerIcon.textShape;
		triggerActiveIconValue = triggerIcon.activeTextShape;
	}
	if (triggerIcon?.type === 'icon') {
		triggerIconValue = triggerIcon.icon;
		triggerActiveIconValue = triggerIcon.activeIcon;
	}
	if (triggerIcon?.type === 'image') {
		triggerIconValue = triggerIcon.image;
		triggerActiveIconValue = triggerIcon.activeImage;
	}
	return (
		<>
			<style>
				{`
				.rmp-block-menu-items-${id} {
					${Object.entries(dynamicStyles)
						.map(([k, v]) => `${k}:${v}`)
						.join(';')}
				}
			`}
			</style>
			{triggerIcon && triggerIcon.type === 'icon' && (
				<div
					className="rmp-submenu-trigger-icon"
					style={{ display: 'none' }}
				>
					<span className="rmp-inactive-submenu-trigger-icon">
						{renderSVG(triggerIconValue)}
					</span>
					<span className="rmp-active-submenu-trigger-icon">
						{renderSVG(triggerActiveIconValue)}
					</span>
				</div>
			)}
			<ul
				{...blockProps}
				data-submenu-icon={triggerIconValue}
				data-submenu-active-icon={triggerActiveIconValue}
				data-submenu-icon-type={triggerIcon?.type}
				data-use-accordion={submenuBehaviour.useAccordion ? true : false}
				data-auto-expand={submenuBehaviour.autoExpandAllSubmenu ? true : false}
				data-auto-expand-current={submenuBehaviour.autoExpandCurrentSubmenu ? true : false}
				data-auto-expand-parent={submenuBehaviour.expandSubItemOnParentClick ? true : false}
			>
				<InnerBlocks.Content />
			</ul>
		</>
	);
}
