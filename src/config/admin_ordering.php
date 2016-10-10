<?php

$menus_array = [];
foreach(get_terms('nav_menu') as $menu) $menus_array[] = [ 'value' => $menu->slug, 'display' => $menu->name];
foreach(get_registered_nav_menus() as $location => $menu) $location_menus[] = ['value' => $location, 'display' => $menu];
$location_menus[] = ['value' => '', 'display' => 'None'];

$unit_options = [
  ['value' => 'px', 'display' => 'px'],
  ['value' => 'em', 'display' => 'em', 'disabled' => true],
  ['value' => 'rem', 'display' => 'rem', 'disabled' => true],
  ['value' => '%', 'display' => '%', 'disabled' => true]
];

$percentage_unit_options = [
  ['value' => 'px', 'display' => 'px', 'disabled' => true],
  ['value' => 'em', 'display' => 'em', 'disabled' => true],
  ['value' => 'rem', 'display' => 'rem', 'disabled' => true],
  ['value' => '%', 'display' => '%']
];

$order_mapping = [

      /*
      *
      * INITIAL SETUP
      *
      */

      __('Initial Setup', 'responsive-menu') => [
        __('Menu', 'responsive-menu') =>
        [
          [
            'option' => 'breakpoint',
            'title' => __('Breakpoint', 'responsive-menu'),
            'label' => __('This is the width of the screen at which point you would like the menu to start showing', 'responsive-menu'),
            'unit' => 'px'
          ],
          [
            'option' => 'menu_to_use',
            'title' => __('Menu to Use', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'select',
            'select' => $menus_array
          ],
          [
            'option' => 'menu_to_hide',
            'title' => __('CSS of Menu to Hide', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ]
        ]
      ],


  /*
  *
  * MENU
  *
  */

  __('Menu', 'responsive-menu') =>  [
    __('Section Ordering', 'responsive-menu') => [
        [
          'option' => 'items_order',
          'title' => __('Order of Menu Items', 'responsive-menu'),
          'label' => __('Drag the items to re-order and click to turn them on/off', 'responsive-menu'),
          'type' => 'menu_ordering'
        ]
    ],
    __('Font Icons', 'responsive-menu') =>
      [
        [
          'option' => 'menu_font_icons',
          'title' => __('Font Icons', 'responsive-menu'),
          'label' => __('Responsive Menu uses the brilliant <a href="http://fontawesome.io/icons/" target="_blank">Awesome Font Icons</a> for implementing icons in your menu - for more info please visit our doc pages at <a href="https://responsive.menu/docs/basic-setup/font-icons/" target="_blank">https://responsive.menu/docs/basic-setup/font-icons/</a>', 'responsive-menu'),
          'type' => 'fonticons',
          'pro' => true
        ]
      ],
    __('Sizing', 'responsive-menu') =>
      [
        [
          'option' => 'menu_width',
          'title' => __('Menu Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'menu_width_unit',
              'type' => 'select',
              'select' => $percentage_unit_options
            ]
          ]
        ],
        [
          'option' => 'menu_maximum_width',
          'title' => __('Maximum Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'menu_maximum_width_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'menu_minimum_width',
          'title' => __('Minimum Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'menu_minimum_width_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'menu_links_height',
          'title' => __('Links Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'menu_links_height_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'menu_border_width',
          'title' => __('Border Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'menu_border_width_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'menu_auto_height',
          'title' => __('Menu Auto Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox',
          'pro' => true
        ],
      ],
      __('Title', 'responsive-menu') =>
        [
          [
            'option' => 'menu_title',
            'title' => __('Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ],
          [
            'option' => 'menu_title_link',
            'title' => __('Link', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
          ],
          [
            'option' => 'menu_title_link_location',
            'title' => __('Link Location', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'select',
            'select' => [
              ['value' => '_blank', 'display' => 'New Tab'],
              ['value' => '_self', 'display' => 'Same Page'],
              ['value' => '_parent', 'display' => 'Parent Page'],
              ['value' => '_top', 'display' => 'Full Window Body']
            ]
          ],
          [
            'option' => 'menu_title_font_size',
            'title' => __('Title Font Size', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'semi_pro' => true,
            'sub_options' =>
            [
              [
                'option' => 'menu_title_font_size_unit',
                'type' => 'select',
                'select' => $unit_options
              ]
            ]
          ],
          [
            'option' => 'menu_title_font_icon',
            'title' => __('Font Icon', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'pro' => true
          ],
          [
            'option' => 'menu_title_image',
            'title' => __('Image', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'image'
          ],
          [
            'option' => 'menu_title_image_alt',
            'title' => __('Alt Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
          ],
          [
            'option' => 'menu_title_background_colour',
            'title' => __('Title Background Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'menu_title_background_hover_colour',
            'title' => __('Title Background Colour Hover', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'menu_title_colour',
            'title' => __('Title Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'menu_title_hover_colour',
            'title' => __('Title Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ]

        ],
    __('Additional Content', 'responsive-menu') =>
      [
        [
          'option' => 'menu_additional_content',
          'title' => __('Text', 'responsive-menu'),
          'label' => __('HTMl and Shortcodes can be used', 'responsive-menu'),
          'type' => 'textarea'
        ],
        [
          'option' => 'menu_additional_content_colour',
          'title' => __('Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
      ],
    __('Animation', 'responsive-menu') =>
      [
        [
          'option' => 'menu_appear_from',
          'title' => __('Appear From', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'select' => [
            ['value' => 'top', 'display' => 'Top'],
            ['value' => 'left', 'display' => 'Left'],
            ['value' => 'right', 'display' => 'Right'],
            ['value' => 'bottom', 'display' => 'Bottom']
          ],
        ],
        [
          'option' => 'animation_type',
          'title' => __('Animation Type', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'semi_pro' => true,
          'select' => [
            ['value' => 'slide',  'display' => 'Slide'],
            ['value' => 'push', 'display' => 'Push'],
            ['value' => 'fade', 'display' => 'Fade', 'disabled' => true]
          ]
        ],
        [
          'option' => 'page_wrapper',
          'title' => __('Page Wrapper CSS selector', 'responsive-menu'),
          'label' => __('This is only needed if you are using the push animation above', 'responsive-menu')
        ],
        [
          'option' => 'menu_close_on_body_click',
          'title' => __('Close Menu on Body Clicks', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox'
        ],
        [
          'option' => 'menu_close_on_link_click',
          'title' => __('Close Menu on Link Clicks', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox'
        ],
      ],
    __('Search Box', 'responsive-menu') =>
      [
        [
          'option' => 'menu_search_box_text',
          'title' => __('Text', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'menu_search_box_text_colour',
          'title' => __('Text Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_search_box_border_colour',
          'title' => __('Border Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_search_box_background_colour',
          'title' => __('Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_search_box_placholder_colour',
          'title' => __('Placeholder Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ]
      ],
    __('Background Colours', 'responsive-menu') =>
      [
        [
          'option' => 'menu_background_image',
          'title' => __('Background Image', 'responsive-menu'),
          'label' => __('Enabling this will deactivate all other colour options', 'responsive-menu'),
          'type' => 'image'
        ],
        [
          'option' => 'menu_background_colour',
          'title' => __('Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_item_background_colour',
          'title' => __('Item Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_item_background_hover_colour',
          'title' => __('Item Background Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_current_item_background_colour',
          'title' => __('Current Item Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_current_item_background_hover_colour',
          'title' => __('Current Item Background Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
      ],
    __('Border Colours', 'responsive-menu-pro') =>
      [
        [
          'option' => 'menu_item_border_colour',
          'title' => __('Item Border Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_item_border_colour_hover',
          'title' => __('Item Border Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_current_item_border_colour',
          'title' => __('Current Item Border Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ],
        [
          'option' => 'menu_current_item_border_hover_colour',
          'title' => __('Current Item Border Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'semi_pro' => true
        ]
      ],
      __('Text Colours', 'responsive-menu') =>
        [

          [
            'option' => 'menu_link_colour',
            'title' => __('Link Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'menu_link_hover_colour',
            'title' => __('Link Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'menu_current_link_colour',
            'title' => __('Current Link Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'menu_current_link_hover_colour',
            'title' => __('Current Link Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],

        ],
        __('Text Styling', 'responsive-menu') =>
          [
            [
              'option' => 'menu_font',
              'title' => __('Font', 'responsive-menu'),
              'label' => __('', 'responsive-menu')
            ],
            [
              'option' => 'menu_font_size',
              'title' => __('Font Size', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'semi_pro' => true,
              'sub_options' =>
              [
                [
                  'option' => 'menu_font_size_unit',
                  'type' => 'select',
                  'select' => $unit_options
                ]
              ]
            ],
            [
              'option' => 'menu_text_alignment',
              'title' => __('Text Alignment', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'select',
              'select' => [
                ['value' => 'left', 'display' => 'Left'],
                ['value' => 'right', 'display' => 'Right'],
                ['value' => 'center', 'display' => 'Centred'],
                ['value' => 'justify', 'display' => 'Justified']
              ]
            ],
            [
              'option' => 'menu_word_wrap',
              'title' => __('Word Wrap', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'checkbox',
              'pro' => true
            ],
          ],
          __('Page Overlay', 'responsive-menu') =>
            [
              [
                'option' => 'menu_overlay',
                'title' => __('Add Page Overlay When Menu Open', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'checkbox',
                'pro' => true
              ],
              [
                'option' => 'menu_overlay_colour',
                'title' => __('Overlay Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour',
                'pro' => true
              ],
            ],
          __('Advanced', 'responsive-menu') =>
            [
              [
                'option' => 'menu_depth',
                'title' => __('Depth', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'select',
                'select' => [
                  ['value' => 1, 'display' => 1],
                  ['value' => 2, 'display' => 2],
                  ['value' => 3, 'display' => 3],
                  ['value' => 4, 'display' => 4],
                  ['value' => 5, 'display' => 5],
                ]
              ],
              [
                'option' => 'menu_disable_scrolling',
                'title' => __('Disable Scrolling when Menu Active', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'checkbox',
                'pro' => true
              ],
              [
                'option' => 'theme_location_menu',
                'title' => __('Theme Location Menu', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'select',
                'select' => $location_menus
              ]
            ],
  ],

    /*
    *
    * BUTTON
    *
    */

    __('Button', 'responsive-menu') => [
      __('Animation', 'responsive-menu') =>
      [
        [
          'option' => 'button_click_animation',
          'title' => __('Animation Type', 'responsive-menu'),
          'label' => __('To see all animations in action please visit <a href="https://jonsuh.com/hamburgers/" target="_blank">this page</a>', 'responsive-menu'),
          'type' => 'select',
          'select' => [
            ['value' => 'off', 'display' => 'Off'],
            ['value' => '3dx', 'display' => '3DX', 'disabled' => true],
            ['value' => '3dx-r', 'display' => '3DX Reverse', 'disabled' => true],
            ['value' => '3dy', 'display' => '3DY', 'disabled' => true],
            ['value' => '3dy-r', 'display' => '3DY Reverse', 'disabled' => true],
            ['value' => 'arrow', 'display' => 'Arrow', 'disabled' => true],
            ['value' => 'arrow-r', 'display' => 'Arrow Reverse', 'disabled' => true],
            ['value' => 'arrowalt', 'display' => 'Arrow Alt', 'disabled' => true],
            ['value' => 'arrowalt-r', 'display' => 'Arrow Alt Reverse', 'disabled' => true],
            ['value' => 'boring', 'display' => 'Boring'],
            ['value' => 'collapse', 'display' => 'Collapse', 'disabled' => true],
            ['value' => 'collapse-r', 'display' => 'Collapse Reverse', 'disabled' => true],
            ['value' => 'elastic', 'display' => 'Elastic', 'disabled' => true],
            ['value' => 'elastic-r', 'display' => 'Elastic Reverse', 'disabled' => true],
            ['value' => 'emphatic', 'display' => 'Emphatic', 'disabled' => true],
            ['value' => 'emphatic-r', 'display' => 'Emphatic Reverse', 'disabled' => true],
            ['value' => 'slider', 'display' => 'Slider', 'disabled' => true],
            ['value' => 'slider-r', 'display' => 'Slider Reverse', 'disabled' => true],
            ['value' => 'spin', 'display' => 'Spin', 'disabled' => true],
            ['value' => 'spin-r', 'display' => 'Spin Reverse', 'disabled' => true],
            ['value' => 'spring', 'display' => 'Spring', 'disabled' => true],
            ['value' => 'spring-r', 'display' => 'Spring Reverse', 'disabled' => true],
            ['value' => 'stand', 'display' => 'Stand', 'disabled' => true],
            ['value' => 'stand-r', 'display' => 'Stand Reverse', 'disabled' => true],
            ['value' => 'squeeze', 'display' => 'Squeeze', 'disabled' => true],
            ['value' => 'vortex', 'display' => 'Vortex', 'disabled' => true],
            ['value' => 'vortex-r', 'display' => 'Vortex Reverse', 'disabled' => true]
          ],
          'semi_pro' => true
        ],
        [
          'option' => 'button_position_type',
          'title' => __('Position Type', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'select' => [
            ['value' => 'absolute', 'display' => 'Absolute'],
            ['value' => 'fixed', 'display' => 'Fixed'],
            ['value' => 'relative', 'display' => 'Relative']
          ]
        ],
        [
          'option' => 'button_push_with_animation',
          'title' => __('Push Button with Animation', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox'
        ],
      ],
      __('Location', 'responsive-menu') =>
      [
        [
          'option' => 'button_distance_from_side',
          'title' => __('Distance from Side', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_distance_from_side_unit',
              'type' => 'select',
              'select' => $percentage_unit_options
            ]
          ]
        ],
        [
          'option' => 'button_left_or_right',
          'title' => __('Button Side', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'select' => [
            ['value' => 'left', 'display' => 'Left'],
            ['value' => 'right', 'display' => 'Right']
          ]
        ],
        [
          'option' => 'button_top',
          'title' => __('Distance from Top', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_top_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
      ],
      __('Container Sizing', 'responsive-menu') =>
      [
        [
          'option' => 'button_height',
          'title' => __('Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_height_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'button_width',
          'title' => __('Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_width_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
      ],
      __('Hamburger Sizing', 'responsive-menu') =>
      [
        [
          'option' => 'button_line_height',
          'title' => __('Line Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_line_height_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'button_line_margin',
          'title' => __('Line Margin', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_line_margin_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
        [
          'option' => 'button_line_width',
          'title' => __('Line Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'semi_pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'button_line_width_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],

      ],
      __('Background Colours', 'responsive-menu') => [
          [
            'option' => 'button_background_colour',
            'title' => __('Background Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'button_background_colour_hover',
            'title' => __('Background Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'button_transparent_background',
            'title' => __('Transparent Background', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox'
          ]
        ],
        __('Line Colours', 'responsive-menu') => [
          [
            'option' => 'button_line_colour',
            'title' => __('Line Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
        ],
        __('Title', 'responsive-menu') => [
          [
            'option' => 'button_title',
            'title' => __('Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
          ],
          [
            'option' => 'button_text_colour',
            'title' => __('Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour',
            'semi_pro' => true
          ],
          [
            'option' => 'button_title_position',
            'title' => __('Title Text Position', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'select',
            'select' => [
              ['value' => 'left', 'display' => 'Left'],
              ['value' => 'right', 'display' => 'Right'],
              ['value' => 'top', 'display' => 'Top'],
              ['value' => 'bottom', 'display' => 'Bottom']
            ]
          ],
          [
            'option' => 'button_font',
            'title' => __('Font', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
          ],
          [
            'option' => 'button_font_size',
            'title' => __('Font Size', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'semi_pro' => true,
            'sub_options' =>
            [
              [
                'option' => 'button_font_size_unit',
                'type' => 'select',
                'select' => $unit_options
              ]
            ]
          ],
          [
            'option' => 'button_title_line_height',
            'title' => __('Line Height', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'semi_pro' => true,
            'sub_options' =>
            [
              [
                'option' => 'button_title_line_height_unit',
                'type' => 'select',
                'select' => $unit_options
              ]
            ]
          ],
      ],
      __('Image', 'responsive-menu') =>
        [
          [
            'option' => 'button_font_icon',
            'title' => __('Font Icon', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'pro' => true
          ],
          [
            'option' => 'button_font_icon_when_clicked',
            'title' => __('Font Icon When Clicked', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'pro' => true
          ],
          [
            'option' => 'button_image',
            'title' => __('Image', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'image'
          ],
          [
            'option' => 'button_image_alt',
            'title' => __('Alt Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ],
          [
            'option' => 'button_image_when_clicked',
            'title' => __('Image When Clicked', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'image'
          ],
          [
            'option' => 'button_image_alt_when_clicked',
            'title' => __('Alt Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ]
        ],
        __('Advanced', 'responsive-menu') =>
        [
          [
            'option' => 'button_click_trigger',
            'title' => __('Trigger', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
          ],
        ],
    ],

/*
*
* SUB MENUS
*
*/
__('Sub-Menus', 'responsive-menu') => [

  __('Toggle Button Background Colours', 'responsive-menu') =>
    [
      [
        'option' => 'menu_sub_arrow_background_colour',
        'title' => __('Background Colour', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_background_hover_colour',
        'title' => __('Background Hover Colour', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_background_colour_active',
        'title' => __('Background Colour Active', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_background_hover_colour_active',
        'title' => __('Background Hover Colour Active', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
    ],
  __('Toggle Button Border Colours', 'responsive-menu') =>
    [
      [
        'option' => 'menu_sub_arrow_border_colour',
        'title' => __('Border Colour', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_border_hover_colour',
        'title' => __('Border Hover Colour', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_border_colour_active',
        'title' => __('Border Colour Active', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_border_hover_colour_active',
        'title' => __('Border Hover Colour Active', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
    ],
  __('Toggle Button Icon Colours', 'responsive-menu') =>
    [
      [
        'option' => 'menu_sub_arrow_shape_colour',
        'title' => __('Icon Colour', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_shape_hover_colour',
        'title' => __('Icon Hover Colour', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_shape_colour_active',
        'title' => __('Icon Colour Active', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
      [
        'option' => 'menu_sub_arrow_shape_hover_colour_active',
        'title' => __('Icon Hover Colour Active', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'type' => 'colour',
        'semi_pro' => true
      ],
    ],
      __('Animations', 'responsive-menu') =>
        [
          [
            'option' => 'accordion_animation',
            'title' => __('Use Accordion Animation', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox'
          ],
          [
            'option' => 'auto_expand_all_submenus',
            'title' => __('Auto Expand All Submenus', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox'
          ],
          [
            'option' => 'auto_expand_current_submenus',
            'title' => __('Auto Expand Current Submenus', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox'
          ],
          [
            'option' => 'menu_item_click_to_trigger_submenu',
            'title' => __('Disable Parent Item Clicks', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox'
          ],
          [
            'option' => 'use_slide_effect',
            'title' => __('Use slide effect instead of drop-down', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox',
            'pro' => true,
            'beta' => true
          ]
        ],
        __('Fade Links In', 'responsive-menu') =>
        [
          [
            'option' => 'fade_submenus',
            'title' => __('Enabled', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'checkbox',
            'pro' => true
          ],
          [
            'option' => 'fade_submenus_side',
            'title' => __('Fade from side', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'select',
            'select' => [
              ['value' => 'left', 'display' => 'Left'],
              ['value' => 'right', 'display' => 'Right']
            ],
            'pro' => true
          ],
          [
            'option' => 'fade_submenus_delay',
            'title' => __('Delay between items', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'unit' => 'ms',
            'pro' => true
          ],
          [
            'option' => 'fade_submenus_speed',
            'title' => __('Speed of fade', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'unit' => 'ms',
            'pro' => true
          ]
        ],
      __('Sizing', 'responsive-menu') =>
        [
          [
            'option' => 'submenu_arrow_height',
            'title' => __('Toggle Button Height', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'semi_pro' => true,
            'sub_options' =>
            [
              [
                'option' => 'submenu_arrow_height_unit',
                'type' => 'select',
                'select' => $unit_options
              ]
            ]
          ],
          [
            'option' => 'submenu_arrow_width',
            'title' => __('Toggle Button Width', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'semi_pro' => true,
            'sub_options' =>
            [
              [
                'option' => 'submenu_arrow_width_unit',
                'type' => 'select',
                'select' => $unit_options
              ]
            ]
          ],

        ],
      __('Toggle Icons', 'responsive-menu') =>
        [
          [
            'option' => 'active_arrow_font_icon',
            'title' => __('Font Icon Active', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'pro' => true
          ],
          [
            'option' => 'inactive_arrow_font_icon',
            'title' => __('Font Icon Inactive', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'pro' => true
          ],
          [
            'option' => 'active_arrow_shape',
            'title' => __('HTML Shape Active', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ],
          [
            'option' => 'inactive_arrow_shape',
            'title' => __('HTML Shape Inactive', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ],
          [
            'option' => 'active_arrow_image',
            'title' => __('Image Active', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'image'
          ],
          [
            'option' => 'active_arrow_image_alt',
            'title' => __('Alt Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ],
          [
            'option' => 'inactive_arrow_image',
            'title' => __('Image Inactive', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'image'
          ],
          [
            'option' => 'inactive_arrow_image_alt',
            'title' => __('Alt Text', 'responsive-menu'),
            'label' => __('', 'responsive-menu')
          ],
          [
            'option' => 'arrow_position',
            'title' => __('Icon Position', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
              'type' => 'select',
              'select' => [
                ['value' => 'left', 'display' => 'Left'],
                ['value' => 'right', 'display' => 'Right']
              ],
          ]
        ],
      ],

  /*
  *
  * TECHNICAL
  *
  */

  __('Technical', 'responsive-menu') => [
    __('Scripts', 'responsive-menu') => [
        [
          'option' => 'external_files',
          'title' => __('Use External Files?', 'responsive-menu'),
          'label' => __('This will create external files for CSS and JavaScript', 'responsive-menu'),
          'type' => 'checkbox'
        ],
        [
          'option' => 'minify_scripts',
          'title' => __('Minify Scripts?', 'responsive-menu'),
          'label' => __('This will minify CSS and JavaScript output', 'responsive-menu'),
          'type' => 'checkbox'
        ],
        [
          'option' => 'scripts_in_footer',
          'title' => __('Place Scripts In Footer?', 'responsive-menu'),
          'label' => __('This will place the JavaScript file in the footer', 'responsive-menu'),
          'type' => 'checkbox'
        ]
    ],
    __('Menu', 'responsive-menu') => [
        [
          'option' => 'custom_walker',
          'title' => __('Custom Walker', 'responsive-menu'),
          'label' => __('Warning: For extremely advanced use only', 'responsive-menu'),
        ],
        [
          'option' => 'mobile_only',
          'title' => __('Show on mobile devices only?', 'responsive-menu'),
          'label' => __('This will make it not a responsive menu but a "mobile menu"', 'responsive-menu'),
          'type' => 'checkbox',
          'pro' => true
        ],
        [
          'option' => 'shortcode',
          'title' => __('Use Shortcode?', 'responsive-menu'),
          'label' => __('Please place [responsive_menu] in your files to use. Full documentation can be found <a target="_blank" href="https://responsive.menu/docs/advanced-setup/shortcode/">here</a>', 'responsive-menu'),
          'type' => 'checkbox'
        ]
    ],
    __('Animation Speeds', 'responsive-menu') =>
    [
      [
        'option' => 'animation_speed',
        'title' => __('Animation Speed', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'unit' => 's'
      ],
      [
        'option' => 'transition_speed',
        'title' => __('Transition Speed', 'responsive-menu'),
        'label' => __('', 'responsive-menu'),
        'unit' => 's'
      ]
    ],
  ],
  /*
  *
  * CUSTOM CSS
  *
  */

  __('Custom CSS', 'responsive-menu') => [
    __('CSS', 'responsive-menu') => [
        [
          'option' => 'custom_css',
          'title' => __('Custom CSS', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'textarea',
          'pro' => true
        ]
    ]
  ],
  /*
  *
  * HEADER BAR
  *
  */
  __('Header Bar', 'responsive-menu') => [
    __('Setup', 'responsive-menu') => [
        [
          'option' => 'use_header_bar',
          'title' => __('Use Header Bar', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox',
          'pro' => true
        ],
        [
          'option' => 'header_bar_position_type',
          'title' => __('Position Type', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'select' => [
              ['value' => 'fixed', 'display' => 'Fixed'],
              ['value' => 'relative', 'display' => 'Relative'],
              ['value' => 'absolute', 'display' => 'Absolute']
          ],
          'pro' => true
        ],
        [
          'option' => 'header_bar_breakpoint',
          'title' => __('Breakpoint', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px',
          'pro' => true
        ],
      ],
    __('Ordering', 'responsive-menu') => [
        [
          'option' => 'header_bar_items_order',
          'title' => __('Ordering', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'header_ordering',
          'pro' => true
        ]
      ],
    __('Logo', 'responsive-menu') => [
        [
          'option' => 'header_bar_logo',
          'title' => __('Image', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'image',
          'pro' => true
        ],
        [
          'option' => 'header_bar_logo_alt',
          'title' => __('Alt Text', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'header_bar_logo_link',
          'title' => __('Link', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
      ],
    __('Title', 'responsive-menu') => [
        [
          'option' => 'header_bar_title',
          'title' => __('Title', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ]
      ],
    __('Content', 'responsive-menu') => [
        [
          'option' => 'header_bar_html_content',
          'title' => __('HTML Content', 'responsive-menu'),
          'label' => __('Accepts shortcodes', 'responsive-menu'),
          'type' => 'textarea',
          'pro' => true
        ]
      ],
    __('Text', 'responsive-menu') => [
        [
          'option' => 'header_bar_font',
          'title' => __('Font', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'header_bar_font_size',
          'title' => __('Font Size', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'header_bar_font_size_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
      ],
    __('Sizing', 'responsive-menu') => [
        [
          'option' => 'header_bar_height',
          'title' => __('Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'header_bar_height_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ],
      ],
    __('Colours', 'responsive-menu') => [
        [
          'option' => 'header_bar_background_color',
          'title' => __('Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'header_bar_text_color',
          'title' => __('Text Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
      ],
  ],

  /*
  *
  * SINGLE MENU
  *
  */
  __('Single Menu', 'responsive-menu') => [
    __('Setup', 'responsive-menu') => [
        [
          'option' => 'use_single_menu',
          'title' => __('Use Single Menu', 'responsive-menu'),
          'label' => __('To use this option you must turn the Shortcode option on and use the shortcode in your theme where you want the menu to appear', 'responsive-menu'),
          'type' => 'checkbox',
          'pro' => true
        ]
    ],
    __('Menu Colours', 'responsive-menu') => [
        [
          'option' => 'single_menu_item_background_colour',
          'title' => __('Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_background_colour_hover',
          'title' => __('Background Hover Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_link_colour',
          'title' => __('Text Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_link_colour_hover',
          'title' => __('Text Hover Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
      ],
        __('Sub-Menu Colours', 'responsive-menu') => [
            [
              'option' => 'single_menu_item_submenu_background_colour',
              'title' => __('Background Colour', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'colour',
              'pro' => true
            ],
            [
              'option' => 'single_menu_item_submenu_background_colour_hover',
              'title' => __('Background Hover Colour', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'colour',
              'pro' => true
            ],
            [
              'option' => 'single_menu_item_submenu_link_colour',
              'title' => __('Text Colour', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'colour',
              'pro' => true
            ],
            [
              'option' => 'single_menu_item_submenu_link_colour_hover',
              'title' => __('Text Hover Colour', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'colour',
              'pro' => true
            ]
    ],
    __('Menu Styling', 'responsive-menu') => [
        [
          'option' => 'single_menu_font',
          'title' => __('Font', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'single_menu_font_size',
          'title' => __('Font Size', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'single_menu_font_size_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ]
    ],
    __('Sub-Menu Styling', 'responsive-menu') => [
        [
          'option' => 'single_menu_submenu_font',
          'title' => __('Font', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'single_menu_submenu_font_size',
          'title' => __('Font Size', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'single_menu_submenu_font_size_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ]
    ],
    __('Menu Sizing', 'responsive-menu') => [
        [
          'option' => 'single_menu_height',
          'title' => __('Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'single_menu_height_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ]
    ],
    __('Sub-Menu Sizing', 'responsive-menu') => [
        [
          'option' => 'single_menu_submenu_height',
          'title' => __('Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true,
          'sub_options' =>
          [
            [
              'option' => 'single_menu_submenu_height_unit',
              'type' => 'select',
              'select' => $unit_options
            ]
          ]
        ]
    ]
  ],
  'Import/Export' => [
    __('Import/Export', 'responsive-menu') => [
        [
          'option' => 'import',
          'title' => __('Import', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'import'
        ],
        [
          'option' => 'export',
          'title' => __('Export', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'export'
        ],
        [
          'option' => 'reset',
          'title' => __('Reset', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'reset'
        ]
    ]
  ]
];
