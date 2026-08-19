/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ToolbarGroup, ToolbarButton, Button } from '@wordpress/components';
import { desktop, tablet, mobile } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { countOverrides } from '../utils/responsive';

const DEVICE_ICONS = { desktop, tablet, mobile };

const DEVICE_LABELS = {
	desktop: __('Desktop', 'responsive-menu'),
	tablet: __('Tablet', 'responsive-menu'),
	mobile: __('Mobile', 'responsive-menu'),
};

/**
 * Block-toolbar device switcher. Picks both which device the editor previews
 * and which device the inspector controls write to.
 *
 * @param {Object}   props
 * @param {string}   props.device     Selected device.
 * @param {Function} props.onChange   Device setter.
 * @param {Object}   props.attributes Block attributes, for the override count.
 * @return {Element} Toolbar group.
 */
export default function DeviceSwitcher({ device, onChange, attributes }) {
	return (
		<ToolbarGroup>
			{Object.keys(DEVICE_ICONS).map((key) => {
				const overrides = countOverrides(attributes, key);

				return (
					<ToolbarButton
						key={key}
						icon={DEVICE_ICONS[key]}
						isActive={device === key}
						label={
							overrides
								? `${DEVICE_LABELS[key]} (${overrides})`
								: DEVICE_LABELS[key]
						}
						onClick={() => onChange(key)}
					/>
				);
			})}
		</ToolbarGroup>
	);
}

/**
 * Inspector banner shown while a non-desktop device is selected, so it is
 * always obvious which layer a control is writing to.
 *
 * @param {Object}   props
 * @param {string}   props.device  Selected device.
 * @param {number}   props.count   Number of overrides on this device.
 * @param {Function} props.onReset Clears every override for the device.
 * @return {Element|null} Notice, or null on desktop.
 */
export function DeviceNotice({ device, count, onReset }) {
	if ('desktop' === device) {
		return null;
	}

	return (
		<div className="rmp-device-notice">
			<p>
				{/* translators: %s: device name. */}
				{__('Editing', 'responsive-menu')}{' '}
				<strong>{DEVICE_LABELS[device]}</strong>.{' '}
				{__(
					'Values you change here apply to this device and narrower; anything untouched is inherited.',
					'responsive-menu'
				)}
			</p>
			{count > 0 && (
				<Button variant="secondary" isDestructive onClick={onReset}>
					{__('Clear', 'responsive-menu')} {count}{' '}
					{__('override(s)', 'responsive-menu')}
				</Button>
			)}
		</div>
	);
}
