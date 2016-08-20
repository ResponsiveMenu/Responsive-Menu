<?php

namespace ResponsiveMenu\Database;

class WpDatabase implements Database {

  public function __construct($wpdb) {
    $this->db = $wpdb;
  }

  public function update($table, array $to_update, array $where) {
    return $this->db->update($this->db->prefix . $table, $to_update, $where);
  }

  public function delete($table, $name) {
    return $this->db->delete($this->db->prefix . $table, $name);
  }

  public function all($table) {
    return $this->db->get_results("SELECT * FROM {$this->db->prefix}{$table}");
  }

  public function insert($table, array $arguments) {
    $arguments['created_at'] = current_time('mysql');
    return $this->db->insert($this->db->prefix . $table, $arguments);
  }

  public function select($table, $column, $value) {
    return $this->db->get_results("SELECT * FROM {$this->db->prefix}{$table} WHERE $column = '$value';");
  }

  public function mySqlTime() {
    return current_time('mysql');
  }

  public function updateOption($key, $value) {
    return update_option($key, $value);
  }

  public function createTable($table) {
    $sql = "CREATE TABLE " . $this->db->prefix . $table . " (
              name varchar(50) NOT NULL,
              value varchar(5000) DEFAULT NULL,
              created_at datetime NOT NULL,
              updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY  (name)
            ) " . $this->db->get_charset_collate() . ";";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
  }

}
