<?php

namespace ResponsiveMenu\ViewModels\Components;

use ResponsiveMenu\Collections\OptionsCollection;

interface ViewComponent {

  public function render(OptionsCollection $collection);

}
