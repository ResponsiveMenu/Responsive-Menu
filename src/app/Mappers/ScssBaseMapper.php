<?php

namespace ResponsiveMenu\Mappers;
use ResponsiveMenu\Collections\OptionsCollection;

class ScssBaseMapper extends ScssMapper {

  public function map(OptionsCollection $options) {

    $css = <<<CSS

      button#responsive-menu-button,
      #responsive-menu-container {
        display: none;
        -webkit-text-size-adjust: 100%;
      }

      @media screen and (max-width: {$options['breakpoint']}px) {

        #responsive-menu-container {
          display: block;
        }

      #responsive-menu-container {
        position: fixed;
        top: 0;
        bottom: 0;
        z-index: 99998;
        /* Fix for scroll bars appearing when not needed */
        padding-bottom: 5px;
        margin-bottom: -5px;
        outline: 1px solid transparent;
        overflow-y: auto;
        overflow-x: hidden;
        .responsive-menu-search-box {
          width: 100%;
          padding: 0 2%;
          border-radius: 2px;
          height: 50px;
          -webkit-appearance: none;
        }

        &.push-left,
        &.slide-left {
          transform: translateX(-100%);
          -ms-transform: translateX(-100%);
          -webkit-transform: translateX(-100%);
          -moz-transform: translateX(-100%);
          .responsive-menu-open & {
            transform: translateX(0);
            -ms-transform: translateX(0);
            -webkit-transform: translateX(0);
            -moz-transform: translateX(0);
          }
        }

        &.push-top,
        &.slide-top {
          transform: translateY(-100%);
          -ms-transform: translateY(-100%);
          -webkit-transform: translateY(-100%);
          -moz-transform: translateY(-100%);
          .responsive-menu-open & {
            transform: translateY(0);
            -ms-transform: translateY(0);
            -webkit-transform: translateY(0);
            -moz-transform: translateY(0);
          }
        }

        &.push-right,
        &.slide-right {
          transform: translateX(100%);
          -ms-transform: translateX(100%);
          -webkit-transform: translateX(100%);
          -moz-transform: translateX(100%);
          .responsive-menu-open & {
            transform: translateX(0);
            -ms-transform: translateX(0);
            -webkit-transform: translateX(0);
            -moz-transform: translateX(0);
          }
        }

        &.push-bottom,
        &.slide-bottom {
          transform: translateY(100%);
          -ms-transform: translateY(100%);
          -webkit-transform: translateY(100%);
          -moz-transform: translateY(100%);
          .responsive-menu-open & {
            transform: translateY(0);
            -ms-transform: translateY(0);
            -webkit-transform: translateY(0);
            -moz-transform: translateY(0);
          }
        }

        // Reset Styles for all our elements
        &, &:before, &:after, & *, & *:before, & *:after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
          }

        #responsive-menu-search-box,
        #responsive-menu-additional-content,
        #responsive-menu-title {
          padding: 25px 5%;
        }

        #responsive-menu {
          &, ul {
            width: 100%;
          }
          & ul.responsive-menu-submenu {
            display: none;
            &.responsive-menu-submenu-open {
              display: block;
            }
          }
          @for \$i from 1 through 6 {
            & ul.responsive-menu-submenu-depth-#{\$i}
            a.responsive-menu-item-link {
                padding-{$options['menu_text_alignment']}: 5% + (5% * \$i);
            }
          }

        }

        li.responsive-menu-item {
          width: 100%;
          list-style: none;
          a {
            width: 100%;
            display: block;
            text-decoration: none;
            padding: 0 5%;
            position: relative;
            .fa {
              margin-right: 15px;
            }
            .responsive-menu-subarrow {
              position: absolute;
              top: 0;
              bottom: 0;
              text-align: center;
              overflow: hidden;
              .fa {
                margin-right: 0;
              }
            }
          }
        }
      }

      button#responsive-menu-button {
        .responsive-menu-button-icon-inactive {
          display: none;
        }
      }

      button#responsive-menu-button {
        z-index: 99999;
        display: none;
        overflow: hidden;
        img {
          max-width: 100%;
        }
      }

      .responsive-menu-label {
        display: inline-block;
        font-weight: 600;
        margin: 0 5px;
        vertical-align: middle;
      }

      .responsive-menu-accessible {
        display: inline-block;
      }

      .responsive-menu-accessible .responsive-menu-box {
        display: inline-block;
        vertical-align: middle;
      }

      .responsive-menu-label.responsive-menu-label-top,
      .responsive-menu-label.responsive-menu-label-bottom
      {
        display: block;
        margin: 0 auto;
      }

    }
CSS;

    return $this->compiler->compile($css);
  }

}
