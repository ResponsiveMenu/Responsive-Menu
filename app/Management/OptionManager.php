<?php

namespace ResponsiveMenuTest\Management;
use ResponsiveMenuTest\Database\Database;
use ResponsiveMenuTest\Tasks\UpdateOptionsTask;

class OptionManager {

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function all() {
        return $this->db->all('responsive_menu_test');
    }

    public function updateOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->update('responsive_menu_test', ['value' => stripslashes($val)], ['name' => $name]);
        return $options;
    }

    public function createOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->insert('responsive_menu_test', ['name' => $name, 'value' => stripslashes($val)]);
        return $options;
    }

    public function removeOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->delete('responsive_menu_test', ['name' => $name]);
        return $options;
    }

}
