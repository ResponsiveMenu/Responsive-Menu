/**
 * Turns block attributes into the CSS custom properties the stylesheet reads.
 *
 * Everything the menu can be styled with is expressed as a `--rmp--*` custom
 * property, which is what makes per-device styling cheap: the same declarations
 * are re-emitted inside a media query with different values, and no selector is
 * duplicated.
 */

/**
 * Internal dependencies
 */
import { resolveAttributes } from './utils/responsive';

/**
 * Attribute groups that can carry per-device values.
 */
export const STYLE_GROUPS = [
	'menuContainerStyle',
	'menuAnimation',
	'hamburgerStyle',
	'hamburgerText',
	'triggerPosition',
	'overlay',
	'menuStyle',
	'submenuStyle',
	'submenuIndentation',
	'triggerIcon',
	'desktopMenuStyle',
];

const UNSET = undefined;

/**
 * A CSS value is usable only when it is a non-empty string that does not carry
 * a stray `undefined`/`NaN` from a missing attribute.
 *
 * @param {*} value Candidate value.
 * @return {boolean} True when the value can be written to CSS.
 */
function isUsable(value) {
	if (UNSET === value || null === value || false === value) {
		return false;
	}

	const stringValue = String(value).trim();

	if ('' === stringValue) {
		return false;
	}

	return !/undefined|NaN/.test(stringValue);
}

/**
 * Drop every property whose value cannot be written to CSS.
 *
 * @param {Object} props Candidate custom properties.
 * @return {Object} Usable custom properties.
 */
function clean(props) {
	return Object.entries(props).reduce((usable, [key, value]) => {
		if (isUsable(value)) {
			usable[key] = String(value).trim();
		}
		return usable;
	}, {});
}

/**
 * Append a unit to a bare number. Values that already carry a unit (or a CSS
 * keyword / calc()) are passed through untouched.
 *
 * @param {*}      value Raw value.
 * @param {string} unit  Unit to append, e.g. `px`.
 * @return {string|undefined} Value with a unit, or undefined when unusable.
 */
function withUnit(value, unit = 'px') {
	if (UNSET === value || null === value || '' === value) {
		return UNSET;
	}

	if ('number' === typeof value) {
		return Number.isFinite(value) ? `${value}${unit}` : UNSET;
	}

	const stringValue = String(value).trim();

	if ('' === stringValue) {
		return UNSET;
	}

	return /^-?\d*\.?\d+$/.test(stringValue)
		? `${stringValue}${unit}`
		: stringValue;
}

/**
 * Collapse a BoxControl value into a shorthand. Sides that were never set fall
 * back to `0` rather than poisoning the whole shorthand with `undefined`.
 *
 * @param {Object} box BoxControl value.
 * @return {string|undefined} Shorthand, or undefined when nothing is set.
 */
function boxShorthand(box) {
	if (!box || 'object' !== typeof box) {
		return UNSET;
	}

	const sides = ['top', 'right', 'bottom', 'left'].map((side) =>
		withUnit(box[side])
	);

	if (sides.every((side) => UNSET === side)) {
		return UNSET;
	}

	return sides.map((side) => side ?? '0').join(' ');
}

/**
 * FocalPointPicker stores `{ x, y }` as 0..1 fractions; CSS wants percentages.
 * Anything else (a keyword such as `center`) is passed through.
 *
 * @param {Object|string} position Stored background position.
 * @return {string|undefined} CSS background-position.
 */
function backgroundPosition(position) {
	if (!position) {
		return UNSET;
	}

	if ('object' === typeof position) {
		const { x, y } = position;

		if (UNSET === x && UNSET === y) {
			return UNSET;
		}

		return `${(Number(x) || 0) * 100}% ${(Number(y) || 0) * 100}%`;
	}

	return position;
}

/**
 * Normalise a BorderBoxControl / BorderControl value into a per-side map.
 *
 * @param {Object} border Stored border value.
 * @return {Object} `{ top, right, bottom, left }` of border definitions.
 */
