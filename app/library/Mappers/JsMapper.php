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
        animationSide: '{$this->options['menu_appear_from']}',
        pageWrapper: '{$this->options['page_wrapper']}',
        isOpen: false,
        triggerTypes: 'click',
        activeClass: 'is-active',
        openMenu: function() {
          $(this.trigger).addClass(this.activeClass);
          $('body').addClass('responsive-menu-open');
          this.setWrapperTranslate();
          this.isOpen = true;
        },
        closeMenu: function() {
          $(this.trigger).removeClass(this.activeClass);
          $('body').removeClass('responsive-menu-open');
          this.clearWrapperTranslate();
          this.isOpen = false;
        },
        triggerMenu: function() {
          this.isOpen ? this.closeMenu() : this.openMenu();
        },
        menuHeight: function() {
          return $('#responsive-menu-container').height();
        },
        menuWidth: function() {
          return $('#responsive-menu-container').width();
        },
        setWrapperTranslate: function() {
          var translate = '';
          switch(this.animationType + this.animationSide) {
            case 'pushleft':
              translate = 'translateX(' + this.menuWidth() + 'px)'; break;
            case 'pushright':
              translate = 'translateX(-' + this.menuWidth() + 'px)'; break;
            case 'pushtop':
              translate = 'translateY(' + this.menuHeight() + 'px)'; break;
            case 'pushbottom':
              translate = 'translateY(-' + this.menuHeight() + 'px)'; break;
            }
            if(translate) {
              $(this.pageWrapper).css({'transform':translate});
            }
        },
        clearWrapperTranslate: function() {
          switch(this.animationType + this.animationSide) {
            case 'pushleft':
            case 'pushright':
            case 'pushtop':
            case 'pushbottom':
              $(this.pageWrapper).css({'transform':''}); break;
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
