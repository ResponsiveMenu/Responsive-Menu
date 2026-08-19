/**
 * Sub-menu behaviour for one `rmp/menu-items` list.
 *
 * Core's navigation blocks render a submenu as a nested `ul` that is shown by
 * CSS on hover. That does not work on touch, and it gives no control over
 * accordion / auto-expand behaviour, so the menu injects its own toggle button
 * per submenu and drives the open state from a class.
 */

import { dataFlag, renderIcon } from './dom';

const OPEN_CLASS = 'rmp-block-active-submenu';
const ARROW_CLASS = 'rmp-block-menu-subarrow';

export default class SubmenuController {
	/**
	 * @param {HTMLElement} list The `ul.wp-block-rmp-menu-items` element.
	 * @param {Object}      menu The owning menu instance.
	 */
	constructor(list, menu) {
		this.list = list;
		this.menu = menu;

		this.settings = {
			accordion: dataFlag(list, 'data-use-accordion'),
			autoExpandAll: dataFlag(list, 'data-auto-expand'),
			autoExpandCurrent: dataFlag(list, 'data-auto-expand-current'),
			expandChildren: dataFlag(list, 'data-auto-expand-parent'),
			itemClickOpens: dataFlag(list, 'data-item-click-opens'),
			desktopTrigger:
				list.getAttribute('data-desktop-submenu-trigger') || 'click',
		};

		this.icons = this.readIcons();

		this.applyLevelClasses(this.list, 1);
		this.submenus = Array.from(
			this.list.querySelectorAll('.wp-block-navigation-submenu')
		);
		this.submenus.forEach((submenu) => this.prepare(submenu));

		this.applyAutoExpand();
	}

	/**
	 * The inactive / active icon for every arrow, resolved once.
	 *
	 * @return {Object} `{ inactive, active }` icon descriptors.
	 */
	readIcons() {
		const type = this.list.getAttribute('data-submenu-icon-type') || 'text';
		const inactiveValue = this.list.getAttribute('data-submenu-icon') || '';
		const activeValue =
			this.list.getAttribute('data-submenu-active-icon') || '';

		let inactiveNode = null;
		let activeNode = null;

		if ('icon' === type) {
			// The template is the list's own preceding sibling; falling back to
			// the first one in the menu keeps markup saved before that layout
			// working, but a menu with several lists needs the scoped lookup.
			const sibling = this.list.previousElementSibling;
			const template =
				sibling && sibling.matches('.rmp-submenu-trigger-icon')
					? sibling
					: this.menu.root.querySelector('.rmp-submenu-trigger-icon');
			inactiveNode =
				template?.querySelector(
					'.rmp-inactive-submenu-trigger-icon > *'
				) || null;
			activeNode =
				template?.querySelector('.rmp-active-submenu-trigger-icon > *') ||
				null;
		}

		// For the icon type the value is only a presence flag — the SVG comes
		// from the cloned node. With no node there is nothing to draw, and a
		// non-empty value here would be printed as literal text instead.
		const describe = (value, node) => {
			if ('icon' !== type) {
				return { type, value, node };
			}
			return { type, value: node ? 'icon' : '', node };
		};

		return {
			inactive: describe(inactiveValue, inactiveNode),
			active: describe(activeValue, activeNode),
		};
	}

	/**
	 * Tag each nested submenu container with its depth so indentation custom
	 * properties can target it. Only direct children are walked, otherwise a
	 * level-3 list would also match the level-1 selector.
	 *
	 * @param {HTMLElement} parent Container to walk.
	 * @param {number}      level  Depth of `parent`'s children.
	 */
	applyLevelClasses(parent, level) {
		Array.from(parent.children).forEach((child) => {
			const container = child.matches(
				'.wp-block-navigation__submenu-container'
			)
				? child
				: child.querySelector(
						':scope > .wp-block-navigation__submenu-container'
					);

			if (!container) {
				return;
			}

			if (
				!Array.from(container.classList).some((name) =>
					name.startsWith('rmp-block-submenu-level-')
				)
			) {
				container.classList.add(`rmp-block-submenu-level-${level}`);
			}

			this.applyLevelClasses(container, level + 1);
		});
	}

