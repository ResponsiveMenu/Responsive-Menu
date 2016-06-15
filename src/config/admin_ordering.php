<?php

$order_mapping = [
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
  __('Animation', 'responsive-menu') => [
    __('Anim One', 'responsive-menu') =>
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
    ]
  ],
  __('Initial Setup', 'responsive-menu') => [

  ],
  __('Button', 'responsive-menu') => [

  ],
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
  __('Technical', 'responsive-menu') => [

  ],
  __('Header Bar', 'responsive-menu') => [

  ],
  __('Item Ordering', 'responsive-menu') => [

  ],
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

  ]
];
