<?php

return [
    'ctrl' => [
        'title' => 'Taxons',
        'label' => 'scientific_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:mycocarto/Resources/Public/Icons/icon_mycocarto.svg'
    ],
    'columns' => [
        'scientific_name' => [
            'label' => 'scientific_name',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'parent_taxon' => [
            'label' => 'Parent taxon',
            'description' => 'Parent taxon',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mycocarto_domain_model_taxon',
            ],

        ],
        'taxon_level' => [
            'label' => 'Taxon level',
            'description' => 'Taxon level',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mycocarto_domain_model_taxonlevel',
                'required' => true,
            ],

        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'scientific_name, parent_taxon, taxon_level',
        ]
    ],
];
