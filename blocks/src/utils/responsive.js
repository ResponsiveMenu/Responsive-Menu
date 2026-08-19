/**
 * Responsive value model for the Responsive Menu block.
 *
 * Desktop values live on the block attributes themselves (so every menu saved
 * before this feature keeps rendering unchanged). Tablet and mobile hold only
 * the keys that differ, under a single `responsive` attribute:
 *
 *   attributes.menuStyle              -> desktop value
 *   attributes.responsive.tablet.menuStyle -> tablet overrides
 *   attributes.responsive.mobile.menuStyle -> mobile overrides
 *
 * A device inherits from the next widest one, so an untouched tablet menu looks
 * exactly like the desktop menu until something is explicitly overridden.
 */

export const DEVICES = ['desktop', 'tablet', 'mobile'];

/**
 * Inheritance chain per device, narrowest last.
 */
export const DEVICE_CHAIN = {
	desktop: ['desktop'],
	tablet: ['desktop', 'tablet'],
	mobile: ['desktop', 'tablet', 'mobile'],
};

/**
 * Viewport width used to render the editor preview for each device. Desktop is
 * null, meaning "whatever width the editor canvas already is".
 */
export const DEVICE_PREVIEW_WIDTH = {
	desktop: null,
	tablet: 780,
	mobile: 390,
};

/**
 * Read the overrides stored for one device + group.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @param {string} group      Attribute name, e.g. `menuStyle`.
 * @return {Object} The stored overrides (never undefined).
 */
export function getOverrides(attributes, device, group) {
	if ('desktop' === device) {
		return attributes?.[group] || {};
	}

	return attributes?.responsive?.[device]?.[group] || {};
}

/**
 * Resolve the effective value of an attribute group for a device, walking the
 * inheritance chain. Only keys explicitly present in an override layer win, so
 * deleting a key falls back to the wider device instead of blanking the style.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @param {string} group      Attribute name, e.g. `menuStyle`.
 * @return {Object} Resolved group value.
 */
export function resolveGroup(attributes, device, group) {
	const chain = DEVICE_CHAIN[device] || DEVICE_CHAIN.desktop;

	return chain.reduce(
		(resolved, layer) => ({
			...resolved,
			...getOverrides(attributes, layer, group),
		}),
		{}
	);
}

/**
 * Resolve every requested group at once — the shape the style builder wants.
 *
 * @param {Object}   attributes Block attributes.
 * @param {string}   device     desktop | tablet | mobile.
 * @param {string[]} groups     Attribute names to resolve.
 * @return {Object} `{ [group]: resolvedValue }`.
 */
export function resolveAttributes(attributes, device, groups) {
	return groups.reduce((resolved, group) => {
		resolved[group] = resolveGroup(attributes, device, group);
		return resolved;
	}, {});
}

/**
 * Whether a device carries its own value for a key (i.e. it is overridden
 * rather than inherited). Used to badge controls in the inspector.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @param {string} group      Attribute name.
 * @param {string} key        Key inside the group.
 * @return {boolean} True when this device sets the key itself.
 */
export function isOverridden(attributes, device, group, key) {
	if ('desktop' === device) {
		return true;
	}

	// Presence, not value: clearing a colour stores `undefined`, and that is a
	// deliberate "no colour on this device" the user must be able to reset.
	// Testing the value instead would hide the reset button while
	// `countOverrides` still counted the key.
	return Object.prototype.hasOwnProperty.call(
		getOverrides(attributes, device, group),
		key
	);
}

/**
 * Build the `setAttributes` payload that writes one key for one device.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @param {string} group      Attribute name.
 * @param {string} key        Key inside the group.
 * @param {*}      value      New value.
 * @return {Object} Payload for `setAttributes`.
 */
export function buildUpdate(attributes, device, group, key, value) {
	if ('desktop' === device) {
		return {
			[group]: {
				...(attributes?.[group] || {}),
				[key]: value,
			},
		};
	}

	const responsive = attributes?.responsive || {};
	const deviceLayer = responsive[device] || {};

	return {
		responsive: {
			...responsive,
			[device]: {
				...deviceLayer,
				[group]: {
					...(deviceLayer[group] || {}),
					[key]: value,
				},
			},
		},
	};
}

/**
 * Build the payload that writes several keys of one group at once.
 *
 * Sequential `update` calls would each build from the same, now-stale
 * attributes, and `setAttributes` merges only at the top level — so the last
 * write would replace the group and every earlier key would be lost.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @param {string} group      Attribute name.
 * @param {Object} values     Keys to write.
 * @return {Object} Payload for `setAttributes`.
 */
export function buildUpdateMany(attributes, device, group, values) {
	if ('desktop' === device) {
		return {
			[group]: {
				...(attributes?.[group] || {}),
				...values,
			},
		};
	}

	const responsive = attributes?.responsive || {};
	const deviceLayer = responsive[device] || {};

	return {
		responsive: {
			...responsive,
			[device]: {
				...deviceLayer,
				[group]: {
					...(deviceLayer[group] || {}),
					...values,
				},
			},
		},
	};
}

/**
 * Build the payload that removes a device override so the key inherits again.
 * On desktop there is nothing to inherit from, so the key is set to
 * `fallback` instead of being deleted.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @param {string} group      Attribute name.
 * @param {string} key        Key inside the group.
 * @param {*}      fallback   Value to write on desktop.
 * @return {Object} Payload for `setAttributes`.
 */
