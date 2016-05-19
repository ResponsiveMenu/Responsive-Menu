<?php

namespace ResponsiveMenu\Repositories;
use ResponsiveMenu\Factories\OptionFactory as Factory;
use ResponsiveMenu\Database\Database as Database;

class OptionRepository implements Repository
{

	private $db;
	private $table;

	public function __construct(Database $db)
	{
		$this->db = $db;
		$this->table = $this->db->getPrefix() . "responsive_menu";
	}

	public function update($name, $value)
	{
		$this->db->update($this->table, array( 'value' => $value ), array( 'name' => $name ) );
	}


	public function updateMany(array $options)
	{
		foreach($options as $name => $value)
      $this->update($name,$value);
	}

	public function all()
	{
		foreach($this->db->all($this->table) as $key)
      $saved[$key->name] = Factory::build($key);
		return $saved;
	}

}
