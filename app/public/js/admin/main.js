jQuery(function($) {
	$(document).on('click', '.tab', function() {
		container_name = '#tab_container_' + $(this).attr('id').replace('tab_', '');
		$('.tab_container').css('display', 'none');
		$(container_name).css('display', 'block');
	});
});