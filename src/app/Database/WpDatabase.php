<?php

namespace ResponsiveMenu\Database;

class WpDatabase implements Database {

  protected static $table_name = 'responsive_menu';

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = $this->db->prefix . self::$table_name;
  }

  public function update(array $to_update, array $where)
  {
    $this->db->update($this->table, $to_update, $where);
  }

  public function delete($name)
  {
    $this->db->delete($this->table, $name);
  }

  public function all()
  {
    return $this->db->get_results("SELECT * FROM $this->table");
  }

  public function insert(array $arguments)
  {
    $arguments['created_at'] = current_time('mysql');
    $this->db->insert($this->table, $arguments);
  }

  public function select($column, $value)
  {
    return $this->db->get_results("SELECT * FROM $this->table WHERE $column = '$value';");
  }

}
