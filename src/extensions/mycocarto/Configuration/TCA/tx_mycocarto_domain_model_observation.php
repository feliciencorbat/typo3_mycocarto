<?php

use Feliciencorbat\Mycocarto\Documentation\Userfuncs\Tca\Tca;

return [
    'ctrl' => [
        'title' => 'Observation',
        'label' => 'species',
        'label_userFunc' => Tca::class . '->completeObservationTitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:mycocarto/Resources/Public/Icons/icon_mycocarto.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
        ],
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
                'type' => 'input',
                'size' => 50,
                'eval' => 'double2',
                'required' => true,
            ],
        ],
        'longitude' => [
            'label' => 'longitude',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'double2',
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
        /*
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
        */
    ],
    'types' => [
        '0' => [
            'showitem' => 'date, latitude, longitude, ecology, species, trees',
        ]
    ],
];