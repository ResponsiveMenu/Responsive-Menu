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
        wrapper: '#responsive-menu-wrapper',
        closeOnClick: '{$this->options['close_menu_on_link_click']}',
        linkElement: '.responsive-menu-item-link',
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

          var sub_menu = $(subarrow).parent().next('.responsive-menu-submenu');
          var self = this;

          if(this.accordion == 'on') {
            // Get Top Most Parent and the siblings
            var top_siblings = sub_menu.parents('.responsive-menu-item-has-children').last().siblings('.responsive-menu-item-has-children');
            var first_siblings = sub_menu.parents('.responsive-menu-item-has-children').first().siblings('.responsive-menu-item-has-children');
            // Close up just the top level parents to key the rest as it was
            top_siblings.children('.responsive-menu-submenu').slideUp(200, 'linear').removeClass('responsive-menu-submenu-open');
            // Set each parent arrow to inactive
            top_siblings.each(function() {
              $(this).find('.responsive-menu-subarrow').first().html(self.inactiveArrow);
            });
            // Now Repeat for the current item siblings
            first_siblings.children('.responsive-menu-submenu').slideUp(200, 'linear').removeClass('responsive-menu-submenu-open');
            first_siblings.each(function() {
              $(this).find('.responsive-menu-subarrow').first().html(self.inactiveArrow);
            });
          }

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
        wrapperHeight: function() {
          return $(this.wrapper).height();
        },
        setWrapperTranslate: function() {
          var translate = '';
          switch(this.animationType + this.animationSide) {
            case 'pushleft':
              translate = 'translateX(' + this.menuWidth() + 'px)'; break;
            case 'pushright':
              translate = 'translateX(-' + this.menuWidth() + 'px)'; break;
            case 'pushtop':
              translate = 'translateY(' + this.wrapperHeight() + 'px)'; break;
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
          $(window).resize(function() {
            if($(window).width() > self.breakpoint) {
              self.closeMenu();
            } else {
              if($('.responsive-menu-open').length>0){
                self.setWrapperTranslate();
              }
            }
          });
          if(this.closeOnClick == 'on') {
            $(this.linkElement).on('click', function() {
              self.closeMenu();
            });
          }


        }
      };
      ResponsiveMenu.init();
    });

JS;

  return $js;

  }

}