export function buildReset(attributes, device, group, key, fallback = '') {
	if ('desktop' === device) {
		return buildUpdate(attributes, device, group, key, fallback);
	}

	const responsive = attributes?.responsive || {};
	const deviceLayer = responsive[device] || {};
	const groupLayer = { ...(deviceLayer[group] || {}) };

	delete groupLayer[key];

	const nextDeviceLayer = { ...deviceLayer, [group]: groupLayer };

	if (0 === Object.keys(groupLayer).length) {
		delete nextDeviceLayer[group];
	}

	const nextResponsive = { ...responsive, [device]: nextDeviceLayer };

	if (0 === Object.keys(nextDeviceLayer).length) {
		delete nextResponsive[device];
	}

	return { responsive: nextResponsive };
}

/**
 * Clear every override a device holds — the "reset this device" action.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     tablet | mobile.
 * @return {Object} Payload for `setAttributes`.
 */
export function buildResetDevice(attributes, device) {
	const responsive = { ...(attributes?.responsive || {}) };
	delete responsive[device];
	return { responsive };
}

/**
 * How many overrides a device holds, so the UI can show a count.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} device     desktop | tablet | mobile.
 * @return {number} Number of overridden keys.
 */
export function countOverrides(attributes, device) {
	if ('desktop' === device) {
		return 0;
	}

	const deviceLayer = attributes?.responsive?.[device] || {};

	return Object.values(deviceLayer).reduce(
		(total, group) => total + Object.keys(group || {}).length,
		0
	);
}

/**
 * A small accessor bundle so inspector controls stay readable:
 *
 *   const rs = createResponsiveHelpers( attributes, setAttributes, device );
 *   rs.get( 'menuStyle' ).color
 *   rs.update( 'menuStyle', 'color', '#fff' )
 *
 * @param {Object}   attributes    Block attributes.
 * @param {Function} setAttributes Block setter.
 * @param {string}   device        desktop | tablet | mobile.
 * @return {Object} Helper bundle.
 */
export function createResponsiveHelpers(attributes, setAttributes, device) {
	return {
		device,
		isResponsive: 'desktop' !== device,
		get: (group) => resolveGroup(attributes, device, group),
		update: (group, key, value) =>
			setAttributes(buildUpdate(attributes, device, group, key, value)),
		updateMany: (group, values) =>
			setAttributes(buildUpdateMany(attributes, device, group, values)),
		reset: (group, key, fallback) =>
			setAttributes(
				buildReset(attributes, device, group, key, fallback)
			),
		isOverridden: (group, key) =>
			isOverridden(attributes, device, group, key),
	};
}

/**
 * Parse a breakpoint, keeping an explicit 0 and defaulting only when the value
 * is genuinely absent or unparseable.
 *
 * @param {*}      value    Raw value.
 * @param {number} fallback Default when absent.
 * @return {number} Breakpoint in px.
 */
function toPx(value, fallback) {
	const parsed = parseInt(value, 10);
	return Number.isFinite(parsed) ? parsed : fallback;
}

/**
 * Keep the breakpoints in a sane order: mobile <= tablet <= desktop switch.
 *
 * @param {Object} breakpoints                  Raw breakpoint values.
 * @param {number} breakpoints.breakpoint       Width at which the menu goes inline.
 * @param {number} breakpoints.tabletBreakpoint Width below which tablet styles apply.
 * @param {number} breakpoints.mobileBreakpoint Width below which mobile styles apply.
 * @return {Object} Normalised breakpoints.
 */
export function normaliseBreakpoints({
	breakpoint,
	tabletBreakpoint,
	mobileBreakpoint,
}) {
	// 0 is a real setting — "never switch to the inline desktop bar" — so it
	// must survive rather than being read as "unset" and defaulted.
	const desktopAt = Math.max(0, toPx(breakpoint, 768));
	const tabletAt = Math.max(1, toPx(tabletBreakpoint, 1024));
	const mobileAt = Math.max(1, Math.min(toPx(mobileBreakpoint, 767), tabletAt - 1));

	return {
		breakpoint: desktopAt,
		tabletBreakpoint: tabletAt,
		mobileBreakpoint: mobileAt,
	};
}

/**
 * Helpers for settings that cannot vary by device.
 *
 * Anything that changes the saved markup or a class name — the trigger type,
 * its icon, the label text, the animation — has only one saved value, so the
 * inspector must write it to the shared layer whichever device is selected.
 * Routing it through the responsive helpers instead would show the change in
 * the editor and silently drop it on save.
 *
 * @param {Object}   attributes    Block attributes.
 * @param {Function} setAttributes Block setter.
 * @return {Object} Helper bundle pinned to the shared layer.
 */
export function createSharedHelpers(attributes, setAttributes) {
	return createResponsiveHelpers(attributes, setAttributes, 'desktop');
}

/**
 * Whether the inline desktop bar applies at a given width.
 *
 * @param {number} width      Viewport width in px.
 * @param {number} breakpoint Desktop breakpoint; 0 means never.
 * @return {boolean} True when the desktop layout applies.
 */
export function isDesktopAt(width, breakpoint) {
	return breakpoint > 0 && width >= breakpoint;
}

/**
 * Which device tier a preview width falls into.
 *
 * @param {number} width       Preview width in px.
 * @param {Object} breakpoints Normalised breakpoints.
 * @return {string} desktop | tablet | mobile.
 */
export function deviceForWidth(width, breakpoints) {
	if (width <= breakpoints.mobileBreakpoint) {
		return 'mobile';
	}
	if (width <= breakpoints.tabletBreakpoint) {
		return 'tablet';
	}
	return 'desktop';
}
