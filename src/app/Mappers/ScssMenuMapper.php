<?php

namespace ResponsiveMenu\Mappers;
use ResponsiveMenu\Collections\OptionsCollection;

class ScssMenuMapper extends ScssMapper {

  public function map(OptionsCollection $options) {

    $css = <<<CSS

    @media screen and ( max-width: {$options['breakpoint']}px ) {

      @if '{$options['menu_close_on_body_click']}' == 'on' {
        html.responsive-menu-open {
          cursor: pointer;
          #responsive-menu-container {
            cursor: initial;
          }
        }
      }

      @if '{$options['page_wrapper']}' != '' {
        & {$options['page_wrapper']} {
          transition: transform {$options['animation_speed']}s;
        }
        html.responsive-menu-open,
        .responsive-menu-open body {
          width: 100%;
        }
      }

      #responsive-menu-container {
        width: {$options['menu_width']}%;
        {$options['menu_appear_from']}: 0;

        background: {$options['menu_background_colour']};
        transition: transform {$options['animation_speed']}s;
        text-align: {$options['menu_text_alignment']};

        & #responsive-menu-wrapper {
          background: {$options['menu_background_colour']};
        }

        #responsive-menu-additional-content {
          color: {$options['menu_additional_content_colour']};
        }

        .responsive-menu-search-box {
            background: {$options['menu_search_box_background_colour']};
            border: 2px solid {$options['menu_search_box_border_colour']};
            color: {$options['menu_search_box_text_colour']};
            &:-ms-input-placeholder {
              color: {$options['menu_search_box_placholder_colour']};
            }
            &:-webkit-input-placeholder {
              color: {$options['menu_search_box_placholder_colour']};
            }
            &:-moz-placeholder {
              color: {$options['menu_search_box_placholder_colour']};
              opacity: 1;
            }
            &::-moz-placeholder {
              color: {$options['menu_search_box_placholder_colour']};
              opacity: 1;
            }
        }

        @if '{$options['menu_maximum_width']}' != '' {
          max-width: {$options['menu_maximum_width']}px;
        }
        @if '{$options['menu_minimum_width']}' != '' {
          min-width: {$options['menu_minimum_width']}px;
        }

        @if '{$options['menu_font']}' != '' {
          font-family: '{$options['menu_font']}';
        }

        & .responsive-menu-item-link, & #responsive-menu-title, & .responsive-menu-subarrow {
          transition: background-color {$options['transition_speed']}s, border-color {$options['transition_speed']}s, color {$options['transition_speed']}s;
        }

        #responsive-menu-title {
          background-color: {$options['menu_title_background_colour']};
          color: {$options['menu_title_colour']};
          font-size: {$options['menu_title_font_size']}px;
          a {
            color: {$options['menu_title_colour']};
            font-size: {$options['menu_title_font_size']}px;
            text-decoration: none;
            &:hover {
              color: {$options['menu_title_hover_colour']};
            }
          }
          &:hover {
            background-color: {$options['menu_title_background_hover_colour']};
            color: {$options['menu_title_hover_colour']};
            a {
              color: {$options['menu_title_hover_colour']};
            }
          }
          #responsive-menu-title-image {
            display: inline-block;
            vertical-align: middle;
            margin-right: 15px;
          }
        }

        #responsive-menu {

          > li.responsive-menu-item:first-child > a {
              border-top:  {$options['menu_border_width']}px solid {$options['menu_item_border_colour']};
          }

          li.responsive-menu-item {
            .responsive-menu-item-link {
              font-size: {$options['menu_font_size']}px;
            }

            a {
              line-height: {$options['menu_links_height']}px;
              border-bottom: {$options['menu_border_width']}px solid {$options['menu_item_border_colour']};

              color: {$options['menu_link_colour']};
              background-color: {$options['menu_item_background_colour']};
              &:hover {
                color: {$options['menu_link_hover_colour']};
                background-color: {$options['menu_item_background_hover_colour']};
                border-color: {$options['menu_item_border_colour_hover']};
                .responsive-menu-subarrow {
                  color: {$options['menu_sub_arrow_shape_hover_colour']};
                  border-color: {$options['menu_sub_arrow_border_hover_colour']};
                  background-color: {$options['menu_sub_arrow_background_hover_colour']};
                }
              }

              .responsive-menu-subarrow {
                {$options['arrow_position']}: 0;
                height: {$options['submenu_arrow_height']}px;
                line-height: {$options['submenu_arrow_height']}px;
                width: {$options['submenu_arrow_width']}px;
                color: {$options['menu_sub_arrow_shape_colour']};
                border-left: {$options['menu_border_width']}px solid {$options['menu_sub_arrow_border_colour']};
                background-color: {$options['menu_sub_arrow_background_colour']};
                  &.responsive-menu-subarrow-active {
                    color: {$options['menu_sub_arrow_shape_colour_active']};
                    border-color: {$options['menu_sub_arrow_border_colour_active']};
                    background-color: {$options['menu_sub_arrow_background_colour_active']};
                    &:hover {
                      color: {$options['menu_sub_arrow_shape_hover_colour_active']};
                      border-color: {$options['menu_sub_arrow_border_hover_colour_active']};
                      background-color: {$options['menu_sub_arrow_background_hover_colour_active']};
                    }
                  }
                  &:hover {
                    color: {$options['menu_sub_arrow_shape_hover_colour']};
                    border-color: {$options['menu_sub_arrow_border_hover_colour']};
                    background-color: {$options['menu_sub_arrow_background_hover_colour']};
                  }
              }
            }
            &.responsive-menu-current-item > .responsive-menu-item-link {
              background-color: {$options['menu_current_item_background_colour']};
              color: {$options['menu_current_link_colour']};
              border-color: {$options['menu_current_item_border_colour']};
              &:hover {
                background-color: {$options['menu_current_item_background_hover_colour']};
                color: {$options['menu_current_link_hover_colour']};
                border-color: {$options['menu_current_item_border_hover_colour']};
              }
            }
          }
        }

        @if '{$options['menu_background_image']}' != '' {
          background: url('{$options['menu_background_image']}');
          background-size: cover;
          #responsive-menu-title,
          #responsive-menu-title:hover,
          #responsive-menu li.responsive-menu-item a,
          #responsive-menu li.responsive-menu-item a:hover,
          #responsive-menu li.responsive-menu-item a .responsive-menu-subarrow,
          #responsive-menu li.responsive-menu-item a .responsive-menu-subarrow:hover,
          #responsive-menu li.responsive-menu-item a:hover .responsive-menu-subarrow,
          #responsive-menu li.responsive-menu-item.responsive-menu-current-item > .responsive-menu-item-link,
          #responsive-menu li.responsive-menu-item.responsive-menu-current-item > .responsive-menu-item-link:hover,
          #responsive-menu-wrapper {
            background: none;
            border: 0;
          }
        }

      }
        @if '{$options['menu_to_hide']}' != '' {
          & {$options['menu_to_hide']} {
            display: none !important;
          }
        }
      }

CSS;

    return $this->compiler->compile($css);
  }

}
