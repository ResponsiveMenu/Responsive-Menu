import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import parseIcon from './utils/parse-icon';
import { flattenIconsArray } from './utils/icon-functions';
import getIcons from './icons';
export default function save(props) {
	const { attributes } = props;
	const { id, hamburgerText, hamburgerStyle } = attributes;
	const blockProps = useBlockProps.save({
		className: `rmp-block-navigator rmp-block-navigator-${id}`,
	});
	const iconsAll = flattenIconsArray(getIcons());
	const iconsObj = iconsAll.reduce((acc, value) => {
		acc[value?.name] = value?.icon;
		return acc;
	}, {});
	const renderSVG = (svg, size) => {
		let renderedIcon = iconsObj?.[svg];
		// Icons provided by third-parties are generally strings.
		if (typeof renderedIcon === 'string') {
			renderedIcon = parseIcon(renderedIcon);
		}

		return <Icon icon={renderedIcon} size={size} />;
	};
	return (
		<nav {...blockProps}>
			<button
					type="button"
					aria-controls={`rmp-block-container-${id}`}
					aria-label={__('Menu Trigger', 'responsive-menu')}
					id={`rmp-block-menu-trigger-${id}`}
					className={`rmp-block-menu-trigger rmp-menu-trigger-boring rmp-mobile-device-menu rmp-block-menu-trigger-position-${hamburgerStyle?.side} rmp-block-text-position-${hamburgerText?.position}`}
				>
					<span className="rmp-block-trigger-box">
						{hamburgerStyle && hamburgerStyle.type === 'icon' && (
							<>
								{hamburgerStyle.icon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
										{renderSVG(hamburgerStyle.icon, hamburgerStyle?.iconSize)}
									</span>
								)}
								{hamburgerStyle.activeIcon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
										{renderSVG(hamburgerStyle.activeIcon, hamburgerStyle?.iconSize)}
									</span>
								)}
							</>
						)}
						{hamburgerStyle && hamburgerStyle.type === 'image' && (
							<>
								{hamburgerStyle.icon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
										<img
											src={hamburgerStyle?.image}
											alt={__('Hamburger Image', 'responsive-menu')}
										/>
									</span>
								)}
								{hamburgerStyle.activeIcon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
										<img
											src={hamburgerStyle?.activeImage}
											alt={__('Hamburger Active Image', 'responsive-menu')}
										/>
									</span>
								)}
							</>
						)}
						{hamburgerStyle && hamburgerStyle.type === 'hamburger' && (
							<span className="rmp-block-trigger-inner"></span>
						)}
					</span>
					<span className="rmp-block-trigger-label">
						{hamburgerText && hamburgerText.text && (
							<span className="rmp-block-trigger-label-inactive">
								{hamburgerText?.text}
							</span>
						)}
						{hamburgerText && hamburgerText.activeText && (
							<span className="rmp-block-trigger-label-active">
								{hamburgerText?.activeText}
							</span>
						)}
					</span>
				</button>
			<div
				className={`rmp-block-container rmp-block-container-${id}`}
				id={`rmp-block-container-${id}`}
			>
				<InnerBlocks.Content />
			</div>
		</nav>
	);
}
