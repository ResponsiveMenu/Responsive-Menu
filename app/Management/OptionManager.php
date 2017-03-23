<?php

namespace ResponsiveMenuTest\Management;
use ResponsiveMenuTest\Database\Database;

class OptionManager {

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function all() {
        return $this->db->all('responsive_menu_test');
    }

    public function updateOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->update('responsive_menu_test', ['value' => $val], ['name' => $name]);
    }

    public function createOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->insert('responsive_menu_test', ['name' => $name, 'value' => $val]);
    }

    public function removeOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->delete('responsive_menu_test', ['name' => $name]);
    }

}
