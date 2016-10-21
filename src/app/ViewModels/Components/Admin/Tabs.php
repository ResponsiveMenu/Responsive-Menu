<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;

class Tabs {

  private $config;
  private $current_tab;

  public function __construct(array $config, $current_tab) {
    $this->config = $config;
    $this->current_tab = $current_tab;
  }

  public function render() {
    $output = '';
    foreach(array_keys($this->config) as $tab_name) {
      $active_class = $this->i($tab_name) == $this->current_tab ? ' active_tab' : '';
      $output .= '<a id="tab_' . $this->i($tab_name) . '" class="tab page-title-action' . $active_class . '">' . $tab_name . '</a>';
    }
    return $output;
  }

  public function i($data) {
      return strtolower(str_replace([' ', '/'], '_', $data));
  }

}
