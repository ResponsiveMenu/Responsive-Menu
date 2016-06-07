<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option as Option;

interface FormComponent {
  public function render(Option $option);
}
