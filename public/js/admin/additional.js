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