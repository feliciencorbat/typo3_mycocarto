<?php

return [
    'ctrl' => [
        'title' => 'Taxon',
        'label' => 'taxon',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
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
                'foreign_table' => 'tx_systematic_domain_model_taxon',
            ],

        ],
        'taxon_level' => [
            'label' => 'Taxon level',
            'description' => 'Taxon level',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_systematic_domain_model_taxon_level',
                'required' => true,
            ],

        ],
    ],
];