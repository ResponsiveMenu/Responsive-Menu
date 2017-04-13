<?php

namespace ResponsiveMenu\Database;

class Database {

    public function __construct($db_base) {
        $this->db = $db_base;
    }

    public function all($table) {
        $results = $this->db->get_results("SELECT * FROM {$this->db->prefix}{$table}", ARRAY_A);
        $flattened = [];
        foreach($results as $result)
            $flattened[$result['name']] = $result['value'];
        return $flattened;
    }

    public function update($table, array $to_update, array $where) {
        return $this->db->update($this->db->prefix . $table, $to_update, $where);
    }

    public function delete($table, $name) {
        return $this->db->delete($this->db->prefix . $table, $name);
    }

    public function insert($table, array $arguments) {
        return $this->db->insert($this->db->prefix . $table, $arguments);
    }

}
