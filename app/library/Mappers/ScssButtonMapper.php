<?php

namespace ResponsiveMenu\Mappers;

class ScssButtonMapper extends ScssMapper
{

  public function map()
  {
    $hamburger_css_dir = dirname(dirname(dirname(__FILE__))) . '/public/scss/hamburgers/hamburgers.scss';
    $no_animation = $this->options['button_click_animation'] == 'off' ? '$hamburger-types: ();' : '';

    $css = <<<CSS

      \$hamburger-layer-height: {$this->options['button_line_height']}px;
      \$hamburger-layer-spacing: {$this->options['button_line_margin']}px;
      \$hamburger-layer-color: {$this->options['button_line_colour']};
      \$hamburger-layer-width: {$this->options['button_line_width']}px;
      \$hamburger-hover-opacity: 1;
      {$no_animation}

      @import "{$hamburger_css_dir}";

      .hamburger {
        width: {$this->options['button_width']}px;
        height: {$this->options['button_height']}px;
        @if '{$this->options['button_transparent_background']}' == 'off' {
          background-color: {$this->options['button_background_colour']};
        }
        position: {$this->options['button_position_type']};
        top: {$this->options['button_top']}px;
        {$this->options['button_left_or_right']}: {$this->options['button_distance_from_side']}%;
      }

      .hamburger-label {
        color: {$this->options['button_text_colour']};
        font-size: {$this->options['button_font_size']}px;
      }

      @media screen and ( max-width: {$this->options['breakpoint']}px ) {
        #responsive-menu-button {
          display: inline-block;
        }
      }
CSS;

    return $this->compiler->compile($css);

  }

}
