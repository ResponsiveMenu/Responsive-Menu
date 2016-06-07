<?php

namespace ResponsiveMenu\Filters;

class JsonFilter implements Filter
{
	public function filter($data)
	{
		return is_string($data) ? json_decode($data) : json_encode($data);
	}

}
