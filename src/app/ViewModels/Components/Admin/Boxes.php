<?php

namespace ResponsiveMenu\ViewModels\Components\Admin;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Form;

class Boxes {

  private $config;

  public function __construct(array $config, OptionsCollection $options) {
    $this->config = $config;
    $this->options = $options;
  }

  public function render() {
    $output = '';
    foreach($this->config as $tab_name => $sub_menus):
      $output .= '<div class="tab_container" id="tab_container_' . $this->i($tab_name) . '">';
        foreach($sub_menus as $sub_menu_name => $options):
          $output .= '
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
              $output .= '<tr class="' . $pro . ' ' . $semi_pro . '" id="' . $option['option'] . '_container">
                      <td>
                        <div class="label">' . $option['title'] . '</div>
                        <span class="description">' . $option['label'] . '</span>
                      </td>
                      <td>';
                      $output .= $this->f($type, $option['option'], $select);
                $output .= $unit . '</td>
                    </tr>';
                endforeach;
        $output .= '</table>
            </div> <!-- .inside -->
          </div> <!-- .postbox -->';
        endforeach;
      $output .= '</div> <!-- .tab_container -->';
    endforeach;
    return $output;
  }

  public function i($data) {
      return strtolower(str_replace([' ', '/'], '_', $data));
  }

  public function f($type, $option_name, $select) {
    switch($type):
      case 'checkbox' : $comp = new Form\Checkbox;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'colour' : $comp = new Form\Colour;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'textarea' : $comp = new Form\TextArea;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'select' : $comp = new Form\Select;
                        return $comp->render($this->options[$option_name], $select);
                        break;
      case 'image' : $comp = new Form\Image;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'menu_ordering' : $comp = new Form\MenuOrdering;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'header_ordering' : $comp = new Form\HeaderBarOrdering;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'fonticons' : $comp = new Form\FontIconPageList;
                        return $comp->render($this->options[$option_name]);
                        break;
      case 'import' : $comp = new Form\Import;
                        return $comp->render();
                        break;
      case 'export' : $comp = new Form\Export;
                        return $comp->render();
                        break;
      case 'reset' : $comp = new Form\Reset;
                        return $comp->render();
                        break;
      default : $comp = new Form\Text;
                        return $comp->render($this->options[$option_name]);
                        break;
    endswitch;

  }

}
