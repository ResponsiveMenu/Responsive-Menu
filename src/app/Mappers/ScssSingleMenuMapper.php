<?php

namespace ResponsiveMenu\Mappers;

class ScssSingleMenuMapper extends ScssMapper
{

  public function map()
  {

    $css = <<<CSS

      @media screen and (min-width: {$this->options['breakpoint']}px) {
        #responsive-menu-container {
          display: block;
          transform: 0;
          & div {
            display: none;
          }
          & #responsive-menu-wrapper,
          & #responsive-menu {
            display: block;
          }
        }
        #responsive-menu {
          list-style-type: none;
          li {
            box-sizing: border-box;
            display: inline-block;
            position: relative;
            white-space: nowrap;
            margin: 0;
            a {
              line-height: {$this->options['single_menu_height']}px;
              color: {$this->options['single_menu_item_link_colour']};
              background: {$this->options['single_menu_item_background_colour']};
              text-decoration: none;
              transition: color, background {$this->options['transition_speed']}s;
              display: block;
              padding: 0 15px;
              font-size: {$this->options['single_menu_font_size']}px;
              @if '{$this->options['single_menu_font']}' != '' {
                font-family: '{$this->options['single_menu_font']}';
              }
              &:hover {
                color: {$this->options['single_menu_item_link_colour_hover']};
                background: {$this->options['single_menu_item_background_colour_hover']};
              }
            }
            .responsive-menu-submenu {
              display: none;
              position: absolute;
              margin: 0;
              top: {$this->options['single_menu_height']}px;
              li {
                display: block;
                width: 100%;
                a {
                  background: {$this->options['single_menu_item_submenu_background_colour']};
                  color: {$this->options['single_menu_item_submenu_link_colour']};
                  font-size: {$this->options['single_menu_submenu_height']};
                  line-height: {$this->options['single_menu_submenu_height']}px;
                  @if '{$this->options['single_menu_submenu_font']}' != '' {
                    font-family: '{$this->options['single_menu_submenu_font']}';
                  }
                  &:hover {
                    color: {$this->options['single_menu_item_submenu_link_colour_hover']};
                    background: {$this->options['single_menu_item_submenu_background_colour']};
                  }
                }
              }
            }
            &:hover > .responsive-menu-submenu {
              display: block;
            }
          }
        }






    }
CSS;

    return $this->compiler->compile($css);
  }

}
