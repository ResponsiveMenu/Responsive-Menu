document.addEventListener('DOMContentLoaded', () => {
    // Select all elements with the class 'rmp-block-menu-trigger'
    const menuTriggers = document.querySelectorAll('.rmp-block-menu-trigger');

    // Loop through each menu trigger and add a click event listener
    menuTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            trigger.classList.toggle('rmp-block-active');
            // Get the ID of the controlled element from the 'aria-controls' attribute
            const controlledElementId = trigger.getAttribute('aria-controls');

            if (controlledElementId) {
                // Select the controlled element using the ID
                const controlledElement = document.getElementById(controlledElementId);

                if (controlledElement) {
                    controlledElement.classList.toggle('rmp-block-active');

                    // Hide on click
                    if (trigger.getAttribute('data-hide-on-click') === 'true') {
                        const menuItems = controlledElement.querySelectorAll('.wp-block-navigation-item > a');
                        menuItems.forEach(menuItem => {
                            menuItem.addEventListener('click', () => {
                                controlledElement.classList.remove('rmp-block-active');
                                trigger.classList.remove('rmp-block-active');
                            });
                        });
                    }

                    // Hide on scroll
                    if (trigger.getAttribute('data-hide-on-scroll') === 'true') {
                        const handleScroll = () => {
                            controlledElement.classList.remove('rmp-block-active');
                            trigger.classList.remove('rmp-block-active');
                            window.removeEventListener('scroll', handleScroll);
                        };
                        window.addEventListener('scroll', handleScroll);
                    }
                }
            }
        });
    });

    const parentElements = document.querySelectorAll('.wp-block-rmp-menu-items .wp-block-navigation-submenu');

    parentElements.forEach(parent => {
        // Retrieve submenu icon text from parent data attributes
        const menuParent = parent.closest('.wp-block-rmp-menu-items');
        const submenuIcon = menuParent.getAttribute('data-submenu-icon');
        const submenuActiveIcon = menuParent.getAttribute('data-submenu-active-icon');

        // Create the new element
        const subArrow = document.createElement('div');
        subArrow.className = 'rmp-block-menu-subarrow';
        subArrow.textContent = submenuIcon;

        // Find the first-level anchor tag within the parent element
        const anchorTag = parent.querySelector(':scope > a');

        if (anchorTag) {
            // Append the new element after the anchor tag
            anchorTag.insertAdjacentElement('afterend', subArrow);

            // Add click event listener to the new subArrow element
            subArrow.addEventListener('click', () => {
                parent.classList.toggle('rmp-block-active-submenu');
                if (parent.classList.contains('rmp-block-active-submenu')) {
                    subArrow.textContent = submenuActiveIcon;
                } else {
                    subArrow.textContent = submenuIcon;
                }
            });
        }
    });
});
