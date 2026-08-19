/**
 * Tests for the custom-property builder.
 */

import DynamicStyles, { buildStyleProps, buildResponsiveStyles } from './styles';

describe('buildStyleProps', () => {
	it('adds units to bare numbers and leaves units alone', () => {
		const props = buildStyleProps({
			menuContainerStyle: { menuWidth: 75, menuMaximumWidth: '350px' },
		});

		expect(props['--rmp--menu-container-width']).toBe('75%');
		expect(props['--rmp--menu-container-max-width']).toBe('350px');
	});

	it('drops properties whose value is missing', () => {
		const props = buildStyleProps({ menuStyle: { color: '' } });

		expect(props).not.toHaveProperty('--rmp--menu-item-color');
		expect(props).not.toHaveProperty('--rmp--menu-item-font-size');
	});

	it('never emits a value carrying undefined or NaN', () => {
		const props = buildStyleProps({
			hamburgerText: { size: 'not-a-number-px' },
			menuStyle: { letterSpacing: undefined },
		});

		Object.values(props).forEach((value) => {
			expect(value).not.toMatch(/undefined|NaN/);
		});
	});

	it('converts a focal point to percentages', () => {
		const props = buildStyleProps({
			menuContainerStyle: { backgroundPosition: { x: 0.25, y: 0.5 } },
		});

		expect(props['--rmp--menu-container-background-position']).toBe(
			'25% 50%'
		);
	});

	it('passes a background-position keyword straight through', () => {
		const props = buildStyleProps({
			menuContainerStyle: { backgroundPosition: 'center' },
		});

		expect(props['--rmp--menu-container-background-position']).toBe(
			'center'
		);
	});

	it('builds a padding shorthand from the sides that are set', () => {
		const props = buildStyleProps({
			menuContainerStyle: { padding: { top: '10px', left: 5 } },
		});

		expect(props['--rmp--menu-container-padding']).toBe('10px 0 0 5px');
	});

	it('omits padding entirely when no side is set', () => {
		const props = buildStyleProps({ menuContainerStyle: { padding: {} } });

		expect(props).not.toHaveProperty('--rmp--menu-container-padding');
	});

	it('expands a linked border to all four sides', () => {
		const props = buildStyleProps({
			menuStyle: {
				border: { width: '2px', style: 'dashed', color: '#f00' },
			},
		});

		expect(props['--rmp--menu-item-border-top']).toBe('2px dashed #f00');
		expect(props['--rmp--menu-item-border-left']).toBe('2px dashed #f00');
	});

	it('keeps per-side borders separate', () => {
		const props = buildStyleProps({
			menuStyle: {
				border: {
					top: { width: '1px', color: '#000' },
					bottom: { width: '3px', style: 'dotted', color: '#fff' },
				},
			},
		});

		expect(props['--rmp--menu-item-border-top']).toBe('1px solid #000');
		expect(props['--rmp--menu-item-border-bottom']).toBe(
			'3px dotted #fff'
		);
		expect(props).not.toHaveProperty('--rmp--menu-item-border-right');
	});

	it('only emits the overlay colour when the overlay is on', () => {
		expect(
			buildStyleProps({ overlay: { enabled: false, color: '#000' } })
		).not.toHaveProperty('--rmp--menu-overlay-background');

		expect(
			buildStyleProps({ overlay: { enabled: true, color: '#000' } })[
				'--rmp--menu-overlay-background'
			]
		).toBe('#000');
	});

	it('derives the trigger box height from the line metrics', () => {
		const props = buildStyleProps({
			hamburgerStyle: { lineSpacing: 10, lineHeight: 3 },
		});

		expect(props['--rmp--menu-hamburger-trigger-box-height']).toBe('26px');
	});
});

describe('buildResponsiveStyles', () => {
	const attributes = {
		menuContainerStyle: { background: '#111', menuWidth: 75 },
		responsive: {
			tablet: { menuContainerStyle: { menuWidth: 90 } },
			mobile: { menuContainerStyle: { menuWidth: 100 } },
		},
	};

	it('puts desktop values in the base tier', () => {
		const { base } = buildResponsiveStyles(attributes);

		expect(base['--rmp--menu-container-width']).toBe('75%');
		expect(base['--rmp--menu-container-background-color']).toBe('#111');
	});

	it('emits only what differs in the narrower tiers', () => {
		const { tablet, mobile } = buildResponsiveStyles(attributes);

		expect(tablet).toEqual({ '--rmp--menu-container-width': '90%' });
		expect(mobile).toEqual({ '--rmp--menu-container-width': '100%' });
	});

	it('leaves a tier empty when it overrides nothing', () => {
		const { tablet, mobile } = buildResponsiveStyles({
			menuContainerStyle: { background: '#111' },
		});

		expect(tablet).toEqual({});
		expect(mobile).toEqual({});
	});

	it('resets a property the narrower tier clears', () => {
		const { tablet } = buildResponsiveStyles({
			menuContainerStyle: { background: '#111' },
			responsive: { tablet: { menuContainerStyle: { background: '' } } },
		});

		// Without an explicit reset the desktop colour would cascade through.
		expect(tablet['--rmp--menu-container-background-color']).toBe(
			'initial'
		);
	});
});

describe('DynamicStyles', () => {
	it('resolves the requested device', () => {
		const attributes = {
			menuStyle: { color: '#fff' },
			responsive: { mobile: { menuStyle: { color: '#000' } } },
		};

		expect(DynamicStyles(attributes, 'desktop')['--rmp--menu-item-color']).toBe(
			'#fff'
		);
		expect(DynamicStyles(attributes, 'mobile')['--rmp--menu-item-color']).toBe(
			'#000'
		);
	});
});
