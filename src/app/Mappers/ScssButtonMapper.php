<?php

namespace ResponsiveMenu\Mappers;
use ResponsiveMenu\Collections\OptionsCollection;

class ScssButtonMapper extends ScssMapper {

  public function map(OptionsCollection $options) {

    $hamburger_css_dir = dirname(dirname(dirname(__FILE__))) . '/public/scss/hamburgers/hamburgers.scss';
    $no_animation = $options['button_click_animation'] == 'off' ? '$hamburger-types: ();' : '';

    $css = <<<CSS

    @media screen and ( max-width: {$options['breakpoint']}px ) {

      \$hamburger-layer-height: {$options['button_line_height']}px;
      \$hamburger-layer-spacing: {$options['button_line_margin']}px;
      \$hamburger-layer-color: {$options['button_line_colour']};
      \$hamburger-layer-width: {$options['button_line_width']}px;
      \$hamburger-hover-opacity: 1;
      {$no_animation}

      @import "{$hamburger_css_dir}";

      button#responsive-menu-button {
        width: {$options['button_width']}px;
        height: {$options['button_height']}px;
        @if '{$options['button_transparent_background']}' == 'off' {
          background: {$options['button_background_colour']};
          &:hover {
            background: {$options['button_background_colour_hover']};
          }
        }
        position: {$options['button_position_type']};
        top: {$options['button_top']}px;
        {$options['button_left_or_right']}: {$options['button_distance_from_side']}%;
        .responsive-menu-box {
          color: {$options['button_line_colour']};
        }
      }

      .responsive-menu-label {
        color: {$options['button_text_colour']};
        font-size: {$options['button_font_size']}px;
        line-height: {$options['button_title_line_height']}px;
        @if '{$options['button_font']}' != '' {
          font-family: '{$options['button_font']}';
        }
      }

      button#responsive-menu-button {
          display: inline-block;
          transition: transform {$options['animation_speed']}s, background-color {$options['transition_speed']}s;
        }
      }
CSS;

    return $this->compiler->compile($css);

  }

}
