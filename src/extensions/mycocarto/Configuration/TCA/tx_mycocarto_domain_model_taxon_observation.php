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
                'type' => 'input',
                'size' => 250,
                'eval' => 'trim',
                'required' => true,
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
        'ecology_id' => [
            'label' => 'ecology',
            'description' => 'Ecology',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_systematic_domain_model_ecology',
                'required' => true,
            ],
        ],
        'species_id' => [
            'label' => 'species',
            'description' => 'Species',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_systematic_domain_model_species',
                'required' => true,
            ],
        ],
    ],
];