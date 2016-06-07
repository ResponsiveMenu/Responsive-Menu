<?php

namespace ResponsiveMenu\Repositories\Options;
use ResponsiveMenu\Models\ComplexOption as ComplexOption;
use ResponsiveMenu\Factories\OptionFactory as Factory;

interface Repository
{
	public function all();
	public function update(ComplexOption $option);
}
