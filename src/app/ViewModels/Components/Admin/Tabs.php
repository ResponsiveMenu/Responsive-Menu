<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;

class Tabs {

  private $config;

  public function __construct(array $config) {
    $this->config = $config;
  }

  public function render() {
    $i=0;
    foreach(array_keys($this->config) as $tab_name) {
      $active_class = $i == 0 ? ' active_tab' : '';
      echo '<a id="tab_' . $this->i($tab_name) . '" class="tab page-title-action' . $active_class . '">' . $tab_name . '</a>';
      $i++;
    }
  }

  public function i($data) {
      return strtolower(str_replace([' ', '/'], '_', $data));
  }

}
