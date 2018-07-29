/**
    Responsive Menu JS file.
    Safe to Copy
**/

jQuery(function($) {

    /* --> File Upload Options */
        $(':file').filestyle({
            icon: false,
            buttonName: 'btn btn-primary btn-rm',
            placeholder: 'No file'
        });
    /* <-- End File Upload Options */

    /* --> Image Upload Options */
        var custom_uploader;

        $(document).on('click', '.image_button', function(e) {
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
    /* <-- End Image Upload Options */

    /* --> Scroll to Option */
        $(document).on('click', '.scroll-to-option', function(e) {
            e.preventDefault();
            var id_to_scroll_to = $(this).attr('href');
            var parent_panel_id = $(id_to_scroll_to).parents('.tab-pane').attr('id');
            var parent_tab = $('a[href="#' + parent_panel_id + '"]').parent('li');

            $('tr').removeClass('option-highlight');
            $('ul.nav-tabs li').removeClass('active');
            parent_tab.addClass('active');

            $('#responsive-menu-current-page').val(parent_panel_id);

            $('.tab-content .tab-pane').removeClass('active').removeClass('in');

            $('#' + parent_panel_id).addClass('active').addClass('in');

            $('html, body').animate({
                scrollTop: $(id_to_scroll_to).offset().top - 50
            }, 1000);

            $(id_to_scroll_to).closest('tr').addClass('option-highlight');
        });
    /* <-- End Scroll to Option */

    /* --> Filter Options */
        $(document).on('keyup', '#filter-options', function() {
            var search_query = $(this).val();
            $('#options-area').css('width', '99%');
            $('.nav-tabs, #banner-area, .top-submit-buttons').hide();

            if(search_query) {
                $('.tab-pane').show().css('opacity', '1');
                $('.panel-body small').css('display', 'block');
                $('#responsive-menu-desktop-menu-container').parent('.panel').hide();

                $('.control-label').closest('tr').hide();
                $('.control-label').each(function() {
                    if ($(this).text().toLowerCase().indexOf(search_query.toLowerCase()) >= 0) {
                        $(this).closest('tr').show();
                    }
                });

                $('#options-area .table-bordered').each(function() {

                    var visible_rows = $(this).children('tbody').children('tr').filter(function() {
                        return $(this).css('display') == 'table-row';
                    });

                    if(visible_rows.length > 0) {
                        $(this).parents('.panel').show();
                    } else {
                        $(this).parents('.panel').hide();
                    }
                });

            } else {
                $('.tab-pane').css('display', '').css('opacity', '');
                $('.control-label').closest('tr').show();
                $('.nav-tabs, #banner-area, .panel, .top-submit-buttons').show();
                $('#options-area').css('width', '');
                $('.panel-body small').css('display', '');
            }
        });
    /* <-- End Filter Options */

    /* --> Navigation Tabs */
        $(document).on('click', '.nav-tabs li a', function() {
            var tab_name = $(this).attr('href').replace('#', '');
            $('#responsive-menu-current-page').val(tab_name);
        });
    /* <-- End Navigation Tabs */

    /* --> Trigger Types */
        $('#responsive-menu-button-trigger-type').selectize({
            plugins: ['remove_button'],
            options: [
                {
                    value:'click',
                    text:'Click'
                },
                {
                    value:'mouseover',
                    text:'Hover'
                }
            ]
        });
    /* <-- End Trigger Types */

    /* --> Keyboard Shortcuts */
        $('.keyboard-shortcuts').selectize({
            plugins: ['remove_button'],
            options: [
                {
                    value:27,
                    text:'Esc'
                },
                {
                    value:13,
                    text:'Enter'
                },
                {
                    value:32,
                    text:'Space'
                },
                {
                    value:37,
                    text:'Left'
                },
                {
                    value:38,
                    text:'Up'
                },
                {
                    value:39,
                    text:'Right'
                },
                {
                    value:40,
                    text:'Down'
                }
            ]
        });
    /* <-- End Keyboard Shortcuts */

    /* --> Menu Order Scripts */
        $(document).on('click', '.menu-order-option-switch', function() {
            var siblings = $(this).siblings('input.orderable-item');
            if(siblings.val() != 'on') {
                siblings.val('on');
                $(this).addClass('menu-order-option-switch-on');
            } else {
                siblings.val('');
                $(this).removeClass('menu-order-option-switch-on');
            }
        });

        $('#menu-sortable').sortable({
            revert: true,
            placeholder: 'dashed-placeholder'
        });

        $('#sortable, .draggable').disableSelection();
    /* <-- End Menu Order Scripts */

    /* --> Theme Selector Script */
        $('#responsive-menu-menu-theme').on('changed.bs.select', function() {
            var selected_theme_key = $(this).val();
            var preview_image_url = THEMES_FOLDER_URL + selected_theme_key + '/preview.png';
            var $preview_image = $('#responsive-menu-theme-preview');

            $preview_image.attr('src', preview_image_url);
        });
    /* <-- End Theme Selector Script */
});
