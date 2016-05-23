<?php

namespace ResponsiveMenu\Mappers;

class CssMapper
{

  public function __construct(array $options)
  {
    $this->options = $options;
  }

  public function map()
  {

    $hover_calculation = (string) $this->options['button_line_margin'] + (string) $this->options['button_line_margin'] / 5;

$css = <<<CSS

    .navicon-button {
      display: inline-block;
      position: relative;
      transition: 0.25s;
      user-select: none;
    }

    .navicon-button .navicon:before,
    .navicon-button .navicon:after {
      transition: 0.25s;
    }

    #responsive-menu-button-holder:hover {
      cursor: pointer;
    }

    .navicon-button.line:hover {
      transition: 0.5s;
      opacity: 1;
    }

    #responsive-menu-button-holder:hover .navicon:before,
    #responsive-menu-button-holder:hover .navicon:after {
      transition: 0.25s;
    }
    #responsive-menu-button-holder:hover .navicon:before {
      top:{$hover_calculation}px;
    }
    #responsive-menu-button-holder:hover .navicon:after {
      top: -{$hover_calculation}px;
    }

    .navicon {
      position: relative;
      height: {$this->options['button_line_height']}px;
      width: {$this->options['button_line_width']}px;
      background: {$this->options['button_line_colour']};
      transition: 0.5s;
      border-radius: 2.5rem;
      margin: auto;
    }

    .navicon:before,
    .navicon:after {
      display: block;
      content: "";
      height: {$this->options['button_line_height']}px;
      width: {$this->options['button_line_width']}px;
      background: {$this->options['button_line_colour']};
      position: absolute;
      z-index: -1;
      transition: 0.5s 0.25s;
      border-radius: 1rem;
    }
    .navicon:before {
      top: {$this->options['button_line_margin']}px;
    }
    .navicon:after {
      top: -{$this->options['button_line_margin']}px;
    }

    .open:not(.steps) .navicon:before,
    .open:not(.steps) .navicon:after {
      top: 0 !important;
    }

    .open .navicon:before,
    .open .navicon:after {
      transition: 0.5s;
    }

    /* Minus */
    .open {
      transform: scale(0.75);
    }

    /* Arrows */
    .open.larr .navicon:before, .open.larr .navicon:after,
    .open.rarr .navicon:before,
    .open.rarr .navicon:after,
    .open.uarr .navicon:before,
    .open.uarr .navicon:after {
      width: 1.5rem;
    }

    .open.larr .navicon:before,
    .open.rarr .navicon:before,
    .open.uarr .navicon:before {
      transform: rotate(35deg);
      transform-origin: left top;
    }

    .open.larr .navicon:after,
    .open.rarr .navicon:after,
    .open.uarr .navicon:after {
      transform: rotate(-35deg);
      transform-origin: left bottom;
    }

    .open.uarr {
      transform: scale(0.75) rotate(90deg);
    }

    /* Arrows */
    .open.rarr .navicon:before {
      transform: translate3d(1em, 0, 0) rotate(-35deg);
      transform-origin: right top;
    }
    .open.rarr .navicon:after {
      transform: translate3d(1em, 0, 0) rotate(35deg);
      transform-origin: right bottom;
    }

    /* × and + */
    .open.plus .navicon,
    .open.x .navicon {
      background: transparent;
    }
    .open.plus .navicon:before,
    .open.x .navicon:before {
      transform: rotate(-45deg);
    }
    .open.plus .navicon:after,
    .open.x .navicon:after {
      transform: rotate(45deg);
    }

    .open.plus {
      transform: scale(0.75) rotate(45deg);
    }

    section *,
    section *:before,
    section *:after {
      transform: translate3d(0, 0, 0);
    }

    section#responsive-menu-button-holder
    {
      display: inline-flex;
      background: {$this->options['button_background_colour']};
      position: {$this->options['button_position_type']};
      top: {$this->options['button_top']}px;
      {$this->options['button_left_or_right']}: {$this->options['button_distance_from_side']}%;
      padding: {$this->options['button_line_margin']}px 0;
      width: {$this->options['button_width']}px;
      height: {$this->options['button_height']}px;
    }

    nav#responsive-menu-button {
      display: flex;
      justify-content: space-between;
      text-align: center;
      border-radius: .5rem .5rem 0 0;
      padding: 0 1rem;
      user-select: none;
      -webkit-tap-highlight-color: transparent;
      margin: auto;
    }

    #responsive-menu-button-title
    {
      width: 100%;
      line-height: {$this->options['button_title_line_height']}px;
      color: {$this->options['button_text_colour']};
      text-align: center;
      font-size: {$this->options['button_font_size']}px;
    }

    nav#responsive-menu-button.has-title
    {
      display: block;
      width: 100%;
    }

    nav#responsive-menu-button.has-title.has-title-top
    {
      display: table-caption;
    }

    nav#responsive-menu-button.has-title-bottom #responsive-menu-button-title
    {
      margin-top: {$this->options['button_line_margin']}px;
    }

    nav#responsive-menu-button.has-title-top #responsive-menu-button-title
    {
      margin-bottom: {$this->options['button_line_margin']}px;
    }

CSS;

  return $css;

  }

}
