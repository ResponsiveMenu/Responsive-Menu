/**
 * Frontend controller for one Responsive Menu block.
 *
 * Owns: the open/closed state of the off-canvas container, the desktop /
 * mobile switch, and every close trigger the block offers. Sub-menu behaviour
 * lives in SubmenuController, one per menu-items list.
 */

import { dataFlag, dataInt, focusableWithin } from './dom';
import SubmenuController from './submenu';

const ACTIVE = 'rmp-block-active';
const DESKTOP = 'rmp-desktop-mode';
const BODY_LOCK = 'rmp-block-menu-open';

export default class ResponsiveMenu {
	/**
	 * @param {HTMLElement} root The `nav.rmp-block-navigator` element.
	 */
	constructor(root) {
		this.root = root;
		this.trigger = root.querySelector('.rmp-block-menu-trigger');
		this.container = root.querySelector('.rmp-block-container');

		if (!this.trigger || !this.container) {
			return;
		}

		this.i18n = window.rmpBlockI18n || {};
		this.isOpen = false;
		this.isDesktop = false;
		this.modeApplied = false;
		this.lastFocused = null;
		this.listeners = [];

		this.settings = this.readSettings();
		this.submenus = Array.from(
			this.container.querySelectorAll('.wp-block-rmp-menu-items')
		).map((list) => new SubmenuController(list, this));

		this.prepareAccessibility();
		this.buildOverlay();
		this.bindTrigger();
		this.bindCloseTriggers();
		this.watchBreakpoint();
	}

	/**
	 * Behaviour flags, read from the nav with a fallback to the trigger so
	 * menus saved by earlier versions of the block keep working.
	 *
	 * @return {Object} Settings.
	 */
	readSettings() {
		const legacy = this.trigger;

		return {
			breakpoint: dataInt(this.root, 'data-breakpoint', 0),
			closeOnLink:
				dataFlag(this.root, 'data-close-on-link') ||
				dataFlag(legacy, 'data-hide-link-click'),
			closeOnBodyClick:
				dataFlag(this.root, 'data-close-on-body-click') ||
				dataFlag(legacy, 'data-hide-page-click'),
			closeOnScroll:
				dataFlag(this.root, 'data-close-on-scroll') ||
				dataFlag(legacy, 'data-hide-on-scroll'),
			closeOnEsc: dataFlag(this.root, 'data-close-on-esc', true),
			scrollLock: dataFlag(this.root, 'data-scroll-lock'),
			focusTrap: dataFlag(this.root, 'data-focus-trap', true),
			swipe: dataFlag(this.root, 'data-swipe'),
			overlay: dataFlag(this.root, 'data-overlay'),
			direction: this.root.getAttribute('data-direction') || 'left',
		};
	}

	/**
	 * Wire up the ARIA relationships the markup cannot express statically.
	 */
	prepareAccessibility() {
		this.trigger.setAttribute('aria-expanded', 'false');

		if (!this.container.id) {
			this.container.id = `rmp-block-container-${Math.random()
				.toString(36)
				.slice(2)}`;
		}

		this.trigger.setAttribute('aria-controls', this.container.id);
	}

	/**
	 * The dimming layer behind an open menu. Created only when enabled, and
	 * always inside the block so it inherits the block's custom properties.
	 */
	buildOverlay() {
		if (!this.settings.overlay) {
			return;
		}

		this.overlay = document.createElement('div');
		this.overlay.className = 'rmp-block-overlay';
		this.overlay.setAttribute('aria-hidden', 'true');
		this.overlay.addEventListener('click', () => this.close());
		this.root.insertBefore(this.overlay, this.container);
	}

	/**
	 * @param {EventTarget} target  Event target.
	 * @param {string}      type    Event name.
	 * @param {Function}    handler Handler.
	 * @param {Object}      options addEventListener options.
	 */
	on(target, type, handler, options) {
		target.addEventListener(type, handler, options);
		this.listeners.push([target, type, handler, options]);
	}

	bindTrigger() {
		this.on(this.trigger, 'click', (event) => {
			event.preventDefault();
			event.stopPropagation();
			this.toggle();
		});
	}

	/**
	 * Every "close the menu" path. All handlers are bound once and check the
	 * open state, rather than being attached and detached on each toggle —
	 * that is what used to stack duplicate listeners on repeated opens.
	 */
	bindCloseTriggers() {
		if (this.settings.closeOnLink) {
			this.on(this.container, 'click', (event) => {
				if (!this.isOpen || this.isDesktop) {
					return;
				}
				const link = event.target.closest('a[href]');
				if (link && this.container.contains(link)) {
					this.close({ restoreFocus: false });
				}
			});
		}

		if (this.settings.closeOnBodyClick) {
			this.on(document, 'click', (event) => {
				if (!this.isOpen || this.isDesktop) {
					return;
				}
				if (!this.root.contains(event.target)) {
					this.close();
				}
			});
		}

		if (this.settings.closeOnScroll) {
			this.on(
				window,
				'scroll',
				() => {
					if (this.isOpen && !this.isDesktop) {
						this.close({ restoreFocus: false });
					}
				},
				{ passive: true }
			);
		}

		this.on(document, 'keydown', (event) => {
			if ('Escape' === event.key) {
				if (this.isDesktop) {
					this.closeAllSubmenus();
					return;
				}
				if (this.isOpen && this.settings.closeOnEsc) {
					this.close();
				}
				return;
			}

			if ('Tab' === event.key) {
				this.trapFocus(event);
			}
		});

		// Desktop dropdowns close when the pointer goes elsewhere.
		this.on(document, 'click', (event) => {
			if (this.isDesktop && !this.root.contains(event.target)) {
				this.closeAllSubmenus();
			}
		});

		if (this.settings.swipe) {
			this.bindSwipe();
		}
	}

