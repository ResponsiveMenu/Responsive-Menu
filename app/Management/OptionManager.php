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
        $updated_options = [];
        foreach($options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $val = stripslashes($val);
            $updated_options[$name] = $val;
            $this->db->update('responsive_menu_test', ['value' => $val], ['name' => $name]);
        endforeach;
        return new OptionsCollection($updated_options);
    }

    public function createOptions(array $options) {
        $updated_options = [];
        foreach($options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $val = stripslashes($val);
            $updated_options[$name] = $val;
            $this->db->insert('responsive_menu_test', ['name' => $name, 'value' => $val]);
        endforeach;
        return new OptionsCollection($updated_options);
    }

    public function removeOptions(array $options) {
        $updated_options = $options;
        foreach($options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $val = stripslashes($val);
            $updated_options[$name] = $val;
            unset($updated_options[$name]);
            $this->db->delete('responsive_menu_test', ['name' => $name]);
        endforeach;
        return new OptionsCollection($updated_options);
    }

}
