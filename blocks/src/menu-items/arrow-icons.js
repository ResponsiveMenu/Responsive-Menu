/**
 * The sub-menu arrow, shared by `edit` and `save`.
 *
 * When the arrow is an icon from the library, the SVG cannot be reconstructed
 * on the frontend from an attribute — so the block prints it once, hidden, and
 * the runtime clones that node into every arrow it builds. That also keeps the
 * runtime free of `innerHTML`, which matters because the text-shape variant is
 * arbitrary editor-supplied content.
 */

import { renderLibraryIcon } from '../components/TriggerContent';

/**
 * The inactive / active values for the configured arrow type.
 *
 * @param {Object} triggerIcon Trigger-icon attributes.
 * @return {Object} `{ inactive, active }`.
 */
export function resolveTriggerIcons(triggerIcon = {}) {
	switch (triggerIcon.type) {
		case 'icon':
			return {
				inactive: triggerIcon.icon || '',
				active: triggerIcon.activeIcon || '',
			};
		case 'image':
			return {
				inactive: triggerIcon.image || '',
				active: triggerIcon.activeImage || '',
			};
		default:
			return {
				inactive: triggerIcon.textShape || '',
				active: triggerIcon.activeTextShape || '',
			};
	}
}

/**
 * The hidden SVG template, printed only for the icon arrow type.
 *
 * @param {Object} props
 * @param {Object} props.triggerIcon Trigger-icon attributes.
 * @param {string} props.inactive    Inactive icon name.
 * @param {string} props.active      Active icon name.
 * @return {Element|null} Template, or null for non-icon arrows.
 */
export function ArrowIconTemplate({ triggerIcon, inactive, active }) {
	if ('icon' !== triggerIcon?.type) {
		return null;
	}

	return (
		<div
			className="rmp-submenu-trigger-icon"
			style={{ display: 'none' }}
			aria-hidden="true"
		>
			<span className="rmp-inactive-submenu-trigger-icon">
				{renderLibraryIcon(inactive)}
			</span>
			<span className="rmp-active-submenu-trigger-icon">
				{renderLibraryIcon(active)}
			</span>
		</div>
	);
}
