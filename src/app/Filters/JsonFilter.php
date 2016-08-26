<?php

namespace ResponsiveMenu\Filters;

class JsonFilter implements Filter {

	public function filter($data) {
		return is_string($data) ? $data : json_encode($data);
	}

}
