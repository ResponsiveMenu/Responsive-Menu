<?php

namespace ResponsiveMenu\ViewModels\Components\Button;

use ResponsiveMenu\ViewModels\Components\ViewComponent;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Translation\Translator;

class Button implements ViewComponent {

  public function __construct(Translator $translator) {
    $this->translator = $translator;
  }

  public function render(OptionsCollection $options) {

    $button_title = $this->translator->translate($options['button_title']);

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
