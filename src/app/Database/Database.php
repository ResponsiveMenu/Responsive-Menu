<?php

namespace ResponsiveMenu\Database;

interface Database
{
	public function update(array $to_update, array $where);
	public function delete($name);
	public function all();
	public function insert(array $arguments);
}
