<?php

namespace ResponsiveMenu\Mappers;

class ScssMenuMapper extends ScssMapper
{

  public function map()
  {

    $auto_height = $this->options['menu_auto_height'] == 'on'
      ? $this->options['menu_appear_from'] == 'bottom' ? 'top: auto;' : 'bottom: auto;'
      : '';

    $max_width = $this->options['menu_maximum_width']
      ? 'max-width: ' . $this->options['menu_maximum_width'] . 'px;'
      : '';

    $min_width = $this->options['menu_minimum_width']
      ? 'min-width: ' . $this->options['menu_minimum_width'] . 'px;'
      : '';

    $css = <<<CSS

      {$this->options['page_wrapper']} {
        transition: transform {$this->options['animation_speed']}s;
      }

      #responsive-menu-container {
        width: {$this->options['menu_width']}%;
        {$this->options['menu_appear_from']}: 0;
        background: {$this->options['menu_item_background_colour']};
        transition: transform {$this->options['animation_speed']}s;
        {$auto_height}
        {$max_width}
        {$min_width}

        #responsive-menu-title {
          background-color: {$this->options['menu_title_background_colour']};
          color: {$this->options['menu_title_colour']};

          &:hover {
            background-color: {$this->options['menu_title_background_hover_colour']};
            color: {$this->options['menu_title_hover_colour']};
          }
        }

        li.responsive-menu-item {
          a {
            height: {$this->options['menu_links_height']}px;
            line-height: {$this->options['menu_links_height']}px;
            border: 1px solid {$this->options['menu_item_border_colour']};
            color: {$this->options['menu_link_colour']};
            background-color: {$this->options['menu_item_background_colour']};

            &:hover {
              color: {$this->options['menu_link_hover_colour']};
              background-color: {$this->options['menu_item_background_hover_colour']};
            }

            .responsive-menu-subarrow {
              height: {$this->options['menu_links_height']}px;
              line-height: {$this->options['menu_links_height']}px;
              color: {$this->options['menu_sub_arrow_shape_colour']};
              border: 1px solid {$this->options['menu_sub_arrow_border_colour']};
              background-color: {$this->options['menu_sub_arrow_background_colour']};

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
