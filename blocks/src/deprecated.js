/**
 * Deprecations for `rmp/menu`.
 *
 * v1 wrote the behaviour flags onto the trigger button and kept the editor's
 * preview state (`activeMenu`) in the saved attributes. v2 moved the flags onto
 * the `nav` — where the runtime can read them before it has found the trigger —
 * added the responsive layers, and dropped the editor-only attribute. Without
 * this deprecation every menu saved by v1 would be flagged as invalid content.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

import { renderLibraryIcon } from './components/TriggerContent';
import metadata from './block.json';

/**
 * v1's trigger markup, reproduced verbatim — including the image branch that
 * gated on `icon`/`activeIcon` instead of `image`/`activeImage`. A deprecation
 * has to match the bytes that were actually saved, bugs and all.
 *
 * @param {Object} props
 * @param {Object} props.hamburgerStyle Hamburger settings.
 * @param {Object} props.hamburgerText  Hamburger label settings.
 * @return {Element} v1 trigger contents.
 */
function V1TriggerContent({ hamburgerStyle, hamburgerText }) {
	return (
		<>
			<span className="rmp-block-trigger-box">
				{hamburgerStyle && hamburgerStyle.type === 'icon' && (
					<>
						{hamburgerStyle.icon && (
							<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
								{renderLibraryIcon(
									hamburgerStyle.icon,
									hamburgerStyle?.iconSize
								)}
							</span>
						)}
						{hamburgerStyle.activeIcon && (
							<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
								{renderLibraryIcon(
									hamburgerStyle.activeIcon,
									hamburgerStyle?.iconSize
								)}
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
									alt={__(
										'Hamburger Active Image',
										'responsive-menu'
									)}
								/>
							</span>
						)}
					</>
				)}
				{hamburgerStyle && hamburgerStyle.type === 'hamburger' && (
					<span className="rmp-block-trigger-inner"></span>
				)}
			</span>
			{hamburgerText &&
				(hamburgerText.text || hamburgerText.activeText) && (
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
				)}
		</>
	);
}

const v1Attributes = {
	...metadata.attributes,
	activeMenu: { type: 'string', default: '' },
};

const v1 = {
	attributes: v1Attributes,
	supports: {
		html: false,
		customClassName: true,
	},
	migrate(attributes) {
		const { activeMenu, ...rest } = attributes;

		return {
			...rest,
			menuBehaviour: {
				escKey: true,
				focusTrap: true,
				scrollLock: false,
				swipe: false,
				...(rest.menuBehaviour || {}),
			},
		};
	},
	save({ attributes }) {
		const {
			id,
			hamburgerText,
			hamburgerStyle,
			menuBehaviour = {},
			menuAnimation = {},
			breakpoint,
		} = attributes;

		const blockProps = useBlockProps.save({
			className: `rmp-block-navigator rmp-block-navigator-${id}`,
		});

		return (
			<nav {...blockProps} data-breakpoint={breakpoint}>
				<button
					type="button"
					aria-controls={`rmp-block-container-${id}`}
					aria-label="Menu Trigger"
					id={`rmp-block-menu-trigger-${id}`}
					data-hide-link-click={menuBehaviour.linkClick ? true : false}
					data-hide-page-click={menuBehaviour.pageClick ? true : false}
					data-hide-on-scroll={
						menuBehaviour.pageScroll ? true : false
					}
					className={`rmp-block-menu-trigger rmp-menu-trigger-boring rmp-mobile-device-menu rmp-block-menu-trigger-position-${hamburgerStyle?.side} rmp-block-text-position-${hamburgerText?.position}`}
				>
					<V1TriggerContent
						hamburgerStyle={hamburgerStyle}
						hamburgerText={hamburgerText}
					/>
				</button>
				<div
					className={`rmp-block-container rmp-block-container-${id} rmp-block-container-direction-${menuAnimation?.direction} rmp-block-container-animation-${menuAnimation?.type}`}
					id={`rmp-block-container-${id}`}
				>
					<InnerBlocks.Content />
				</div>
			</nav>
		);
	},
};

export default [v1];
