<?php

namespace ResponsiveMenu\Form;

class Reset {

	public function render() {
    return '<input type="submit" class="button submit" name="responsive_menu_reset" value="' . __('Reset Options', 'responsive-menu') . '" />';
	}

}
