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

	function addSubmenuLevelClasses(element, level) {
		if (
			element.classList.contains('wp-block-navigation__submenu-container') &&
			element.classList.contains('wp-block-navigation-submenu')
		) {
			const existingLevelClass = Array.from(element.classList).find((cls) =>
				cls.startsWith('rmp-block-submenu-level-')
			);

			if (!existingLevelClass) {
				element.classList.add(`rmp-block-submenu-level-${level}`);
			}

			level++;
			const childSubmenus = element.querySelectorAll(
				'.wp-block-navigation__submenu-container.wp-block-navigation-submenu'
			);
			childSubmenus.forEach((child) => addSubmenuLevelClasses(child, level));
		}
	}

	const topLevelElements = document.querySelectorAll(
		'.wp-block-rmp-menu-items .wp-block-navigation__submenu-container.wp-block-navigation-submenu'
	);
	topLevelElements.forEach((element) => addSubmenuLevelClasses(element, 1));

	const parentElements = document.querySelectorAll('.wp-block-rmp-menu-items .wp-block-navigation-submenu');
	parentElements.forEach((parent) => {
		const menuParent = parent.closest('.wp-block-rmp-menu-items');
		const menuContainer = parent.closest('.rmp-block-container');
		let submenuIcon = menuParent.getAttribute('data-submenu-icon');
		let submenuActiveIcon = menuParent.getAttribute('data-submenu-active-icon');
		const submenuIconType = menuParent.getAttribute('data-submenu-icon-type');

		if (submenuIconType === 'icon') {
			submenuIcon = menuContainer.querySelector(
				'.rmp-submenu-trigger-icon .rmp-inactive-submenu-trigger-icon'
			).innerHTML;
			submenuActiveIcon = menuContainer.querySelector(
				'.rmp-submenu-trigger-icon .rmp-active-submenu-trigger-icon'
			).innerHTML;
		} else if (submenuIconType === 'image' && submenuIcon) {
			submenuIcon = `<img src="${submenuIcon}">`;
			submenuActiveIcon = `<img src="${submenuActiveIcon}">`;
		}

		const subArrow = document.createElement('div');
		subArrow.className = 'rmp-block-menu-subarrow';
		subArrow.innerHTML = submenuIcon;

		const anchorTag = parent.querySelector(':scope > a');
		if (anchorTag) {
			anchorTag.insertAdjacentElement('afterend', subArrow);

			subArrow.addEventListener('click', () => {
				parent.classList.toggle('rmp-block-active-submenu');
				if (parent.classList.contains('rmp-block-active-submenu')) {
					subArrow.innerHTML = submenuActiveIcon;
					if (menuParent.getAttribute('data-auto-expand-parent') === 'true') {
						const allSubmenus = parent.querySelectorAll('.has-child:not(.rmp-block-active-submenu) .rmp-block-menu-subarrow');
						if (allSubmenus) {
							allSubmenus.forEach((submenu) => {
								submenu.click();
							});
						}
					}
					if (menuParent.getAttribute('data-use-accordion') === 'true') {
						const topLevelSubmenus = menuParent.querySelectorAll(':scope > .wp-block-navigation-item.has-child.rmp-block-active-submenu > .rmp-block-menu-subarrow');
						if (subArrow.closest('.wp-block-navigation__submenu-container') !== null) {
							return;
						}
						topLevelSubmenus.forEach((submenu) => {
							if (submenu !== subArrow ) {
								submenu.click();
							}
						});
					}
				} else {
					subArrow.innerHTML = submenuIcon;
				}
			});
		}
	});

	const menuItems = document.querySelectorAll('.wp-block-rmp-menu-items');

	menuItems.forEach((menu) => {
		if (menu.getAttribute('data-auto-expand') === 'true') {
			const allSubmenus = menu.querySelectorAll('.wp-block-navigation-item.has-child .rmp-block-menu-subarrow');
			allSubmenus.forEach((submenu) => {
				submenu.click();
			});
		}

		if (menu.getAttribute('data-auto-expand-current') === 'true') {
			const currentMenu = menu.querySelector('.has-child.current-menu-item .rmp-block-menu-subarrow');
			if (currentMenu) {
				currentMenu.click();
			}
		}
	});
});
