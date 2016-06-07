<?php

namespace ResponsiveMenu\Repositories\Options;
use ResponsiveMenu\Database\Database as Database;

class BaseOptionRepository {

  const TABLE = 'responsive_menu';

	public function __construct(Database $db)
	{
		$this->db = $db;
		$this->table = $this->db->getPrefix() . self::TABLE;
	}

}
