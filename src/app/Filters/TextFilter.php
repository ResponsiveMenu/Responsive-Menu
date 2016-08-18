<?php

namespace ResponsiveMenu\Filters;

class TextFilter implements Filter {

  public function filter($data) {
		return strip_tags($data);
	}

}
