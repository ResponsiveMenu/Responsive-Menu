<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;
use ResponsiveMenu\Form as Form;

class Boxes {

  private $config;

  public function __construct(array $config, OptionsCollection $options) {
    $this->config = $config;
    $this->options = $options;
  }

  public function render() {

    foreach($this->config as $tab_name => $sub_menus):
      echo '<div class="tab_container" id="tab_container_' . $this->i($tab_name) . '">';
        foreach($sub_menus as $sub_menu_name => $options):
          echo '
          <div class="postbox" id="postbox_' . $this->i($sub_menu_name).'">
            <div class="handlediv">
              <button aria-expanded="true" class="button-link" type="button">
                <span class="screen-reader-text">' . __('Toggle panel: Location', 'responsive-menu') . '</span>
                <span aria-hidden="true" class="toggle-indicator"></span>
              </button>
            </div> <!-- .handlediv -->
            <h2 class="ui-sortable-handle hndle">' . $tab_name . ' &raquo; ' . $sub_menu_name . '</h2>
            <div class="inside">
              <table class="widefat">';
                foreach($options as $option):
                  $pro = isset($option['pro']) ? 'pro_option' : '';
                  $semi_pro = isset($option['semi_pro'])  ? 'semi_pro_option' : '';
                  $type = isset($option['type']) ? $option['type'] : null;
                  $unit = isset($option['unit']) ? '<span class="units">' . $option['unit'] . '</span>' : null;
                  $select = isset($option['select']) ? $option['select'] : null;
              echo '<tr class="' . $pro . ' ' . $semi_pro . '" id="' . $option['option'] . '_container">
                      <td>
                        <div class="label">' . $option['title'] . '</div>
                        <span class="description">' . $option['label'] . '</span>
                      </td>
                      <td>';
                        $this->f($type, $option['option'], $select);
                echo $unit . '</td>
                    </tr>';
                endforeach;
        echo '</table>
            </div> <!-- .inside -->
          </div> <!-- .postbox -->';
        endforeach;
      echo '</div> <!-- .tab_container -->';
    endforeach;
  }

  public function i($data) {
      return strtolower(str_replace([' ', '/'], '_', $data));
  }

  public function f($type, $option_name, $select) {
    switch($type):
      case 'checkbox' : $comp = new Form\Checkbox;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'colour' : $comp = new Form\Colour;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'textarea' : $comp = new Form\TextArea;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'select' : $comp = new Form\Select;
                        $comp->render($this->options[$option_name], $select);
                        break;
      case 'image' : $comp = new Form\Image;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'menu_ordering' : $comp = new Form\MenuOrdering;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'header_ordering' : $comp = new Form\HeaderBarOrdering;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'fonticons' : $comp = new Form\FontIconPageList;
                        $comp->render($this->options[$option_name]);
                        break;
      case 'import' : $comp = new Form\Import;
                        $comp->render();
                        break;
      case 'export' : $comp = new Form\Export;
                        $comp->render();
                        break;
      case 'reset' : $comp = new Form\Reset;
                        $comp->render();
                        break;
      default : $comp = new Form\Text;
                        $comp->render($this->options[$option_name]);
                        break;
    endswitch;

  }

}
