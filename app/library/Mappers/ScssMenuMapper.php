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

      body.responsive-menu-open {
        overflow: hidden;
      }

      #responsive-menu-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        overflow: hidden;
        width: 0;
        height: 0;
        background-color: #000;
        opacity: 0;
        transition: opacity 0.3s, width 0s 0.3s, height 0s 0.3s;
        .responsive-menu-open & {
          width: 100%;
          height: 100%;
          opacity: 0.7;
          transition: opacity 0.3s;
        }
      }

      #responsive-menu-container {
        position: fixed;
        top: 0;
        bottom: 0;
        z-index: 9999;
        width: {$this->options['menu_width']}%;
        {$this->options['menu_appear_from']}: 0;
        background: {$this->options['menu_item_background_colour']};
        transition: transform {$this->options['animation_speed']}s;
        overflow-y: auto;
        {$auto_height}
        {$max_width}
        {$min_width}

        .admin-bar & {
          margin-top: 32px;
        }

        &.push-left,
        &.slide-left {
          transform: translateX(-100%);
          .responsive-menu-open & {
            transform: translateX(0);
          }
        }

        &.push-top,
        &.slide-top {
          transform: translateY(-100%);
          .responsive-menu-open & {
            transform: translateY(0);
          }
        }

        &.push-right,
        &.slide-right {
          transform: translateX(100%);
          .responsive-menu-open & {
            transform: translateX(0);
          }
        }

        &.push-bottom,
        &.slide-bottom {
          transform: translateY(100%);
          .responsive-menu-open & {
            transform: translateY(0);
          }
        }

        // Reset Styles for all our elements
        &, &:before, &:after, & *, & *:before, & *:after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
          }

        #responsive-menu {
          &, ul {
            width: 100%;
          }
        }

        #responsive-menu-title {
          background-color: {$this->options['menu_title_background_colour']};
          color: {$this->options['menu_title_colour']};

          &:hover {
            background-color: {$this->options['menu_title_background_hover_colour']};
            color: {$this->options['menu_title_hover_colour']};
          }
        }

        li.responsive-menu-item {
          width: 100%;
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
            background-color: {$this->options['menu_item_background_colour']};

            &:hover {
              color: {$this->options['menu_link_hover_colour']};
              background-color: {$this->options['menu_item_background_hover_colour']};
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

      @media screen and ( max-width: 782px ) {
        .admin-bar #responsive-menu-container {
            margin-top: 46px;
        }
      }
CSS;

    return $this->compiler->compile($css);
  }

}
