<?php

use Feliciencorbat\Mycocarto\Controller\EcologyController;


return [
    'mycocarto' => [
        'labels' => ['title' => 'MycoCarto'],
        'position' => ['after' => 'web'],
        'iconIdentifier' => 'tx_mycocarto_logo',
        'navigationComponent' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
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
];