<?php

foreach(get_terms('nav_menu') as $menu) $menus_array[$menu->slug] = ['value' => $menu->name];

$order_mapping = [

  /*
  *
  * MENU
  *
  */

  __('Menu', 'responsive-menu') =>  [
    __('Sub Menus', 'responsive-menu') =>
      [
        [
          'option' => 'accordion_animation',
          'title' => __('Accordion Animation', 'responsive-menu'),
          'label' => __('Example label', 'responsive-menu'),
          'type' => 'checkbox'
        ],
        [
          'option' => 'accordion_animation',
          'title' => __('Accordion Animation2', 'responsive-menu'),
          'label' => __('Example label2', 'responsive-menu'),
          'type' => 'checkbox'
        ]
      ],
    __('Sub Menus 2', 'responsive-menu') =>
      [
        [
          'option' => 'accordion_animation',
          'title' => __('Accordion Animation', 'responsive-menu'),
          'label' => __('Example labdddel', 'responsive-menu'),
          'type' => 'checkbox'
        ],
        [
          'option' => 'accordion_animation',
          'title' => __('Accordion Animffffation2', 'responsive-menu'),
          'label' => __('Example labddddel2', 'responsive-menu'),
          'type' => 'checkbox'
        ]
      ]
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
          'slide' => ['value' => 'Slide'],
          'push' => ['value' => 'Push'],
          'fade' => ['value' => 'Fade']
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
          'title' => __('Use a Custom Walker?', 'responsive-menu'),
          'label' => __('Warning: For extremely advanced use only', 'responsive-menu'),
          'type' => 'checkbox'
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
