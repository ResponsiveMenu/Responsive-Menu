jQuery(function($) {

  $('.wp-color-picker').wpColorPicker();

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

	$(document).on('click', '.tab', function() {
    tab_name = $(this).attr('id').replace('tab_', '');
    container_name = '#tab_container_' + tab_name;
    $('#responsive_menu_current_page').val(tab_name);
		$('.tab_container').css('display', 'none');
		$(container_name).css('display', 'block');
    $('.tab').removeClass('active_tab');
    $(this).addClass('active_tab');
	});

    $(document).on('click', '#responsive_menu_preview', function(e) {
      e.preventDefault();
      $('#responsive_menu_form').attr('action', '/?responsive-menu-preview=true');
      $('#responsive_menu_form').attr('target', '_blank');
      $('#responsive_menu_form').submit();
      $('#responsive_menu_form').attr('action', '');
      $('#responsive_menu_form').attr('target', '');
    });

});
