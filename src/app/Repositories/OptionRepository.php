<?php

namespace ResponsiveMenu\Repositories;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class OptionRepository {

  protected static $table = 'responsive_menu';

  public function __construct($db, $factory) {
    $this->db = $db;
    $this->factory = $factory;
  }

  public function all() {
    $options = $this->db->all(self::$table);
    $collection = new OptionsCollection;
    foreach($options as $option)
      $collection->add($this->factory->build($option->name, $option->value));
    return $collection;
  }

  public function update(Option $option) {
    $this->db->update(self::$table,
      ['value' => $option->getFiltered()],
      ['name' => $option->getName()]
    );
  }

  public function create(Option $option) {
    $arguments['name'] = $option->getName();
    $arguments['value'] = $option->getFiltered();
    $arguments['created_at'] = current_time('mysql');
    $this->db->insert(self::$table, $arguments);
  }

  public function remove($name) {
      $this->db->delete(self::$table, $name);
  }

}
