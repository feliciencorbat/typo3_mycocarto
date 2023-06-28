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
        'author' => [
            'label' => 'author',
            'config' => [
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'family' => [
            'label' => 'family',
            'description' => 'Family',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_systematic_domain_model_taxon',
                'required' => true,
            ],
        ],
    ],
];