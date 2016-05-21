jQuery(function($) {

  $('.wp-color-picker').wpColorPicker();
  $('#banner_area').stick_in_parent();

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
		container_name = '#tab_container_' + $(this).attr('id').replace('tab_', '');
		$('.tab_container').css('display', 'none');
		$(container_name).css('display', 'block');
    $('.tab').removeClass('active_tab');
    $(this).addClass('active_tab');
	});
});
