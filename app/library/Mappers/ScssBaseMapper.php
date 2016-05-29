<?php

namespace ResponsiveMenu\Mappers;

class ScssBaseMapper extends ScssMapper
{

  public function map()
  {

    $css = <<<CSS

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
        overflow-y: auto;

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

        li.responsive-menu-item {
          width: 100%;
          list-style: none;
          a {
            width: 100%;
            display: block;
            margin-top: -1px; // Fix double borders with menu link above
            text-decoration: none;

            .responsive-menu-subarrow {
              float: right;
              padding: 0 10px;
              margin: -1px; // Fix double borders with menu link
            }
          }
        }
      }

      @media screen and ( max-width: 782px ) {
        .admin-bar #responsive-menu-container {
            margin-top: 46px;
        }
      }

      .hamburger {
        padding: 0;

        .admin-bar & {
          margin-top: 32px;
        }

      }

      #responsive-menu-button {
        z-index: 9999;
      }

      .hamburger-label {
        display: inline-block;
        font-weight: 600;
        margin: 0 5px;
        vertical-align: middle;
      }

      .hamburger--accessible {
        display: inline-block;
      }

      .hamburger--accessible .hamburger-box {
        display: inline-block;
        vertical-align: middle;
      }

      .hamburger-label.hamburger-label-top,
      .hamburger-label.hamburger-label-bottom
      {
        display: block;
        margin: 10px auto;
      }

      @media screen and ( max-width: 782px ) {
        .admin-bar .hamburger {
            margin-top: 46px;
        }
      }
CSS;

    return $this->compiler->compile($css);
  }

}
