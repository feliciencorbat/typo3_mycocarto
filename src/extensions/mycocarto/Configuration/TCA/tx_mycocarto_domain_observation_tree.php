<?php

return [
    'ctrl' => [
        'title' => 'Observation trees',
        'label' => 'observation trees',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
    ],
    'columns' => [
        'observation_id' => [
            'label' => 'observation',
            'description' => 'Observation',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_systematic_domain_model_observation',
                'required' => true,
            ],
        ],
        'tree_id' => [
            'label' => 'tree',
            'description' => 'Tree',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_systematic_domain_model_tree',
                'required' => true,
            ],
        ],
    ],
];