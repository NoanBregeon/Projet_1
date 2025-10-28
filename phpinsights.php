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
        'tests', // Exclure les tests pour éviter les conflits
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
        'NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits', // Permet l'utilisation des traits dans les tests
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
        'PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff' => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
        ],
    ],
];
