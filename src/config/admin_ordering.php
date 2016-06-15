<?php

foreach(get_terms('nav_menu') as $menu) $menus_array[] = [ 'value' => $menu->slug, 'display' => $menu->name];
foreach(get_registered_nav_menus() as $menu) $location_menus[] = ['value' => $menu, 'display' => $menu];
$location_menus[] = ['value' => '', 'display' => 'None'];

$order_mapping = [

  /*
  *
  * MENU
  *
  */

  __('Menu', 'responsive-menu') =>  [
    __('Sub Menu Animations', 'responsive-menu') =>
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
      ],
    __('Sub Menu Styling', 'responsive-menu') =>
      [
        [
          'option' => 'submenu_arrow_height',
          'title' => __('Sub-Menu Arrow Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px'
        ],
        [
          'option' => 'submenu_arrow_width',
          'title' => __('Sub-Menu Arrow width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px'
        ],

      ],
    __('Sub Menu Arrows', 'responsive-menu') =>
      [
        [
          'option' => 'active_arrow_font_icon',
          'title' => __('Active Arrow Font Icon', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'inactive_arrow_font_icon',
          'title' => __('Inactive Arrow Font Icon', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'active_arrow_shape',
          'title' => __('Active Arrow Shape', 'responsive-menu'),
          'label' => __('', 'responsive-menu')
        ],
        [
          'option' => 'inactive_arrow_shape',
          'title' => __('Inactive Arrow Shape', 'responsive-menu'),
          'label' => __('', 'responsive-menu')
        ],
        [
          'option' => 'active_arrow_image',
          'title' => __('Active Arrow Image', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'image'
        ],
        [
          'option' => 'inactive_arrow_image',
          'title' => __('Inactive Arrow Image', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'image'
        ]
      ],
    __('Sizing', 'responsive-menu') =>
      [
        [
          'option' => 'menu_maximum_width',
          'title' => __('Maximum Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px'
        ],
        [
          'option' => 'menu_minimum_width',
          'title' => __('Minimum Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px'
        ],
      ],
    __('Advanced', 'responsive-menu') =>
      [
        [
          'option' => 'menu_auto_height',
          'title' => __('Menu Auto Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
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
          'option' => 'menu_item_click_to_trigger_submenu',
          'title' => __('Disable Parent Item Clicks', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox'

        ],
        [
          'option' => 'menu_overlay',
          'title' => __('Add Overlay When Menu Open', 'responsive-menu'),
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
        [
          'option' => 'menu_word_wrap',
          'title' => __('Word Wrap', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox',
          'pro' => true
        ],
        [
          'option' => 'theme_location_menu',
          'title' => __('Theme Location Menu', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'select' => [$location_menus]
        ]
      ],
    __('General', 'responsive-menu') =>
      [
        [
          'option' => 'menu_appear_from',
          'title' => __('Menu Appear From', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'select',
          'select' => [
            ['value' => 'top', 'display' => 'Top'],
            ['value' => 'left', 'display' => 'Left'],
            ['value' => 'right', 'display' => 'Right'],
            ['value' => 'bottom', 'display' => 'Bottom']
          ]
        ],
        [
          'option' => 'menu_search_box_text',
          'title' => __('Menu Search Box Text', 'responsive-menu'),
          'label' => __('', 'responsive-menu')
        ],
        [
          'option' => 'menu_title',
          'title' => __('Menu Title', 'responsive-menu'),
          'label' => __('', 'responsive-menu')
        ],
        [
          'option' => 'menu_title_font_icon',
          'title' => __('Menu Title Font Icon', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'menu_title_image',
          'title' => __('Menu Title Image', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'image'
        ],
        [
          'option' => 'menu_title_link',
          'title' => __('Menu Title Link', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
        ],
        [
          'option' => 'menu_title_link_location',
          'title' => __('Menu Title Link Location', 'responsive-menu'),
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
          'option' => 'menu_width',
          'title' => __('Menu Width', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px'
        ],
      ],
    __('Background Colours', 'responsive-menu') =>
      [
        [
          'option' => 'menu_background_colour',
          'title' => __('Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_current_item_background_colour',
          'title' => __('Current Item Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_current_item_background_hover_colour',
          'title' => __('Current Item Background Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_item_background_colour',
          'title' => __('Item Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_item_background_hover_colour',
          'title' => __('Item Background Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_item_border_colour',
          'title' => __('Item Border Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_title_background_colour',
          'title' => __('Title Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ],
        [
          'option' => 'menu_title_background_hover_colour',
          'title' => __('Title Background Colour Hover', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour'
        ]
      ],
      __('Text Colours', 'responsive-menu') =>
        [
          [
            'option' => 'menu_current_link_colour',
            'title' => __('Current Link Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour'
          ],
          [
            'option' => 'menu_current_link_hover_colour',
            'title' => __('Current Link Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour'
          ],
          [
            'option' => 'menu_link_colour',
            'title' => __('Link Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour'
          ],
          [
            'option' => 'menu_link_hover_colour',
            'title' => __('Link Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour'
          ],
          [
            'option' => 'menu_title_colour',
            'title' => __('Title Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour'
          ],
          [
            'option' => 'menu_title_hover_colour',
            'title' => __('Title Hover Colour', 'responsive-menu'),
            'label' => __('', 'responsive-menu'),
            'type' => 'colour'
          ],
        ],
        __('Styling', 'responsive-menu') =>
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
              'unit' => 'px'
            ],
            [
              'option' => 'menu_links_height',
              'title' => __('Links Height', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'unit' => 'px'
            ],
            [
              'option' => 'menu_text_alignment',
              'title' => __('Text Alignment', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'type' => 'select',
              'select' => [
                ['value' => 'left', 'display' => 'Left'],
                ['value' => 'right', 'display' => 'Right']
              ]
            ],
            [
              'option' => 'menu_title_font_size',
              'title' => __('Title Font Size', 'responsive-menu'),
              'label' => __('', 'responsive-menu'),
              'unit' => 'px'
            ],
          ],
          __('Font Icons', 'responsive-menu') =>
            [
              [
                'option' => 'menu_font_icons',
                'title' => __('Font Icons', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'fonticons'
              ]
            ],
          __('Sub-Arrow Colours', 'responsive-menu') =>
            [
              [
                'option' => 'menu_sub_arrow_background_colour',
                'title' => __('Background Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour'
              ],
              [
                'option' => 'menu_sub_arrow_background_hover_colour',
                'title' => __('Background Hover Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour'
              ],
              [
                'option' => 'menu_sub_arrow_border_colour',
                'title' => __('Border Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour'
              ],
              [
                'option' => 'menu_sub_arrow_border_hover_colour',
                'title' => __('Border Hover Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour'
              ],
              [
                'option' => 'menu_sub_arrow_shape_colour',
                'title' => __('Shape Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour'
              ],
              [
                'option' => 'menu_sub_arrow_shape_hover_colour',
                'title' => __('Shape Hover Colour', 'responsive-menu'),
                'label' => __('', 'responsive-menu'),
                'type' => 'colour'
              ],
            ],
  ],

  /*
  *
  * ANIMATION
  *
  */

  __('Animation', 'responsive-menu') => [
    __('Speed', 'responsive-menu') =>
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
    __('Type', 'responsive-menu') =>
    [
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
        'label' => __('This is only needed if you are using the push animation', 'responsive-menu')
      ]
    ]
  ],

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
        'select' => [
          $menus_array
        ]
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
  * BUTTON
  *
  */

  __('Button', 'responsive-menu') => [

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
          'label' => __('Please place [responsive_menu] in your files to use', 'responsive-menu'),
          'type' => 'checkbox'
        ]
    ]
  ],

  /*
  *
  * HEADER BAR
  *
  */
  __('Header Bar', 'responsive-menu') => [

  ],

  /*
  *
  * ITEMS ORDERING
  *
  */
  __('Item Ordering', 'responsive-menu') => [
    __('Order', 'responsive-menu') => [
        [
          'option' => 'items_order',
          'title' => __('Order of Menu Items', 'responsive-menu'),
          'label' => __('Drag the items to re-order and click to turn them on/off', 'responsive-menu'),
          'type' => 'menu_ordering'
        ]
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
          'label' => __('', 'responsive-menu'),
          'type' => 'checkbox',
          'pro' => true
        ]
    ],
    __('Main Menu Colours', 'responsive-menu') => [
        [
          'option' => 'single_menu_item_background_colour',
          'title' => __('Link Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_background_colour_hover',
          'title' => __('Link Background Colour (Hover)', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_link_colour',
          'title' => __('Link Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_link_colour_hover',
          'title' => __('Link Colour (Hover)', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ]
    ],
    __('Sub-Menu Colours', 'responsive-menu') => [
        [
          'option' => 'single_menu_item_submenu_background_colour',
          'title' => __('Link Background Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_submenu_background_colour_hover',
          'title' => __('Link Background Colour (Hover)', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_submenu_link_colour',
          'title' => __('Link Colour', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ],
        [
          'option' => 'single_menu_item_submenu_link_colour_hover',
          'title' => __('Link Colour (Hover)', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'type' => 'colour',
          'pro' => true
        ]
    ],
    __('Main Menu Styling', 'responsive-menu') => [
        [
          'option' => 'single_menu_font',
          'title' => __('Font Name', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'single_menu_font_size',
          'title' => __('Font Size', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px',
          'pro' => true
        ]
    ],
    __('Sub-Menu Styling', 'responsive-menu') => [
        [
          'option' => 'single_menu_submenu_font',
          'title' => __('Font Name', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'pro' => true
        ],
        [
          'option' => 'single_menu_submenu_font_size',
          'title' => __('Font Size', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px',
          'pro' => true
        ]
    ],
    __('Main Menu Sizing', 'responsive-menu') => [
        [
          'option' => 'single_menu_height',
          'title' => __('Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px',
          'pro' => true
        ]
    ],
    __('Sub-Menu Menu Sizing', 'responsive-menu') => [
        [
          'option' => 'single_menu_submenu_height',
          'title' => __('Height', 'responsive-menu'),
          'label' => __('', 'responsive-menu'),
          'unit' => 'px',
          'pro' => true
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
        ]
    ]
  ]
];
