document.addEventListener('DOMContentLoaded', () => {
	const menuTriggers = document.querySelectorAll('.rmp-block-menu-trigger');

	const handleClickOutside = (trigger, controlledElement) => {
		const clickOutsideListener = (event) => {
			const isClickInsideMenu = controlledElement.contains(event.target);
			const isClickOnTrigger = trigger.contains(event.target);

			if (!isClickInsideMenu && !isClickOnTrigger) {
				controlledElement.classList.remove('rmp-block-active');
				trigger.classList.remove('rmp-block-active');
				document.removeEventListener('click', clickOutsideListener);
			}
		};

		document.addEventListener('click', clickOutsideListener);
	};

	menuTriggers.forEach((trigger) => {
		trigger.addEventListener('click', (event) => {
			event.stopPropagation();
			trigger.classList.toggle('rmp-block-active');
			const controlledElementId = trigger.getAttribute('aria-controls');

			if (controlledElementId) {
				const controlledElement = document.getElementById(controlledElementId);

				if (controlledElement) {
					controlledElement.classList.toggle('rmp-block-active');

					if (trigger.getAttribute('data-hide-link-click') === 'true') {
						const menuItems = controlledElement.querySelectorAll('.wp-block-navigation-item > a');
						menuItems.forEach((menuItem) => {
							menuItem.addEventListener('click', () => {
								controlledElement.classList.remove('rmp-block-active');
								trigger.classList.remove('rmp-block-active');
							});
						});
					}

					if (trigger.getAttribute('data-hide-on-scroll') === 'true') {
						const handleScroll = () => {
							controlledElement.classList.remove('rmp-block-active');
							trigger.classList.remove('rmp-block-active');
							window.removeEventListener('scroll', handleScroll);
						};
						window.addEventListener('scroll', handleScroll);
					}

					if (trigger.getAttribute('data-hide-page-click') === 'true') {
						handleClickOutside(trigger, controlledElement);
					}
				}
			}
		});
	});

	// ─── Submenu level classes ────────────────────────────────────────────────
	// Only process direct child submenus to avoid wrong level assignments
	function addSubmenuLevelClasses(parentEl, level) {
		// Find only DIRECT child .wp-block-navigation-submenu items
		const children = parentEl.children;
		for (let i = 0; i < children.length; i++) {
			const child = children[i];
			if (
				child.classList.contains('wp-block-navigation-submenu') ||
				child.classList.contains('wp-block-navigation__submenu-container')
			) {
				const existingLevelClass = Array.from(child.classList).find((cls) =>
					cls.startsWith('rmp-block-submenu-level-')
				);
				if (!existingLevelClass) {
					child.classList.add(`rmp-block-submenu-level-${level}`);
				}
				// Recurse into this submenu's container for deeper nesting
				const nestedContainer = child.querySelector(':scope > .wp-block-navigation__submenu-container');
				if (nestedContainer) {
					addSubmenuLevelClasses(nestedContainer, level + 1);
				}
			}
		}
	}

	const menuItemContainers = document.querySelectorAll('.wp-block-rmp-menu-items');
	menuItemContainers.forEach((container) => addSubmenuLevelClasses(container, 1));

	// ─── Close all children of a submenu ──────────────────────────────────────
	function closeChildSubmenus(parentItem, submenuIconHtml) {
		const openChildren = parentItem.querySelectorAll('.rmp-block-active-submenu');
		openChildren.forEach((child) => {
			child.classList.remove('rmp-block-active-submenu');
			const childArrow = child.querySelector(':scope > .rmp-block-menu-subarrow');
			if (childArrow) {
				childArrow.setAttribute('aria-expanded', 'false');
				// Reset icon to inactive state if we have the original icon
				if (submenuIconHtml) {
					childArrow.innerHTML = submenuIconHtml;
				}
			}
		});
	}

	// ─── Submenu arrow injection and click handling ───────────────────────────
	const parentElements = document.querySelectorAll('.wp-block-rmp-menu-items .wp-block-navigation-submenu');
	parentElements.forEach((parent) => {
		const menuParent = parent.closest('.wp-block-rmp-menu-items');
		const menuContainer = parent.closest('.rmp-block-container');
		let submenuIcon = menuParent.getAttribute('data-submenu-icon');
		let submenuActiveIcon = menuParent.getAttribute('data-submenu-active-icon');
		const submenuIconType = menuParent.getAttribute('data-submenu-icon-type');

		if (submenuIconType === 'icon') {
			const inactiveEl = menuContainer
				? menuContainer.querySelector('.rmp-submenu-trigger-icon .rmp-inactive-submenu-trigger-icon')
				: null;
			const activeEl = menuContainer
				? menuContainer.querySelector('.rmp-submenu-trigger-icon .rmp-active-submenu-trigger-icon')
				: null;
			submenuIcon = inactiveEl ? inactiveEl.innerHTML : submenuIcon;
			submenuActiveIcon = activeEl ? activeEl.innerHTML : submenuActiveIcon;
		} else if (submenuIconType === 'image' && submenuIcon) {
			submenuIcon = `<img src="${submenuIcon}" alt="">`;
			submenuActiveIcon = `<img src="${submenuActiveIcon}" alt="">`;
		}

		const subArrow = document.createElement('button');
		subArrow.className = 'rmp-block-menu-subarrow';
		subArrow.setAttribute('type', 'button');
		subArrow.setAttribute('aria-expanded', 'false');
		subArrow.setAttribute('aria-label', 'Toggle submenu');
		subArrow.innerHTML = submenuIcon;

		const anchorTag = parent.querySelector(':scope > a');
		if (anchorTag) {
			anchorTag.style.flex = '1 1 auto';
			anchorTag.style.minWidth = '0';
			anchorTag.insertAdjacentElement('afterend', subArrow);

			subArrow.addEventListener('click', (event) => {
				event.preventDefault();
				event.stopPropagation();

				const wasActive = parent.classList.contains('rmp-block-active-submenu');
				parent.classList.toggle('rmp-block-active-submenu');
				const isActive = !wasActive;
				subArrow.setAttribute('aria-expanded', isActive ? 'true' : 'false');

				if (isActive) {
					subArrow.innerHTML = submenuActiveIcon;

					// Auto-expand children if configured
					if (menuParent.getAttribute('data-auto-expand-parent') === 'true') {
						const childArrows = parent.querySelectorAll(
							':scope > .wp-block-navigation__submenu-container .wp-block-navigation-submenu:not(.rmp-block-active-submenu) > .rmp-block-menu-subarrow'
						);
						childArrows.forEach((arrow) => arrow.click());
					}

					// Accordion: close sibling submenus at the same level
					if (menuParent.getAttribute('data-use-accordion') === 'true') {
						const parentContainer = parent.parentElement;
						if (parentContainer) {
							const siblings = parentContainer.querySelectorAll(
								':scope > .wp-block-navigation-submenu.rmp-block-active-submenu'
							);
							siblings.forEach((sibling) => {
								if (sibling !== parent) {
									const siblingArrow = sibling.querySelector(':scope > .rmp-block-menu-subarrow');
									if (siblingArrow) siblingArrow.click();
								}
							});
						}
					}
				} else {
					subArrow.innerHTML = submenuIcon;
					// Close all nested children when parent is closed
					closeChildSubmenus(parent, submenuIcon);
				}
			});
		}
	});

	// ─── Click-outside handler for desktop dropdowns ─────────────────────────
	// Single document-level listener instead of one per submenu
	document.addEventListener('click', (event) => {
		const allNavs = document.querySelectorAll('.rmp-desktop-mode');
		allNavs.forEach((nav) => {
			// If click is inside this nav, don't close its submenus
			if (nav.contains(event.target)) return;

			// Close all open submenus in this nav
			const openSubmenus = nav.querySelectorAll('.rmp-block-active-submenu');
			openSubmenus.forEach((item) => {
				item.classList.remove('rmp-block-active-submenu');
				const arrow = item.querySelector(':scope > .rmp-block-menu-subarrow');
				if (arrow) {
					arrow.setAttribute('aria-expanded', 'false');
					// Restore inactive icon
					const menuParent = item.closest('.wp-block-rmp-menu-items');
					let inactiveIcon = menuParent ? menuParent.getAttribute('data-submenu-icon') : null;
					const iconType = menuParent ? menuParent.getAttribute('data-submenu-icon-type') : null;
					if (iconType === 'image' && inactiveIcon) {
						inactiveIcon = `<img src="${inactiveIcon}" alt="">`;
					} else if (iconType === 'icon') {
						const container = item.closest('.rmp-block-container');
						const inactiveEl = container
							? container.querySelector('.rmp-submenu-trigger-icon .rmp-inactive-submenu-trigger-icon')
							: null;
						inactiveIcon = inactiveEl ? inactiveEl.innerHTML : inactiveIcon;
					}
					if (inactiveIcon) arrow.innerHTML = inactiveIcon;
				}
			});
		});
	});

	// ─── Auto-expand settings ────────────────────────────────────────────────
	const menuItems = document.querySelectorAll('.wp-block-rmp-menu-items');
	menuItems.forEach((menu) => {
		if (menu.getAttribute('data-auto-expand') === 'true') {
			const allSubmenus = menu.querySelectorAll('.wp-block-navigation-submenu > .rmp-block-menu-subarrow');
			allSubmenus.forEach((submenu) => submenu.click());
		}

		if (menu.getAttribute('data-auto-expand-current') === 'true') {
			const currentMenu = menu.querySelector('.wp-block-navigation-submenu.current-menu-item > .rmp-block-menu-subarrow');
			if (currentMenu) {
				currentMenu.click();
			}
		}
	});

	// ─── Breakpoint / Desktop mode ───────────────────────────────────────────
	const navBlocks = document.querySelectorAll('.rmp-block-navigator[data-breakpoint]');

	navBlocks.forEach((nav) => {
		const breakpoint = parseInt(nav.getAttribute('data-breakpoint'), 10);
		if (!breakpoint) return;

		const mq = window.matchMedia(`(min-width: ${breakpoint}px)`);

		const applyMode = (isDesktop) => {
			if (isDesktop) {
				nav.classList.add('rmp-desktop-mode');
				const container = nav.querySelector('.rmp-block-container');
				const trigger = nav.querySelector('.rmp-block-menu-trigger');
				if (container) container.classList.remove('rmp-block-active');
				if (trigger) trigger.classList.remove('rmp-block-active');
			} else {
				nav.classList.remove('rmp-desktop-mode');
				nav.querySelectorAll('.rmp-block-active-submenu').forEach((el) => {
					el.classList.remove('rmp-block-active-submenu');
					const arrow = el.querySelector(':scope > .rmp-block-menu-subarrow');
					if (arrow) arrow.setAttribute('aria-expanded', 'false');
				});
			}
		};

		applyMode(mq.matches);
		mq.addEventListener('change', (e) => applyMode(e.matches));
	});
});
