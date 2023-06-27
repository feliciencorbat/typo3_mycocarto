<?php

use Feliciencorbat\Mycocarto\Controller\EcologyController;

return [
    'tx_mycocarto_ecologies' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/ecologies',
        'labels' => ['Ecologie', 'Ecologie', 'Ecologie'],
        'extensionName' => 'mycocarto',
        'controllerActions' => [
            EcologyController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
        ],
    ],
];