	/**
	 * Swipe the panel away in the direction it came from.
	 */
	bindSwipe() {
		let startX = 0;
		let startY = 0;

		this.on(
			this.container,
			'touchstart',
			(event) => {
				startX = event.changedTouches[0].clientX;
				startY = event.changedTouches[0].clientY;
			},
			{ passive: true }
		);

		this.on(
			this.container,
			'touchend',
			(event) => {
				if (!this.isOpen || this.isDesktop) {
					return;
				}

				const deltaX = event.changedTouches[0].clientX - startX;
				const deltaY = event.changedTouches[0].clientY - startY;

				if (Math.abs(deltaX) < 60 || Math.abs(deltaX) < Math.abs(deltaY)) {
					return;
				}

				const away =
					('right' === this.settings.direction && deltaX > 0) ||
					('left' === this.settings.direction && deltaX < 0);

				if (away) {
					this.close();
				}
			},
			{ passive: true }
		);
	}

	/**
	 * Keep Tab inside the open panel so focus cannot land on the page behind.
	 *
	 * @param {KeyboardEvent} event Keydown event.
	 */
	trapFocus(event) {
		if (!this.isOpen || this.isDesktop || !this.settings.focusTrap) {
			return;
		}

		const focusable = [this.trigger, ...focusableWithin(this.container)];

		if (0 === focusable.length) {
			return;
		}

		const first = focusable[0];
		const last = focusable[focusable.length - 1];
		const active = this.root.ownerDocument.activeElement;

		if (event.shiftKey && (active === first || !this.root.contains(active))) {
			event.preventDefault();
			last.focus();
			return;
		}

		if (!event.shiftKey && active === last) {
			event.preventDefault();
			first.focus();
		}
	}

	toggle() {
		if (this.isOpen) {
			this.close();
		} else {
			this.open();
		}
	}

	open() {
		if (this.isOpen || this.isDesktop) {
			return;
		}

		this.lastFocused = this.root.ownerDocument.activeElement;
		this.isOpen = true;

		this.root.classList.add(ACTIVE);
		this.trigger.classList.add(ACTIVE);
		this.container.classList.add(ACTIVE);
		this.trigger.setAttribute('aria-expanded', 'true');
		this.container.removeAttribute('aria-hidden');
		this.overlay?.classList.add(ACTIVE);

		if (this.settings.scrollLock) {
			document.body.classList.add(BODY_LOCK);
		}

		const first = focusableWithin(this.container)[0];
		first?.focus({ preventScroll: true });

		this.root.dispatchEvent(
			new CustomEvent('rmp-menu-open', { bubbles: true })
		);
	}

	/**
	 * @param {Object}  options
	 * @param {boolean} options.restoreFocus Return focus to the trigger.
	 */
	close({ restoreFocus = true } = {}) {
		if (!this.isOpen) {
			return;
		}

		this.isOpen = false;

		this.root.classList.remove(ACTIVE);
		this.trigger.classList.remove(ACTIVE);
		this.container.classList.remove(ACTIVE);
		this.trigger.setAttribute('aria-expanded', 'false');
		this.overlay?.classList.remove(ACTIVE);
		document.body.classList.remove(BODY_LOCK);

		if (!this.isDesktop) {
			this.container.setAttribute('aria-hidden', 'true');
		}

		const active = this.root.ownerDocument.activeElement;

		if (restoreFocus && this.root.contains(active)) {
			this.trigger.focus({ preventScroll: true });
		}

		this.lastFocused = null;

		this.root.dispatchEvent(
			new CustomEvent('rmp-menu-close', { bubbles: true })
		);
	}

	closeAllSubmenus() {
		this.submenus.forEach((controller) => controller.closeAll());
	}

	/**
	 * Switch between the off-canvas panel and the inline desktop bar. The CSS
	 * carries an equivalent media query so the correct layout is painted
	 * before this runs; this keeps the ARIA state and open panels in sync.
	 */
	watchBreakpoint() {
		const { breakpoint } = this.settings;

		if (!breakpoint) {
			this.applyMode(false);
			return;
		}

		const query = window.matchMedia(`(min-width: ${breakpoint}px)`);
		const listener = (event) => this.applyMode(event.matches);

		this.applyMode(query.matches);

		if (query.addEventListener) {
			this.on(query, 'change', listener);
		} else {
			// Safari < 14.
			query.addListener(listener);
		}
	}

	/**
	 * @param {boolean} isDesktop Whether the desktop layout applies.
	 */
	applyMode(isDesktop) {
		const wasDesktop = this.isDesktop;
		const isFirstRun = !this.modeApplied;

		this.isDesktop = isDesktop;
		this.modeApplied = true;
		this.root.classList.toggle(DESKTOP, isDesktop);

		if (isDesktop) {
			this.close({ restoreFocus: false });
			this.container.removeAttribute('aria-hidden');
			this.trigger.setAttribute('aria-hidden', 'true');
			this.trigger.setAttribute('tabindex', '-1');
		} else {
			// Only on an actual switch out of desktop — collapsing here on the
			// first run would undo the auto-expand settings straight away.
			if (wasDesktop && !isFirstRun) {
				this.closeAllSubmenus();
			}
			this.trigger.removeAttribute('aria-hidden');
			this.trigger.removeAttribute('tabindex');
			if (!this.isOpen) {
				this.container.setAttribute('aria-hidden', 'true');
			}
		}
	}

	/**
	 * Detach every listener — used when a block is removed from the DOM.
	 */
	destroy() {
		this.listeners.forEach(([target, type, handler, options]) =>
			target.removeEventListener(type, handler, options)
		);
		this.listeners = [];
		delete this.root.dataset.rmpInit;
	}
}
