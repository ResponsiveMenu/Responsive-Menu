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
        container: '#responsive-menu-container',
        openClass: 'responsive-menu-open',
        accordion: '{$this->options['accordion_animation']}',
        activeArrow: '{$this->options['active_arrow_shape']}',
        inactiveArrow: '{$this->options['inactive_arrow_shape']}',
        openMenu: function() {
          $(this.trigger).addClass(this.activeClass);
          $('body').addClass(this.openClass);
          this.setWrapperTranslate();
          this.isOpen = true;
        },
        closeMenu: function() {
          $(this.trigger).removeClass(this.activeClass);
          $('body').removeClass(this.openClass);
          this.clearWrapperTranslate();
          this.isOpen = false;
        },
        triggerMenu: function() {
          this.isOpen ? this.closeMenu() : this.openMenu();
        },
        triggerSubArrow: function(subarrow) {
          if(this.accordion == 'on') {
            $('.responsive-menu-submenu').slideUp(200, 'linear');
            $('.responsive-menu-submenu').removeClass('responsive-menu-submenu-open');
            $('.responsive-menu-subarrow').html(this.inactiveArrow);
          }
          var sub_menu = $(subarrow).parent().parent().children('.responsive-menu-submenu');
          if(sub_menu.hasClass('responsive-menu-submenu-open')) {
            sub_menu.slideUp(200, 'linear').removeClass('responsive-menu-submenu-open');
            $(subarrow).html(this.inactiveArrow);
          } else {
            sub_menu.slideDown(200, 'linear').addClass('responsive-menu-submenu-open');
            $(subarrow).html(this.activeArrow);
          }
        },
        menuHeight: function() {
          return $(this.container).height();
        },
        menuWidth: function() {
          return $(this.container).width();
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
        },
        init: function() {
          var self = this;
          $(this.trigger).on(this.triggerTypes, function(){
            self.triggerMenu();
          });
          $('.responsive-menu-subarrow').on('click', function(e) {
            e.preventDefault();
            self.triggerSubArrow(this);
          });
        }
      };

      ResponsiveMenu.init();

    });

JS;

  return $js;

  }

}
