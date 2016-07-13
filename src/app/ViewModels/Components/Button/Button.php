<?php

namespace ResponsiveMenu\ViewModels\Components\Button;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Button implements ViewComponent {

  public function render(OptionsCollection $options) {

    $button_title = apply_filters('wpml_translate_single_string', $options['button_title']->getValue(), 'Responsive Menu', 'button_title');

    /*
    Add Polylang Support */
    if(function_exists('pll__'))
      $button_title = pll__($button_title);

    $button_title_pos = $options['button_title_position']->getValue();
    $button_title_html = $button_title != '' ? '<span class="responsive-menu-label responsive-menu-label-'.$button_title_pos.'">'.$button_title.'</span>' : '';

    $accessible = in_array($button_title_pos, array('left', 'right')) ? 'responsive-menu-accessible' : '';
    $content = '';

    $content .= '<button id="responsive-menu-button"
            class="responsive-menu-button ' . $accessible .
            ' responsive-menu-' . $options['button_click_animation'] . '"
            type="button"
            aria-label="Menu">';
    $content .= in_array($button_title_pos, array('top', 'left')) ? $button_title_html : '';
    $content .= '<span class="responsive-menu-box">' . $options->getButtonIcon() . $options->getButtonIconActive() . '</span>';
    $content .= in_array($button_title_pos, array('bottom', 'right')) ? $button_title_html : '';
    $content .= '</button>';

    return $content;

  }

}
