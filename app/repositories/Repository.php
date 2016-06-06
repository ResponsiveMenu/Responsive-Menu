<?php

namespace ResponsiveMenu\Repositories;
use ResponsiveMenu\Models\Option as Option;
use ResponsiveMenu\Factories\OptionFactory as Factory;

interface Repository
{
	public function all();
	public function update(Option $option);
	public function updateMany(array $options);
}
