<?php

return [
    'ctrl' => [
        'title' => 'Species',
        'label' => 'species',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
    ],
    'columns' => [
        'genus' => [
            'label' => 'genus',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'species' => [
            'label' => 'species',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'family_id' => [
            'label' => 'Family',
            'description' => 'Family',
            'config' => [
                'foreign_table' => 'tx_systematic_domain_model_taxon',
                'required' => true,
            ],
        ],
    ],
];