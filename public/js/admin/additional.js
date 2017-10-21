/**
    Responsive Menu JS file.
    NOT Safe to Copy

    Do Not Copy
**/

jQuery(function($) {

    /* --> Colour Select Options */
        $.minicolors.defaults = $.extend(
            $.minicolors.defaults, {
                format: 'hex',
                opacity: false,
                theme: 'bootstrap'
            }
        );
        $('.mini-colours').minicolors();
    /* <-- End Colour Select Options */

    /* --> Upgrade Message Overlay */
        $('.pro-row td.col-right, .pro-container').append(
            '<div class="responsive-menu-pro-overlay">' +
            '<a href="https://responsive.menu/pricing/?utm_source=free-plugin' +
            '&utm_medium=option&utm_campaign=free-plugin-option-upgrade"' +
            'target="_blank">Click to upgrade now to use</a>' +
            '</div>'
        );
    /* <-- End Upgrade Message Overlay */

    /* --> Hide Pro Options */
        $(document).on('change', '#hide-pro-options', function() {
            if($(this).is(':checked')) {
                $('.nav > li.pro-tab, ' +
                    '.fully-pro-container, ' +
                    '.responsive-menu-preview, ' +
                    '.pro-row'
                ).hide();
            } else {
                $('.nav > li.pro-tab, ' +
                    '.fully-pro-container, ' +
                    '.responsive-menu-preview, ' +
                    '.pro-row'
                ).show();
            }
        });
    /* <-- End Hide Pro Options  */

    /* --> Preview Options */
        $(document).on('click', '.responsive-menu-preview', function(e) {
            e.preventDefault();
            var form = $('#responsive-menu-form');
            form.attr('action', WP_HOME_URL + '?responsive-menu-preview=true');
            form.attr('target', '_blank');
            form.submit();
            form.attr('action', '');
            form.attr('target', '');
        });
    /* <-- End Preview Options */

});