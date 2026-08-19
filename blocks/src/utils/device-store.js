/**
 * Which device the editor is currently previewing / editing.
 *
 * This is an editing preference, not content, so it deliberately lives outside
 * the block attributes — otherwise switching preview would dirty the post. Both
 * `rmp/menu` and its inner `rmp/menu-items` subscribe to the same value so the
 * whole menu previews as one device.
 */

import { useEffect, useState } from '@wordpress/element';

let currentDevice = 'desktop';
const listeners = new Set();

/**
 * @return {string} The device currently being previewed.
 */
export function getPreviewDevice() {
	return currentDevice;
}

/**
 * @param {string} device desktop | tablet | mobile.
 */
export function setPreviewDevice(device) {
	if (device === currentDevice) {
		return;
	}

	currentDevice = device;
	listeners.forEach((listener) => listener(currentDevice));
}

/**
 * Subscribe to the previewed device.
 *
 * @return {[string, Function]} `[ device, setDevice ]`.
 */
export function usePreviewDevice() {
	const [device, setDevice] = useState(currentDevice);

	useEffect(() => {
		const listener = (next) => setDevice(next);
		listeners.add(listener);

		// Another block may have changed it between render and effect.
		if (currentDevice !== device) {
			setDevice(currentDevice);
		}

		return () => listeners.delete(listener);
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	return [device, setPreviewDevice];
}