function borderSides(border) {
	if (!border || 'object' !== typeof border) {
		return {};
	}

	// BorderControl (a single, linked border) rather than BorderBoxControl.
	if (border.width || border.style || border.color) {
		return {
			top: border,
			right: border,
			bottom: border,
			left: border,
		};
	}

	return border;
}

/**
 * Build the shorthand for one side of a border.
 *
 * @param {Object} border Stored border value.
 * @param {string} side   top | right | bottom | left.
 * @return {string|undefined} `width style color`, or undefined when unset.
 */
function borderSide(border, side) {
	const value = borderSides(border)[side];

	if (!value || (!value.width && !value.color)) {
		return UNSET;
	}

	const width = withUnit(value.width) ?? '1px';
	const style = value.style || 'solid';
	const color = value.color || 'currentColor';

	return `${width} ${style} ${color}`;
}

/**
 * Emit the four `border-*` custom properties for a state.
 *
 * @param {string} prefix Property prefix, e.g. `--rmp--menu-item`.
 * @param {string} state  Empty for the normal state, else `-hover` etc.
 * @param {Object} border Stored border value.
 * @return {Object} Custom properties.
 */
function borderProps(prefix, state, border) {
	return ['top', 'right', 'bottom', 'left'].reduce((props, side) => {
		props[`${prefix}${state}-border-${side}`] = borderSide(border, side);
		return props;
	}, {});
}

/**
 * Build every custom property for one already-resolved attribute set.
 *
 * @param {Object} resolved Resolved attribute groups.
 * @return {Object} CSS custom properties.
 */
