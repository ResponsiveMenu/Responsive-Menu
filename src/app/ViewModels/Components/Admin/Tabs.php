<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;

class Tabs {

  private $config;

  public function __construct(array $config) {
    $this->config = $config;
  }

  public function render() {
    foreach(array_keys($this->config) as $tab_name) {
      echo '<a id="tab_' . $this->i($tab_name) . '" class="tab page-title-action">' . $tab_name . '</a>';
    }
  }

  public function i($data) {
      return strtolower(str_replace(['/', '_'], '_', $data));
  }

}
