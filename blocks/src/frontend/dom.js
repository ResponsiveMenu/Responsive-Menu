/**
 * Small DOM helpers shared by the frontend runtime.
 */

/**
 * Read a boolean data attribute. Absent, `false` and `0` are all false.
 *
 * @param {Element} element  Element to read from.
 * @param {string}  name     Attribute name, including the `data-` prefix.
 * @param {boolean} fallback Value when the attribute is absent.
 * @return {boolean} Parsed value.
 */
export function dataFlag(element, name, fallback = false) {
	if (!element || !element.hasAttribute(name)) {
		return fallback;
	}

	const value = element.getAttribute(name);

	return 'false' !== value && '0' !== value && '' !== value;
}

/**
 * Read an integer data attribute.
 *
 * @param {Element} element  Element to read from.
 * @param {string}  name     Attribute name, including the `data-` prefix.
 * @param {number}  fallback Value when absent or unparseable.
 * @return {number} Parsed value.
 */
export function dataInt(element, name, fallback = 0) {
	const value = parseInt(element?.getAttribute(name), 10);
	return Number.isFinite(value) ? value : fallback;
}

/**
 * Elements that can receive focus, in document order.
 */
const FOCUSABLE = [
	'a[href]',
	'button:not([disabled])',
	'input:not([disabled]):not([type="hidden"])',
	'select:not([disabled])',
	'textarea:not([disabled])',
	'[tabindex]:not([tabindex="-1"])',
].join(',');

/**
 * Visible, focusable descendants of an element.
 *
 * @param {Element} root Container to search.
 * @return {Element[]} Focusable elements.
 */
export function focusableWithin(root) {
	if (!root) {
		return [];
	}

	return Array.from(root.querySelectorAll(FOCUSABLE)).filter((element) => {
		if (element.getAttribute('aria-hidden') === 'true') {
			return false;
		}
		return !!(
			element.offsetWidth ||
			element.offsetHeight ||
			element.getClientRects().length
		);
	});
}

/**
 * Replace an element's contents with an icon, without ever writing untrusted
 * markup: text shapes go in as text, image icons are built as a real `img`
 * node, and only SVG cloned from the block's own hidden template is inserted
 * as markup.
 *
 * @param {Element} target Element to fill.
 * @param {Object}  icon   `{ type, value, node }`.
 */
export function renderIcon(target, icon) {
	if (!target) {
		return;
	}

	target.textContent = '';

	if (!icon || !icon.value) {
		return;
	}

	if ('icon' === icon.type && icon.node) {
		target.appendChild(icon.node.cloneNode(true));
		return;
	}

	if ('image' === icon.type) {
		const image = document.createElement('img');
		image.src = icon.value;
		image.alt = '';
		image.setAttribute('aria-hidden', 'true');
		target.appendChild(image);
		return;
	}

	target.textContent = icon.value;
}

/**
 * Run a callback once the document is parsed — immediately when it already is,
 * so a deferred or late-injected bundle still initialises.
 *
 * @param {Function} callback Callback to run.
 */
export function onReady(callback) {
	if ('loading' !== document.readyState) {
		callback();
		return;
	}

	document.addEventListener('DOMContentLoaded', callback, { once: true });
}
