<?php

namespace ResponsiveMenu\Form;

class Import {

	public function render() {
		return '<input type="file" name="responsive_menu_import_file" /><input type="submit" class="button submit" name="responsive_menu_import" value="' . __('Import Options', 'responsive-menu') . '" />';
	}

}
