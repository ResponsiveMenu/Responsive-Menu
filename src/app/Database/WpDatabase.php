<?php

namespace ResponsiveMenu\Database;

class WpDatabase implements Database {

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
  }

  public function update($table, array $to_update, array $where) {
    $this->db->update($this->db->prefix . $table, $to_update, $where);
  }

  public function delete($table, $name) {
    $this->db->delete($this->db->prefix . $table, $name);
  }

  public function all($table) {
    return $this->db->get_results("SELECT * FROM {$this->db->prefix}{$table}");
  }

  public function insert($table, array $arguments) {
    $arguments['created_at'] = current_time('mysql');
    $this->db->insert($this->db->prefix . $table, $arguments);
  }

  public function select($table, $column, $value) {
    return $this->db->get_results("SELECT * FROM {$this->prexix}{$table} WHERE $column = '$value';");
  }

  public function getPrefix() {
    return $this->db->prefix;
  }

  public function getCharset() {
    return $this->db->get_charset_collate();
  }

}
