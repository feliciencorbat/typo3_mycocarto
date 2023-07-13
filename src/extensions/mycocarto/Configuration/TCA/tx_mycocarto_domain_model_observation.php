<?php

use Feliciencorbat\Mycocarto\Documentation\Userfuncs\Tca\Tca;

return [
    'ctrl' => [
        'title' => 'Observation',
        'label' => 'date',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:mycocarto/Resources/Public/Icons/icon_mycocarto.svg'
    ],
    'columns' => [
        'date' => [
            'label' => 'date',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'eval' => 'datetime',
                'required' => true,
                'size' => 20,
                'default' => 0,
            ],
        ],
        'latitude' => [
            'label' => 'latitude',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 450000,
                    'upper' => 850000
                ],
                'required' => true,
            ],
        ],
        'longitude' => [
            'label' => 'longitude',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 50000,
                    'upper' => 300000
                ],
                'required' => true,
            ],
        ],
        'ecology' => [
            'label' => 'ecology',
            'description' => 'Ecologie',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mycocarto_domain_model_ecology',
                'required' => true,
            ],
        ],
        'species' => [
            'label' => 'species',
            'description' => 'Espèce',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mycocarto_domain_model_species',
                'required' => true,
            ],
        ],

        'trees' => [
            'label' => 'scientific_name',
            'description' => 'Arbres',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'foreign_table' => 'tx_mycocarto_domain_model_tree',
                'MM' => 'tx_mycocarto_domain_model_observation_tree_mm'
            ],
        ],

        'user' => [
            'label' => 'user',
            'description' => 'Utilisateur',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'required' => true,
            ],
        ],

    ],
    'types' => [
        '0' => [
            'showitem' => 'date, latitude, longitude, ecology, species, trees, user',
        ]
    ],
];