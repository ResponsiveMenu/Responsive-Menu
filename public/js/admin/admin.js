jQuery(function($) {

    $.minicolors.defaults = $.extend($.minicolors.defaults, {
        format: 'hex',
        opacity: false,
        theme: 'bootstrap'
    });
    $('.mini-colours').minicolors();

    $(':file').filestyle({icon: false, buttonName: 'btn-primary', placeholder: 'No file'});
    
    var custom_uploader;

    $('.image_button').click(function (e) {
        e.preventDefault();
        window.imgFor = $(this).attr('for');
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image',
                id: 'test'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#' + window.imgFor).val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();
    });

    $(document).on('click', '#responsive_menu_preview', function(e) {
        e.preventDefault();
        $('#responsive_menu_form').attr('action', '/?responsive-menu-preview=true');
        $('#responsive_menu_form').attr('target', '_blank');
        $('#responsive_menu_form').submit();
        $('#responsive_menu_form').attr('action', '');
        $('#responsive_menu_form').attr('target', '');
    });

    $(document).on('click', '.validation-error', function(e) {
        e.preventDefault();
        var id_to_scroll_to = $(this).attr('href');
        var parent_panel_id = $(id_to_scroll_to).parents('.tab-pane').attr('id');
        var parent_tab = $('a[href="#' + parent_panel_id + '"]').parent('li');

        $('ul.nav-tabs li').removeClass('active');
        parent_tab.addClass('active');

        $('.tab-content .tab-pane').removeClass('active').removeClass('in');

        $('#' + parent_panel_id).addClass('active');
        $('#' + parent_panel_id).addClass('in');

        $('html, body').animate({
            scrollTop: $(id_to_scroll_to).offset().top - 50
        }, 1000);
    });

});
