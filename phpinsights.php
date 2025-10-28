<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default preset
    |--------------------------------------------------------------------------
    |
    | You can use one of the built-in presets:
    | 'default', 'laravel', 'symfony', 'magento2', 'drupal', ...
    |
    */
    'preset' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | IDE link (optional)
    |--------------------------------------------------------------------------
    */
    'ide' => null,

    /*
    |--------------------------------------------------------------------------
    | Directories / files to exclude
    |--------------------------------------------------------------------------
    */
    'exclude' => [
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
        'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | Add/remove insights
    |--------------------------------------------------------------------------
    */
    'add' => [
        // Ajouter des insights si nécessaire, ex: 'App\\Insights\\MyCustomInsight'
    ],

    'remove' => [
        // Retirer des insights si tu veux alléger les règles
        // ex: 'SlevomatCodingStandard.Classes.UnusedPrivateElements',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurations spécifiques aux insights
    |--------------------------------------------------------------------------
    */
    'config' => [
        // Exemple : configurer un insight spécifique
        // 'SlevomatCodingStandard.Classes.ClassConstantVisibility' => [
        //     'public' => true,
        // ],
    ],
];
