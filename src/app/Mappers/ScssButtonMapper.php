<?php

namespace ResponsiveMenu\Mappers;

class ScssButtonMapper extends ScssMapper
{

  public function map()
  {
    $hamburger_css_dir = dirname(dirname(dirname(__FILE__))) . '/public/scss/hamburgers/hamburgers.scss';
    $no_animation = $this->options['button_click_animation'] == 'off' ? '$hamburger-types: ();' : '';

    $css = <<<CSS

    @media screen and ( max-width: {$this->options['breakpoint']}px ) {

      \$hamburger-layer-height: {$this->options['button_line_height']}px;
      \$hamburger-layer-spacing: {$this->options['button_line_margin']}px;
      \$hamburger-layer-color: {$this->options['button_line_colour']};
      \$hamburger-layer-width: {$this->options['button_line_width']}px;
      \$hamburger-hover-opacity: 1;
      {$no_animation}

      @import "{$hamburger_css_dir}";

      .responsive-menu-button {
        width: {$this->options['button_width']}px;
        height: {$this->options['button_height']}px;
        @if '{$this->options['button_transparent_background']}' == 'off' {
          background: {$this->options['button_background_colour']};
          &:hover {
            background: {$this->options['button_background_colour_hover']};
          }
        }
        position: {$this->options['button_position_type']};
        top: {$this->options['button_top']}px;
        {$this->options['button_left_or_right']}: {$this->options['button_distance_from_side']}%;
        .responsive-menu-box {
          color: {$this->options['button_line_colour']};
        }
      }

      .responsive-menu-label {
        color: {$this->options['button_text_colour']};
        font-size: {$this->options['button_font_size']}px;
        line-height: {$this->options['button_title_line_height']}px;
        @if '{$this->options['button_font']}' != '' {
          font-family: '{$this->options['button_font']}';
        }
      }

      #responsive-menu-button {
          display: inline-block;
          transition: transform {$this->options['animation_speed']}s, background-color {$this->options['transition_speed']}s;
        }
      }
CSS;

    return $this->compiler->compile($css);

  }

}
