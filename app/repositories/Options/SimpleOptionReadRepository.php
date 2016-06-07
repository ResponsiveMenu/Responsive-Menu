<?php

namespace ResponsiveMenu\Repositories\Options;
use ResponsiveMenu\Models\SimpleOption as SimpleOption;
use ResponsiveMenu\Database\Database as Database;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class SimpleOptionReadRepository extends BaseOptionRepository implements ReadRepository
{
  
	public function all()
	{
    $options = $this->db->all($this->table);
    $collection = new OptionsCollection;
    foreach($options as $option)
      $collection->add(new SimpleOption($option->name, $option->value));
    return $collection;
	}

}
