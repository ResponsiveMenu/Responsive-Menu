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

    $(document).on('click', '#responsive-menu-preview', function(e) {
        e.preventDefault();
        var form = $('#responsive-menu-form');
        form.attr('action', WP_HOME_URL + '?responsive-menu-preview=true');
        form.attr('target', '_blank');
        form.submit();
        form.attr('action', '');
        form.attr('target', '');
    });

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

    $(document).on('click', '.nav-tabs li a', function() {
        var tab_name = $(this).attr('href').replace('#', '');
        $('#responsive-menu-current-page').val(tab_name);
    });

    $(document).on('keyup', '#filter-options', function() {
        var search_query = $(this).val();
        var current_tab = $('.nav-tabs .active').attr('id');
        $('#options-area').css('width', '99%');
        $('.nav-tabs, #banner-area').hide();

        if(search_query) {
           $('.tab-pane').show().css('opacity', '1');
           $('.panel-body small').css('display', 'block');

           $('.control-label').closest('tr').hide();
           $('.control-label').each(function (i) {
               if ($(this).text().toLowerCase().indexOf(search_query.toLowerCase()) >= 0) {
                   $(this).closest('tr').show();
               }
           });

           $('#options-area .table-bordered').each(function(i) {

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
            $('.nav-tabs, #banner-area, .panel').show();
            $('#options-area').css('width', '');
            $('.panel-body small').css('display', '');
        }
    });

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

});