<?php

return [
    'ctrl' => [
        'title' => 'Observation',
        'label' => 'observation',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
    ],
    'columns' => [
        'date' => [
            'label' => 'date',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'latitude' => [
            'label' => 'latitude',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'longitude' => [
            'label' => 'longitude',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'ecology' => [
            'exclude' => false,
            'label' => 'ecology',
            'description' => 'Ecology',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mycocarto_domain_model_ecology',
                'required' => true,
                'default' => 0,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'species' => [
            'exclude' => false,
            'label' => 'species',
            'description' => 'Species',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mycocarto_domain_model_species',
                'required' => true,
                'default' => 0,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
    ],
];