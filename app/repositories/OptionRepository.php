<?php

namespace ResponsiveMenu\Repositories;
use ResponsiveMenu\Factories\OptionFactory as Factory;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Database\Database as Database;

class OptionRepository implements Repository
{

	private $db;
	private $table;
  const TABLE = 'responsive_menu';

	public function __construct(Database $db, Factory $factory)
	{
		$this->db = $db;
    $this->factory = $factory;
		$this->table = $this->db->getPrefix() . self::TABLE;
	}

	public function update(Option $option)
	{
		$this->db->update($this->table, array('value' => $option->getValue()), array('name' => $option->getName()));
	}

	public function updateMany(array $options)
	{
		foreach($options as $name => $value)
      $this->update($name,$value);
	}

	public function all()
	{
    $options = $this->db->all($this->table);
    $all_options = [];
    foreach($options as $option)
      $all_options[$option->name] = $this->factory->build($option->name, $option->value);
    return $all_options;
	}

}
