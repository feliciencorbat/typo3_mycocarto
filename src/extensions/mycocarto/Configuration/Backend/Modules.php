<?php

use Feliciencorbat\Mycocarto\Controller\EcologyController;
use Feliciencorbat\Mycocarto\Controller\SpeciesController;
use Feliciencorbat\Mycocarto\Controller\TreeController;


return [
    'module' => [
        'labels' => ['title' => 'MycoCarto'],
        'position' => ['after' => 'web'],
        'iconIdentifier' => 'tx_mycocarto_logo',
        'extensionName' => 'Mycocarto',
        'navigationComponent' => '',
    ],

    'species' => [
        'parent' => 'module',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/species',
        'labels' => ['title' => 'Espèces'],
        'extensionName' => 'Mycocarto',
        'iconIdentifier' => 'tx_mycocarto_logo',
        'controllerActions' => [
            SpeciesController::class => [
                'list', 'new', 'create', 'delete'
            ],
        ],
    ],

    'ecologies' => [
        'parent' => 'module',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/ecologies',
        'labels' => ['title' => 'Ecologies'],
        'extensionName' => 'Mycocarto',
        'iconIdentifier' => 'tx_mycocarto_logo',
        'controllerActions' => [
            EcologyController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
        ],
    ],

    'trees' => [
        'parent' => 'module',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/trees',
        'labels' => ['title' => 'Arbres'],
        'extensionName' => 'Mycocarto',
        'iconIdentifier' => 'tx_mycocarto_logo',
        'controllerActions' => [
            TreeController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
        ],
    ],
];