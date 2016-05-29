<?php

namespace ResponsiveMenu\Mappers;

class JsMapper
{
  public function __construct(array $options)
  {
    $this->options = $options;
  }

  public function map()
  {
    $js = <<<JS

    jQuery(document).ready(function($) {

      var ResponsiveMenu = {
        trigger: '{$this->options['button_click_trigger']}',
        animationSpeed: {$this->options['animation_speed']},
        breakpoint: {$this->options['breakpoint']},
        pushButton: '{$this->options['button_push_with_animation']}',
        animationType: '{$this->options['animation_type']}',
        pageWrapper: '{$this->options['page_wrapper']}',
        isOpen: false,
        triggerTypes: 'click',
        activeClass: 'is-active',
        openMenu: function() {
          $(this.trigger).addClass(this.activeClass);
          $('body').addClass('responsive-menu-open');
          this.isOpen = true;
        },
        closeMenu: function() {
          $(this.trigger).removeClass(this.activeClass);
          $('body').removeClass('responsive-menu-open');
          this.isOpen = false;
        },
        triggerMenu: function() {
          this.isOpen ? this.closeMenu() : this.openMenu();
        }
      };

      $(ResponsiveMenu.trigger).on(ResponsiveMenu.triggerTypes, function(){
        ResponsiveMenu.triggerMenu();
      });

    });

JS;

  return $js;

  }

}
