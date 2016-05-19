<?php

namespace ResponsiveMenu\Repositories;

interface Repository
{
	public function all();
	public function update($name,$value);
	public function updateMany(array $options);
}