export function buildStyleProps(resolved) {
	const {
		menuContainerStyle = {},
		menuAnimation = {},
		hamburgerStyle = {},
		hamburgerText = {},
		triggerPosition = {},
		overlay = {},
		menuStyle = {},
		submenuStyle = {},
		submenuIndentation = {},
		triggerIcon = {},
		desktopMenuStyle = {},
	} = resolved;

	const backgroundImage = menuContainerStyle.backgroundImage
		? `url(${menuContainerStyle.backgroundImage})`
		: UNSET;

	// The trigger box has to be tall enough for three lines plus their gaps.
	// Only derive it when the menu actually configures a lines-style trigger,
	// so a block that has no hamburger (the menu list) emits nothing for it.
	// Which edge a desktop dropdown hangs from. Left with both sides unset so
	// nothing is emitted for a block that has no desktop menu at all.
	const dropdownAlign = { left: UNSET, right: UNSET };

	if (desktopMenuStyle.dropdownAlign) {
		const alignRight = 'right' === desktopMenuStyle.dropdownAlign;
		dropdownAlign.left = alignRight ? 'auto' : '0';
		dropdownAlign.right = alignRight ? '0' : 'auto';
	}

	const hasLineMetrics =
		UNSET !== hamburgerStyle.lineSpacing || UNSET !== hamburgerStyle.lineHeight;
	const triggerBoxHeight = hasLineMetrics
		? `${2 * (Number(hamburgerStyle.lineSpacing) || 0) + 2 * (Number(hamburgerStyle.lineHeight) || 0)}px`
		: UNSET;

	return clean({
		// ── Menu container ──────────────────────────────────────────────
		'--rmp--menu-container-text-color': menuContainerStyle.color,
		'--rmp--menu-container-background-color': menuContainerStyle.background,
		'--rmp--menu-container-background-image': backgroundImage,
		'--rmp--menu-container-background-position': backgroundPosition(
			menuContainerStyle.backgroundPosition
		),
		'--rmp--menu-container-background-repeat':
			menuContainerStyle.backgroundRepeat,
		'--rmp--menu-container-background-size':
			menuContainerStyle.backgroundSize,
		'--rmp--menu-container-padding': boxShorthand(
			menuContainerStyle.padding
		),
		'--rmp--menu-container-width': withUnit(
			menuContainerStyle.menuWidth,
			'%'
		),
		'--rmp--menu-container-max-width': withUnit(
			menuContainerStyle.menuMaximumWidth
		),
		'--rmp--menu-container-min-width': withUnit(
			menuContainerStyle.menuMinimumWidth
		),
		'--rmp--menu-container-height': menuContainerStyle.autoHeight
			? 'auto'
			: UNSET,
		'--rmp--menu-container-columns': menuContainerStyle.columns,
		'--rmp--menu-container-animation-duration': Number.isFinite(
			Number(menuAnimation.transitionDuration)
		)
			? `${Number(menuAnimation.transitionDuration)}s`
			: UNSET,

		// ── Overlay ─────────────────────────────────────────────────────
		'--rmp--menu-overlay-background': overlay.enabled
			? overlay.color
			: UNSET,

		// ── Hamburger trigger ───────────────────────────────────────────
		'--rmp--menu-hamburger-line-spacing': withUnit(
			hamburgerStyle.lineSpacing
		),
		'--rmp--menu-hamburger-line-width': withUnit(hamburgerStyle.lineWidth),
		'--rmp--menu-hamburger-line-height': withUnit(
			hamburgerStyle.lineHeight
		),
		'--rmp--menu-hamburger-width': withUnit(hamburgerStyle.width),
		'--rmp--menu-hamburger-height': withUnit(hamburgerStyle.height),
		'--rmp--menu-hamburger-color': hamburgerStyle.color,
		'--rmp--menu-hamburger-hover-color': hamburgerStyle.hoverColor,
		'--rmp--menu-hamburger-active-color': hamburgerStyle.activeColor,
		'--rmp--menu-hamburger-background': hamburgerStyle.background,
		'--rmp--menu-hamburger-hover-background':
			hamburgerStyle.hoverBackground,
		'--rmp--menu-hamburger-active-background':
			hamburgerStyle.activeBackground,
		'--rmp--menu-hamburger-border-radius': boxShorthand(
			hamburgerStyle.borderRadius
		),
		'--rmp--menu-hamburger-icon-size': withUnit(hamburgerStyle.iconSize),
		'--rmp--menu-hamburger-trigger-box-height': triggerBoxHeight,

		// ── Trigger placement ───────────────────────────────────────────
		'--rmp--menu-hamburger-offset-side': withUnit(
			triggerPosition.distanceFromSide
		),
		'--rmp--menu-hamburger-offset-top': withUnit(triggerPosition.top),
		'--rmp--menu-hamburger-z-index': triggerPosition.zIndex,

		// ── Hamburger label ─────────────────────────────────────────────
		'--rmp--menu-hamburger-text-font': hamburgerText.fontFamily,
		'--rmp--menu-hamburger-text-size': withUnit(hamburgerText.size),
		'--rmp--menu-hamburger-text-line-height': withUnit(
			hamburgerText.lineHeight
		),
		'--rmp--menu-hamburger-text-color': hamburgerText.color,
		'--rmp--menu-hamburger-text-gap': withUnit(hamburgerText.gap),

		// ── Menu items ──────────────────────────────────────────────────
		'--rmp--menu-item-height': withUnit(menuStyle.itemHeight),
		'--rmp--menu-item-line-height': withUnit(menuStyle.lineHeight),
		'--rmp--menu-item-padding': boxShorthand(menuStyle.padding),
		'--rmp--menu-item-font-size': withUnit(menuStyle.fontSize),
		'--rmp--menu-item-font-wieght': menuStyle.fontWieght,
		'--rmp--menu-item-font-family': menuStyle.fontFamily,
		'--rmp--menu-item-text-align': menuStyle.textAlign,
		'--rmp--menu-item-letter-spacing': withUnit(menuStyle.letterSpacing),
		'--rmp--menu-item-letter-case': menuStyle.letterCase,
		'--rmp--menu-item-word-wrap': menuStyle.wordWrap,
		'--rmp--menu-item-color': menuStyle.color,
		'--rmp--menu-item-hover-color': menuStyle.hoverColor,
		'--rmp--menu-item-active-color': menuStyle.activeColor,
		'--rmp--menu-item-active-hover-color': menuStyle.activeHoverColor,
		'--rmp--menu-item-background': menuStyle.background,
		'--rmp--menu-item-hover-background': menuStyle.backgroundHover,
		'--rmp--menu-item-active-background': menuStyle.backgroundActive,
		'--rmp--menu-item-active-hover-background':
			menuStyle.backgroundActiveHover,
		...borderProps('--rmp--menu-item', '', menuStyle.border),
		...borderProps('--rmp--menu-item', '-hover', menuStyle.borderHover),
		...borderProps('--rmp--menu-item', '-active', menuStyle.borderActive),
		...borderProps(
			'--rmp--menu-item',
			'-active-hover',
			menuStyle.borderActiveHover
		),

		// ── Sub-menu items ──────────────────────────────────────────────
		'--rmp--menu-subitem-line-height': withUnit(submenuStyle.lineHeight),
		'--rmp--menu-subitem-padding': boxShorthand(submenuStyle.padding),
		'--rmp--menu-subitem-font-size': withUnit(submenuStyle.fontSize),
		'--rmp--menu-subitem-font-wieght': submenuStyle.fontWieght,
		'--rmp--menu-subitem-font-family': submenuStyle.fontFamily,
		'--rmp--menu-subitem-text-align': submenuStyle.textAlign,
		'--rmp--menu-subitem-letter-spacing': withUnit(
			submenuStyle.letterSpacing
		),
		'--rmp--menu-subitem-letter-case': submenuStyle.letterCase,
		'--rmp--menu-subitem-word-wrap': submenuStyle.wordWrap,
		'--rmp--menu-subitem-color': submenuStyle.color,
		'--rmp--menu-subitem-hover-color': submenuStyle.hoverColor,
		'--rmp--menu-subitem-active-color': submenuStyle.activeColor,
		'--rmp--menu-subitem-active-hover-color': submenuStyle.activeHoverColor,
		'--rmp--menu-subitem-background': submenuStyle.backgroundColor,
		'--rmp--menu-subitem-hover-background':
			submenuStyle.backgroundHoverColor,
		'--rmp--menu-subitem-active-background':
			submenuStyle.backgroundActiveColor,
		'--rmp--menu-subitem-active-hover-background':
			submenuStyle.backgroundActiveHoverColor,
		...borderProps('--rmp--menu-subitem', '', submenuStyle.border),
		...borderProps(
			'--rmp--menu-subitem',
			'-hover',
			submenuStyle.borderHover
		),
		...borderProps(
			'--rmp--menu-subitem',
			'-active',
			submenuStyle.borderActive
		),
		...borderProps(
			'--rmp--menu-subitem',
			'-active-hover',
			submenuStyle.borderActiveHover
		),

		// ── Sub-menu indentation ────────────────────────────────────────
		'--rmp--menu-subitem-indentation-child1': withUnit(
			submenuIndentation.childLevel1,
			'%'
		),
		'--rmp--menu-subitem-indentation-child2': withUnit(
			submenuIndentation.childLevel2,
			'%'
		),
		'--rmp--menu-subitem-indentation-child3': withUnit(
			submenuIndentation.childLevel3,
			'%'
		),
		'--rmp--menu-subitem-indentation-child4': withUnit(
			submenuIndentation.childLevel4,
			'%'
		),

		// ── Sub-menu trigger icon ───────────────────────────────────────
		'--rmp--menu-subitem-trigger-icon-width': withUnit(triggerIcon.width),
		'--rmp--menu-subitem-trigger-icon-height': withUnit(triggerIcon.height),
		'--rmp--menu-subitem-trigger-icon-color': triggerIcon.color,
		'--rmp--menu-subitem-trigger-icon-hover-color': triggerIcon.hoverColor,
		'--rmp--menu-subitem-trigger-icon-active-color':
			triggerIcon.activeColor,
		'--rmp--menu-subitem-trigger-icon-active-hover-color':
			triggerIcon.activeHoverColor,
		'--rmp--menu-subitem-trigger-icon-background':
			triggerIcon.backgroundColor,
		'--rmp--menu-subitem-trigger-icon-hover-background':
			triggerIcon.backgroundHoverColor,
		'--rmp--menu-subitem-trigger-icon-active-background':
			triggerIcon.backgroundActiveColor,
		'--rmp--menu-subitem-trigger-icon-active-hover-background':
			triggerIcon.backgroundActiveHoverColor,
		...borderProps('--rmp--menu-subitem-trigger', '', triggerIcon.border),
		...borderProps(
			'--rmp--menu-subitem-trigger',
			'-hover',
			triggerIcon.borderHover
		),
		...borderProps(
			'--rmp--menu-subitem-trigger',
			'-active',
			triggerIcon.borderActive
		),
		...borderProps(
			'--rmp--menu-subitem-trigger',
			'-active-hover',
			triggerIcon.borderActiveHover
		),

		// ── Desktop (inline) menu ───────────────────────────────────────
		'--rmp--desktop-menu-color': desktopMenuStyle.color,
		'--rmp--desktop-menu-hover-color': desktopMenuStyle.hoverColor,
		'--rmp--desktop-menu-active-color': desktopMenuStyle.activeColor,
		'--rmp--desktop-menu-background': desktopMenuStyle.background,
		'--rmp--desktop-menu-hover-background':
			desktopMenuStyle.backgroundHover,
		'--rmp--desktop-menu-active-background':
			desktopMenuStyle.backgroundActive,
		'--rmp--desktop-menu-item-padding': boxShorthand(
			desktopMenuStyle.itemPadding
		),
		'--rmp--desktop-menu-gap': withUnit(desktopMenuStyle.gap),
		'--rmp--desktop-menu-justify': desktopMenuStyle.justify,
		'--rmp--desktop-submenu-color': desktopMenuStyle.submenuColor,
		'--rmp--desktop-submenu-hover-color': desktopMenuStyle.submenuHoverColor,
		'--rmp--desktop-submenu-background': desktopMenuStyle.submenuBackground,
		'--rmp--desktop-submenu-hover-background':
			desktopMenuStyle.submenuBackgroundHover,
		'--rmp--desktop-submenu-min-width': withUnit(
			desktopMenuStyle.submenuMinWidth
		),
		'--rmp--desktop-submenu-animation-duration': Number.isFinite(
			Number(desktopMenuStyle.submenuAnimationSpeed)
		)
			? `${Number(desktopMenuStyle.submenuAnimationSpeed)}ms`
			: UNSET,
		'--rmp--desktop-submenu-alignment': dropdownAlign.left,
		'--rmp--desktop-submenu-right': dropdownAlign.right,
	});
}

