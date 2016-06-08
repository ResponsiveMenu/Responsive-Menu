<?php

namespace ResponsiveMenu\Repositories\Options;
use ResponsiveMenu\Database\Database as Database;

class BaseOptionRepository {

	public function __construct(Database $db)
	{
		$this->db = $db;
	}

}
