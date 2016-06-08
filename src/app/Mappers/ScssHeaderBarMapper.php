<?php

namespace ResponsiveMenu\Mappers;

class ScssHeaderBarMapper extends ScssMapper
{

  public function map()
  {
    $css = <<<CSS
      #responsive-menu-header {
        position: {$this->options['header_bar_position_type']};
        background-color: {$this->options['header_bar_background_color']};
        height: {$this->options['header_bar_height']}px;
        color: {$this->options['header_bar_text_color']};
        margin-top: {$this->options['header_bar_margin_top']}px;
        font-size: {$this->options['header_bar_font_size']}px;
        @if '{$this->options['header_bar_font']}' != '' {
          font-family: '{$this->options['header_bar_font']}';
        }
        .responsive-menu-header-bar-item {
          line-height: {$this->options['header_bar_height']}px;
        }
      }
      @media screen and ( max-width: {$this->options['breakpoint']}px ) {
        #responsive-menu-header {
          display: inline-block;
        }
        body {
          padding-top: {$this->options['header_bar_height']}px;
        }
      }
CSS;

    return $this->compiler->compile($css);

  }

}
