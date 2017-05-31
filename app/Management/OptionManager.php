<?php

namespace ResponsiveMenu\Management;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Database\Database;

class OptionManager {

    private $db;
    private $default_options;

    public function __construct(Database $db, $default_options) {
        $this->db = $db;
        $this->default_options = $default_options;
    }

    public function all() {
        $options = $this->db->all('responsive_menu');
        return new OptionsCollection($options);
    }

    public function updateOptions(array $options) {
        $updated_options = $this->combineOptions($options);
        foreach($updated_options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $updated_options[$name] = $val;
            $this->db->update('responsive_menu', ['name' => $name, 'value' => $val]);
        endforeach;
        return new OptionsCollection($updated_options);
    }

    public function createOptions(array $options) {
        $updated_options = $this->combineOptions($options);
        foreach($options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $updated_options[$name] = $val;
            $this->db->insert('responsive_menu', ['name' => $name, 'value' => $val]);
        endforeach;
        return new OptionsCollection($updated_options);
    }

    public function removeOptions(array $options) {
        $updated_options = $this->combineOptions($options);
        foreach($options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $updated_options[$name] = $val;
            unset($updated_options[$name]);
            $this->db->delete('responsive_menu', ['name' => $name]);
        endforeach;
        return new OptionsCollection($updated_options);
    }

    public function buildFromArray(array $options) {
        $new_options = $this->combineOptions($options);
        foreach($options as $name => $val):
            $val = is_array($val) ? json_encode($val) : $val;
            $new_options[$name] = $val;
        endforeach;
        return new OptionsCollection($new_options);
    }

    private function combineOptions($new_options) {
        return array_merge($this->default_options, $new_options);
    }

}