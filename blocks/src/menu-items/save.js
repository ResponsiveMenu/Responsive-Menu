import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import DynamicStyles from '../styles';

export default function Save({ attributes }) {
	const { id, triggerIcon } = attributes;
	const blockProps = useBlockProps.save({
		className: `rmp-block-menu-items-${id} is-responsive wp-block-navigation wp-block-rmp-menu-items`
	});
	const dynamicStyles = DynamicStyles(attributes);
	let triggerIconValue = "";
	let triggerActiveIconValue = "";
	if (triggerIcon?.type === 'text') {
		triggerIconValue = triggerIcon.textShape;
		triggerActiveIconValue = triggerIcon.activeTextShape;
	}
	if (triggerIcon?.type === 'icon') {
		triggerIconValue = <Icon icon={triggerIcon.icon} />;
		triggerActiveIconValue = <Icon icon={triggerIcon.activeIcon} />;
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

			<ul {...blockProps} data-submenu-icon={ triggerIconValue } data-submenu-active-icon={ triggerActiveIconValue } data-submenu-icon-type={ triggerIcon?.type }>
				<InnerBlocks.Content />
			</ul>
		</>
	);
}
