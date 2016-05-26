<?php

namespace ResponsiveMenu\Mappers;

class ScssMenuMapper extends ScssMapper
{

  public function map()
  {

    $css = <<<CSS

      #responsive-menu-container {
        position: fixed;
        top: 0;
        bottom: 0;
        width: {$this->options['menu_width']}%;
        left: 0;
        background: {$this->options['menu_item_background_colour']};

        box-sizing: border-box;

          * {
            box-sizing: border-box;
            :before, :after {
              box-sizing: border-box;
            }
        }

        #responsive-menu {
          margin: 0;
          width: 100%;
          ul {
            margin: 0;
            width: 100%;
          }
        }

        .responsive-menu-item {

          width: 100%;
          background: {$this->options['menu_item_background_colour']};
          list-style: none;

          a {
            width: 100%;
            display: block;
            height: {$this->options['menu_links_height']}px;
            line-height: {$this->options['menu_links_height']}px;
            border: 1px solid {$this->options['menu_item_border_colour']};
            margin-top: -1px; // Fix double borders with menu link above
            text-decoration: none;
            color: {$this->options['menu_link_colour']};

            &:hover {
              color: {$this->options['menu_link_hover_colour']};
            }

            .responsive-menu-subarrow {
              float: right;
              height: {$this->options['menu_links_height']}px;
              line-height: {$this->options['menu_links_height']}px;
              color: {$this->options['menu_sub_arrow_shape_colour']};
              border: 1px solid {$this->options['menu_sub_arrow_border_colour']};
              background-color: {$this->options['menu_sub_arrow_background_colour']};
              padding: 0 10px;
              margin: -1px; // Fix double borders with menu link

                &:hover {
                  color: {$this->options['menu_sub_arrow_shape_hover_colour']};
                  border-color: {$this->options['menu_sub_arrow_border_hover_colour']};
                  background-color: {$this->options['menu_sub_arrow_background_hover_colour']};
                }

            }
          }
        }

      }
CSS;

    return $this->compiler->compile($css);
  }

}
