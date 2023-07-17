<?php

return [
    'ctrl' => [
        'title' => 'Niveaux du taxon',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:mycocarto/Resources/Public/Icons/icon_mycocarto.svg'
    ],
    'columns' => [
        'name' => [
            'label' => 'name',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'name',
        ]
    ],
];