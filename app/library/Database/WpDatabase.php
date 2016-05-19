<?php

namespace ResponsiveMenu\Database;

class WpDatabase implements Database
{
	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
	}

	public function update($table, array $to_update, array $where)
	{
		$this->db->update( $table, $to_update, $where );
	}

	public function delete($table, $name)
	{
    $this->db->delete($table, $name);
	}

	public function all($table)
	{
		return $this->db->get_results("SELECT * FROM $table");
	}

	public function insert($table, array $arguments)
	{
		$arguments['created_at'] = current_time('mysql');
		$this->db->insert( $table, $arguments );
	}

	public function insertIfNotExists($table, array $arguments)
	{
		if(!$this->select($table, key($arguments), $arguments[key($arguments)])) $this->insert($table, $arguments);
	}

	public function select($table, $column, $value)
	{
		return $this->db->get_results( "SELECT * FROM $table WHERE $column = '$value';" );
	}

	public function getPrefix()
	{
		return $this->db->prefix;
	}

	public function getCharset()
	{
		return $this->db->get_charset_collate();
	}
}
