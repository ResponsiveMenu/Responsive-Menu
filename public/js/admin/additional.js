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
            form.attr('action', WP_RMP.HOME_URL + '?responsive-menu-preview=true');
            form.attr('target', '_blank');
            form.submit();
            form.attr('action', '');
            form.attr('target', '');
        });
    /* <-- End Preview Options */

     /** Move to new version */
     jQuery('.rmp-upgrade-version').on( 'click', function(e) {
        e.preventDefault();
        jQuery.ajax({
            url: ajaxurl,
            data: { action : 'rmp_switch_version' },
            type: 'POST',
            dataType: 'json',
            error: function( error ) {
                jQuery(this).prop('disabled', false);
            },
            success: function( response ) {
                if ( response.data.redirect ) {
                    location.href = response.data.redirect;    
                }
            }
        });
    });

    /** Call ajax to hide admin notice permanent. */
    $( '.rmp-version-upgrade-notice' ).on( 'click', '.notice-dismiss', function( event ) {
        event.preventDefault();
        jQuery.ajax( {
            type: "POST",
            url:  ajaxurl,
            data: "action=rmp_version_admin_notice_dismiss",
        });
    });

});