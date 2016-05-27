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
        openMenu: function() {
          $(this.trigger).addClass('is-active');
          this.isOpen = true;
        },
        closeMenu: function() {
          $(this.trigger).removeClass('is-active');
          this.isOpen = false;
        },
        triggerMenu: function() {
          if(!this.isOpen) {
            this.openMenu();
          } else {
            this.closeMenu();
          }
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
