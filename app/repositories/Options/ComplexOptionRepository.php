<?php

namespace ResponsiveMenu\Repositories\Options;
use ResponsiveMenu\Factories\OptionFactory as Factory;
use ResponsiveMenu\Models\ComplexOption as ComplexOption;
use ResponsiveMenu\Database\Database as Database;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class ComplexOptionRepository extends BaseOptionRepository implements Repository
{

	public function update(ComplexOption $option)
	{
		$this->db->update($this->table, array('value' => $option->getFiltered()), array('name' => $option->getName()));
	}

	public function all()
	{
    $options = $this->db->all($this->table);
    $collection = new OptionsCollection;
    $factory = new Factory;
    foreach($options as $option)
      $collection->add($factory->build($option->name, $option->value));
    return $collection;
	}

}
