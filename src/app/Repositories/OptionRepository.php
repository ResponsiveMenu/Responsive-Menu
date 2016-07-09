<?php

namespace ResponsiveMenu\Repositories;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class OptionRepository {

  protected static $table_name = 'responsive_menu';

  public function __construct($db, $factory) {
    $this->db = $db;
    $this->table = $this->db->prefix . self::$table_name;
    $this->factory = $factory;
  }

  public function all() {
    $options = $this->db->get_results("SELECT * FROM $this->table");
    $collection = new OptionsCollection;
    foreach($options as $option)
      $collection->add($this->factory->build($option->name, $option->value));
    return $collection;
  }

  public function update(Option $option) {
    $this->db->update($this->table, ['value' => $option->getFiltered()], ['name' => $option->getName()]);
  }

  public function create($name, $value) {

    $arguments['name'] = $name;
    $arguments['value'] = $value;
    $arguments['created_at'] = current_time('mysql');
    var_dump($arguments);
    $this->db->insert($this->table, $arguments);
  }

  public function remove($name) {
      $this->db->delete($this->table, $name);
  }

  public function getTableCharset() {
    return $this->db->get_charset_collate();
  }

  public function getTable() {
    return $this->table;
  }

}
