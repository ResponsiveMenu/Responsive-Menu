/**
 * Tests for the responsive value model.
 */

import {
	resolveGroup,
	isOverridden,
	buildUpdate,
	buildUpdateMany,
	buildReset,
	buildResetDevice,
	countOverrides,
	normaliseBreakpoints,
	deviceForWidth,
	isDesktopAt,
} from './responsive';

const attributes = {
	menuStyle: { color: '#fff', fontSize: '15px', textAlign: 'left' },
	responsive: {
		tablet: { menuStyle: { fontSize: '14px' } },
		mobile: { menuStyle: { textAlign: 'center' } },
	},
};

describe('resolveGroup', () => {
	it('returns the desktop value untouched', () => {
		expect(resolveGroup(attributes, 'desktop', 'menuStyle')).toEqual({
			color: '#fff',
			fontSize: '15px',
			textAlign: 'left',
		});
	});

	it('layers tablet overrides over desktop', () => {
		expect(resolveGroup(attributes, 'tablet', 'menuStyle')).toEqual({
			color: '#fff',
			fontSize: '14px',
			textAlign: 'left',
		});
	});

	it('inherits through tablet on mobile', () => {
		expect(resolveGroup(attributes, 'mobile', 'menuStyle')).toEqual({
			color: '#fff',
			// Not set on mobile, so the tablet override still applies.
			fontSize: '14px',
			textAlign: 'center',
		});
	});

	it('is empty for a group that does not exist', () => {
		expect(resolveGroup(attributes, 'mobile', 'nope')).toEqual({});
	});
});

describe('isOverridden', () => {
	it('treats every desktop key as owned by desktop', () => {
		expect(isOverridden(attributes, 'desktop', 'menuStyle', 'color')).toBe(
			true
		);
	});

	it('counts a key stored as undefined — clearing a colour is an override', () => {
		const cleared = {
			menuStyle: { color: '#fff' },
			responsive: { tablet: { menuStyle: { color: undefined } } },
		};

		expect(isOverridden(cleared, 'tablet', 'menuStyle', 'color')).toBe(true);
		expect(countOverrides(cleared, 'tablet')).toBe(1);
		expect(resolveGroup(cleared, 'tablet', 'menuStyle').color).toBeUndefined();
	});

	it('is true only for keys the device sets itself', () => {
		expect(isOverridden(attributes, 'tablet', 'menuStyle', 'fontSize')).toBe(
			true
		);
		expect(isOverridden(attributes, 'tablet', 'menuStyle', 'color')).toBe(
			false
		);
	});
});

describe('buildUpdate', () => {
	it('writes desktop values onto the attribute itself', () => {
		expect(
			buildUpdate(attributes, 'desktop', 'menuStyle', 'color', '#000')
		).toEqual({
			menuStyle: { color: '#000', fontSize: '15px', textAlign: 'left' },
		});
	});

	it('writes other devices into the responsive layer only', () => {
		const update = buildUpdate(
			attributes,
			'mobile',
			'menuStyle',
			'color',
			'#000'
		);

		expect(update.responsive.mobile.menuStyle).toEqual({
			textAlign: 'center',
			color: '#000',
		});
		// The desktop value is untouched.
		expect(update.menuStyle).toBeUndefined();
		expect(attributes.menuStyle.color).toBe('#fff');
	});
});

describe('buildUpdateMany', () => {
	it('writes every key in one payload', () => {
		const update = buildUpdateMany(attributes, 'desktop', 'menuStyle', {
			color: '#000',
			fontSize: '12px',
		});

		expect(update.menuStyle).toEqual({
			color: '#000',
			fontSize: '12px',
			textAlign: 'left',
		});
	});

	it('writes every key on a non-desktop device too', () => {
		const update = buildUpdateMany(attributes, 'tablet', 'menuStyle', {
			color: '#000',
			textAlign: 'center',
		});

		expect(update.responsive.tablet.menuStyle).toEqual({
			fontSize: '14px',
			color: '#000',
			textAlign: 'center',
		});
	});
});

describe('buildReset', () => {
	it('drops the key so the value inherits again', () => {
		const reset = buildReset(attributes, 'tablet', 'menuStyle', 'fontSize');

		expect(reset.responsive.tablet).toBeUndefined();
		expect(reset.responsive.mobile.menuStyle).toEqual({
			textAlign: 'center',
		});
	});

	it('writes a fallback on desktop, where there is nothing to inherit', () => {
		expect(
			buildReset(attributes, 'desktop', 'menuStyle', 'color', '')
		).toEqual({
			menuStyle: { color: '', fontSize: '15px', textAlign: 'left' },
		});
	});
});

describe('buildResetDevice', () => {
	it('clears one device and leaves the others', () => {
		const reset = buildResetDevice(attributes, 'tablet');

		expect(reset.responsive.tablet).toBeUndefined();
		expect(reset.responsive.mobile).toBeDefined();
	});
});

describe('countOverrides', () => {
	it('counts the keys a device sets', () => {
		expect(countOverrides(attributes, 'desktop')).toBe(0);
		expect(countOverrides(attributes, 'tablet')).toBe(1);
		expect(countOverrides(attributes, 'mobile')).toBe(1);
	});
});

describe('normaliseBreakpoints', () => {
	it('falls back to the defaults', () => {
		expect(normaliseBreakpoints({})).toEqual({
			breakpoint: 768,
			tabletBreakpoint: 1024,
			mobileBreakpoint: 767,
		});
	});

	it('keeps an explicit 0, which means "never switch to desktop"', () => {
		expect(normaliseBreakpoints({ breakpoint: 0 }).breakpoint).toBe(0);
	});

	it('keeps the mobile query narrower than the tablet one', () => {
		expect(
			normaliseBreakpoints({
				breakpoint: 900,
				tabletBreakpoint: 800,
				mobileBreakpoint: 900,
			})
		).toEqual({
			breakpoint: 900,
			tabletBreakpoint: 800,
			mobileBreakpoint: 799,
		});
	});
});

describe('deviceForWidth', () => {
	const breakpoints = normaliseBreakpoints({});

	it.each([
		[360, 'mobile'],
		[767, 'mobile'],
		[768, 'tablet'],
		[1024, 'tablet'],
		[1025, 'desktop'],
	])('maps %ipx to %s', (width, expected) => {
		expect(deviceForWidth(width, breakpoints)).toBe(expected);
	});
});

describe('isDesktopAt', () => {
	it('is true at or above the breakpoint', () => {
		expect(isDesktopAt(768, 768)).toBe(true);
		expect(isDesktopAt(1200, 768)).toBe(true);
		expect(isDesktopAt(767, 768)).toBe(false);
	});

	it('is never true when the breakpoint is 0', () => {
		// 0 means the menu stays off-canvas at every width.
		expect(isDesktopAt(4000, 0)).toBe(false);
	});
});
