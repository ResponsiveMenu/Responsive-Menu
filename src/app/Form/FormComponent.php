<?php

namespace ResponsiveMenu\Form;
use ResponsiveMenu\Models\Option;

interface FormComponent {
  public function render(Option $option);
}
