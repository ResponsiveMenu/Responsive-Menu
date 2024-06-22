document.addEventListener('DOMContentLoaded', () => {
    // Select all elements with the class 'rmp-block-menu-trigger'
    const menuTriggers = document.querySelectorAll('.rmp-block-menu-trigger');

    // Loop through each menu trigger and add a click event listener
    menuTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            // Toggle the 'qsm-block-active' class on the clicked element
            trigger.classList.toggle('rmp-block-active');

            // Get the ID of the controlled element from the 'aria-controls' attribute
            const controlledElementId = trigger.getAttribute('aria-controls');

            if (controlledElementId) {
                // Select the controlled element using the ID
                const controlledElement = document.getElementById(controlledElementId);

                if (controlledElement) {
                    // Toggle the 'qsm-block-active' class on the controlled element
                    controlledElement.classList.toggle('qsm-block-active');
                }
            }
        });
    });
});
