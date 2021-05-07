<?php

return [
    'default' => 'bliss',

    'themes' => [
        'default' => [
            'views_path' => 'resources/themes/default/views',
            'assets_path' => 'public_html/themes/default/assets',
            'name' => 'Default'
        ],

        'bliss' => [
            'views_path' => 'resources/themes/bliss/views',
            'assets_path' => 'public_html/themes/bliss/assets',
            'name' => 'Bliss',
            'parent' => 'default'
        ],

        'velocity' => [
            'views_path' => 'resources/themes/velocity/views',
            'assets_path' => 'public_html/themes/velocity/assets',
            'name' => 'Velocity',
            'parent' => 'default'
        ],
    ]
];