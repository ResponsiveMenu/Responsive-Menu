<?php

namespace ResponsiveMenuTest\Management;
use ResponsiveMenuTest\Collections\OptionsCollection;
use ResponsiveMenuTest\Database\Database;

class OptionManager {

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function all() {
        $options = $this->db->all('responsive_menu_test');
        return new OptionsCollection($options);
    }

    public function updateOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->update('responsive_menu_test', ['value' => stripslashes($val)], ['name' => $name]);
        return new OptionsCollection($options);
    }

    public function createOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->insert('responsive_menu_test', ['name' => $name, 'value' => stripslashes($val)]);
        return new OptionsCollection($options);
    }

    public function removeOptions(array $options) {
        foreach($options as $name => $val)
            $this->db->delete('responsive_menu_test', ['name' => $name]);
        return new OptionsCollection($options);
    }

}
