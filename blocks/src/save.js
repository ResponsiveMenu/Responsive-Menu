/**
 * Saved markup for the Responsive Menu block.
 *
 * Every behavioural setting is written as a data attribute so the frontend
 * runtime needs no inline configuration script, and every visual setting is a
 * CSS custom property emitted by PHP — which is what lets one saved markup
 * serve desktop, tablet and mobile.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

import TriggerContent from './components/TriggerContent';

export default function save({ attributes }) {
	const {
		id,
		hamburgerText,
		hamburgerStyle,
		menuBehaviour = {},
		menuAnimation = {},
		overlay = {},
		triggerPosition = {},
		breakpoint,
	} = attributes;

	const blockProps = useBlockProps.save({
		className: `rmp-block-navigator rmp-block-navigator-${id} rmp-block-trigger-position-${triggerPosition.type || 'relative'}`,
	});

	return (
		<nav
			{...blockProps}
			data-breakpoint={breakpoint}
			data-direction={menuAnimation.direction}
			data-close-on-link={menuBehaviour.linkClick ? 'true' : 'false'}
			data-close-on-body-click={
				menuBehaviour.pageClick ? 'true' : 'false'
			}
			data-close-on-scroll={menuBehaviour.pageScroll ? 'true' : 'false'}
			data-close-on-esc={false === menuBehaviour.escKey ? 'false' : 'true'}
			data-scroll-lock={menuBehaviour.scrollLock ? 'true' : 'false'}
			data-focus-trap={
				false === menuBehaviour.focusTrap ? 'false' : 'true'
			}
			data-swipe={menuBehaviour.swipe ? 'true' : 'false'}
			data-overlay={overlay.enabled ? 'true' : 'false'}
		>
			<button
				type="button"
				aria-controls={`rmp-block-container-${id}`}
				aria-expanded="false"
				aria-label={__('Menu', 'responsive-menu')}
				id={`rmp-block-menu-trigger-${id}`}
				className={`rmp-block-menu-trigger rmp-menu-trigger-boring rmp-mobile-device-menu rmp-block-menu-trigger-position-${hamburgerStyle?.side} rmp-block-text-position-${hamburgerText?.position}`}
			>
				<TriggerContent
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
}
