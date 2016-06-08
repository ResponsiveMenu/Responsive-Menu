<?php

namespace ResponsiveMenu\Repositories\Options;
use ResponsiveMenu\Models\ComplexOption as ComplexOption;

interface Repository {
  public function all();
  public function update(ComplexOption $option);
}
