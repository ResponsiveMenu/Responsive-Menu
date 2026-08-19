/**
 * Frontend entry point for the Responsive Menu block.
 *
 * Boots every menu on the page and keeps watching for menus that arrive later
 * — infinite scroll, AJAX-loaded headers and page builders all add navigation
 * after `DOMContentLoaded`, and a one-shot init silently leaves those dead.
 */

import { onReady } from './frontend/dom';
import ResponsiveMenu from './frontend/menu';

const instances = new WeakMap();

/**
 * Initialise every menu that has not been initialised yet.
 */
function initAll() {
	document
		.querySelectorAll('.rmp-block-navigator:not([data-rmp-init])')
		.forEach((root) => {
			root.dataset.rmpInit = '1';
			instances.set(root, new ResponsiveMenu(root));
		});
}

/**
 * Watch for menus added to the document after the first pass. The callback is
 * coalesced into one microtask-ish tick so a large DOM insertion does not run
 * the query once per node.
 */
function watchForNewMenus() {
	if ('undefined' === typeof window.MutationObserver) {
		return;
	}

	let scheduled = false;

	const observer = new window.MutationObserver((records) => {
		if (scheduled) {
			return;
		}

		const addedElements = records.some((record) =>
			Array.from(record.addedNodes).some(
				// Node.ELEMENT_NODE
				(node) => 1 === node.nodeType
			)
		);

		if (!addedElements) {
			return;
		}

		scheduled = true;
		window.requestAnimationFrame(() => {
			scheduled = false;
			initAll();
		});
	});

	observer.observe(document.body, { childList: true, subtree: true });
}

onReady(() => {
	initAll();
	watchForNewMenus();
});

// Exposed so themes and add-ons can drive a menu without reaching into the DOM.
window.rmpBlockMenu = {
	init: initAll,
	get: (root) => instances.get(root),
};
