<?php

use Feliciencorbat\Mycocarto\Controller\EcologyController;
use Feliciencorbat\Mycocarto\Controller\SpeciesController;
use Feliciencorbat\Mycocarto\Controller\TreeController;


return [
    'mycocarto' => [
        'labels' => ['title' => 'MycoCarto'],
        'position' => ['after' => 'web'],
        'iconIdentifier' => 'tx_mycocarto_logo',
        'navigationComponent' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
    ],

    'tx_mycocarto_species' => [
        'parent' => 'mycocarto',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/species',
        'labels' => ['title' => 'Espèces'],
        'extensionName' => 'mycocarto',
        'iconIdentifier' => 'tx_mycocarto_logo',
        'controllerActions' => [
            SpeciesController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
        ],
    ],

    'tx_mycocarto_ecologies' => [
        'parent' => 'mycocarto',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/ecologies',
        'labels' => ['title' => 'Ecologies'],
        'extensionName' => 'mycocarto',
        'iconIdentifier' => 'tx_mycocarto_logo',
        'controllerActions' => [
            EcologyController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
        ],
    ],

    'tx_mycocarto_trees' => [
        'parent' => 'mycocarto',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/trees',
        'labels' => ['title' => 'Arbres'],
        'extensionName' => 'mycocarto',
        'iconIdentifier' => 'tx_mycocarto_logo',
        'controllerActions' => [
            TreeController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
        ],
    ],
];