/**
 * Custom properties for a single device — used by the editor preview, which
 * applies one device's values inline rather than via media queries.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @return {Object} CSS custom properties.
 */
export default function DynamicStyles(attributes, device = 'desktop') {
	return buildStyleProps(resolveAttributes(attributes, device, STYLE_GROUPS));
}

/**
 * The full set of styles the block saves: desktop values plus, for tablet and
 * mobile, only the properties that actually differ from the next widest tier.
 *
 * The frontend re-emits each tier inside `@media (max-width: …)`, which is why
 * a diff is enough: at mobile width the tablet query matches too, so an
 * unchanged property is simply inherited.
 *
 * @param {Object} attributes Block attributes.
 * @return {Object} `{ base, tablet, mobile }`.
 */
export function buildResponsiveStyles(attributes) {
	const base = DynamicStyles(attributes, 'desktop');
	const tabletFull = DynamicStyles(attributes, 'tablet');
	const mobileFull = DynamicStyles(attributes, 'mobile');

	return {
		base,
		tablet: diffProps(base, tabletFull),
		mobile: diffProps(tabletFull, mobileFull),
	};
}

/**
 * Properties in `next` that differ from `previous`. A property that existed in
 * `previous` and vanished in `next` is re-emitted as `initial`, otherwise the
 * wider tier's value would leak through the cascade.
 *
 * @param {Object} previous Wider tier's properties.
 * @param {Object} next     Narrower tier's properties.
 * @return {Object} Changed properties.
 */
function diffProps(previous, next) {
	const changed = {};

	Object.entries(next).forEach(([key, value]) => {
		if (previous[key] !== value) {
			changed[key] = value;
		}
	});

	Object.keys(previous).forEach((key) => {
		if (!(key in next)) {
			changed[key] = 'initial';
		}
	});

	return changed;
}