	/**
	 * Give one submenu its toggle button and event handlers.
	 *
	 * @param {HTMLElement} submenu A `.wp-block-navigation-submenu` element.
	 */
	prepare(submenu) {
		if (submenu.dataset.rmpSubmenu) {
			return;
		}
		submenu.dataset.rmpSubmenu = '1';

		const container = submenu.querySelector(
			':scope > .wp-block-navigation__submenu-container'
		);

		if (!container) {
			return;
		}

		const anchor = submenu.querySelector(':scope > a');
		const arrow = document.createElement('button');
		arrow.className = ARROW_CLASS;
		arrow.type = 'button';
		arrow.setAttribute('aria-expanded', 'false');
		arrow.setAttribute(
			'aria-label',
			this.menu.i18n.toggleSubmenu || 'Toggle submenu'
		);
		renderIcon(arrow, this.icons.inactive);

		if (anchor) {
			anchor.insertAdjacentElement('afterend', arrow);
		} else {
			submenu.insertBefore(arrow, container);
		}

		this.on(arrow, 'click', (event) => {
			event.preventDefault();
			event.stopPropagation();
			this.toggle(submenu);
		});

		// Opening from the item itself, when the menu is configured that way.
		if (this.settings.itemClickOpens && anchor) {
			this.on(anchor, 'click', (event) => {
				if (this.isOpen(submenu)) {
					return;
				}
				event.preventDefault();
				// Without this the click reaches the menu's close-on-link
				// handler and shuts the whole panel the moment the sub-menu
				// opens.
				event.stopPropagation();
				this.open(submenu);
			});
		}

		// Desktop hover dropdowns, with keyboard parity via focus/blur.
		this.on(submenu, 'pointerenter', () => {
			if (this.isHoverMode()) {
				this.open(submenu);
			}
		});
		this.on(submenu, 'pointerleave', () => {
			if (this.isHoverMode()) {
				this.close(submenu);
			}
		});
		this.on(submenu, 'focusin', () => {
			if (this.isHoverMode()) {
				this.open(submenu);
			}
		});
		this.on(submenu, 'focusout', (event) => {
			if (this.isHoverMode() && !submenu.contains(event.relatedTarget)) {
				this.close(submenu);
			}
		});
	}

	/**
	 * Register a listener on the owning menu, so `destroy()` detaches it.
	 *
	 * @param {EventTarget} target  Event target.
	 * @param {string}      type    Event name.
	 * @param {Function}    handler Handler.
	 */
	on(target, type, handler) {
		this.menu.on(target, type, handler);
	}

	/**
	 * @return {boolean} True when hover should open dropdowns right now.
	 */
	isHoverMode() {
		return (
			this.menu.isDesktop &&
			'hover' === this.settings.desktopTrigger &&
			window.matchMedia('(hover: hover)').matches
		);
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 * @return {boolean} Whether it is open.
	 */
	isOpen(submenu) {
		return submenu.classList.contains(OPEN_CLASS);
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 */
	toggle(submenu) {
		if (this.isOpen(submenu)) {
			this.close(submenu);
		} else {
			this.open(submenu);
		}
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 */
	open(submenu) {
		if (this.isOpen(submenu)) {
			return;
		}

		if (this.settings.accordion) {
			this.closeSiblings(submenu);
		}

		submenu.classList.add(OPEN_CLASS);
		this.setArrowState(submenu, true);

		if (this.settings.expandChildren) {
			this.childSubmenus(submenu).forEach((child) => this.open(child));
		}
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 */
	close(submenu) {
		if (!this.isOpen(submenu)) {
			return;
		}

		submenu.classList.remove(OPEN_CLASS);
		this.setArrowState(submenu, false);

		// A closed parent must not leave open descendants behind it.
		submenu
			.querySelectorAll(`.${OPEN_CLASS}`)
			.forEach((child) => this.close(child));
	}

	/**
	 * Close every open submenu in this list.
	 */
	closeAll() {
		this.submenus
			.filter((submenu) => this.isOpen(submenu))
			.forEach((submenu) => this.close(submenu));
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 */
	closeSiblings(submenu) {
		const parent = submenu.parentElement;

		if (!parent) {
			return;
		}

		Array.from(
			parent.querySelectorAll(
				`:scope > .wp-block-navigation-submenu.${OPEN_CLASS}`
			)
		)
			.filter((sibling) => sibling !== submenu)
			.forEach((sibling) => this.close(sibling));
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 * @return {HTMLElement[]} Its direct child submenus.
	 */
	childSubmenus(submenu) {
		return Array.from(
			submenu.querySelectorAll(
				':scope > .wp-block-navigation__submenu-container > .wp-block-navigation-submenu'
			)
		);
	}

	/**
	 * @param {HTMLElement} submenu Submenu element.
	 * @param {boolean}     isOpen  Open state.
	 */
	setArrowState(submenu, isOpen) {
		const arrow = submenu.querySelector(`:scope > .${ARROW_CLASS}`);

		if (!arrow) {
			return;
		}

		arrow.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		renderIcon(arrow, isOpen ? this.icons.active : this.icons.inactive);
	}

	/**
	 * Apply the auto-expand settings once, at boot.
	 */
	applyAutoExpand() {
		if (this.settings.autoExpandAll) {
			// "Expand everything" and accordion are contradictory; expanding
			// wins for this initial pass, then accordion resumes for clicks.
			const accordion = this.settings.accordion;
			this.settings.accordion = false;
			this.submenus.forEach((submenu) => this.open(submenu));
			this.settings.accordion = accordion;
			return;
		}

		if (this.settings.autoExpandCurrent) {
			this.submenus
				.filter(
					(submenu) =>
						submenu.classList.contains('current-menu-item') ||
						submenu.classList.contains(
							'current-menu-ancestor'
						) ||
						submenu.querySelector('.current-menu-item')
				)
				.forEach((submenu) => this.open(submenu));
		}
	}
}
