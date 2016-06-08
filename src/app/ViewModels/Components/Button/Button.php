<?php

namespace ResponsiveMenu\ViewModels\Components\Button;

use ResponsiveMenu\ViewModels\Components\ViewComponent as ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection as OptionsCollection;

class Button implements ViewComponent {

  public function render(OptionsCollection $options) {

    $button_title = $options['button_title'];
    $button_title_pos = $options['button_title_position'];
    $button_title_html = $button_title != '' ? '<span class="hamburger-label hamburger-label-'.$button_title_pos.'">'.$button_title.'</span>' : '';
    $accessible = in_array($button_title_pos, array('left', 'right')) ? 'hamburger--accessible' : '';

    $content = '<button id="responsive-menu-button"
            class="hamburger ' . $accessible . '
            hamburger--' . $options['button_click_animation'] . '"
            type="button"
            aria-label="Menu"
            aria-controls="navigation">';
    $content .= in_array($button_title_pos, array('top', 'left')) ? $button_title_html : '';
    $content .= '<span class="hamburger-box">' . $options->getButtonIcon() . $options->getButtonIconActive() . '</span>';
    $content .= in_array($button_title_pos, array('bottom', 'right')) ? $button_title_html : '';
    $content .= '</button>';

    return $content;

  }

}
