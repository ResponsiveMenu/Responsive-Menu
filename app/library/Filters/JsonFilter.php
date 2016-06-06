<?php

namespace ResponsiveMenu\Filters;

class JsonFilter implements Filter
{
	public function filter($data)
	{
		return json_encode($data);
	}

}
