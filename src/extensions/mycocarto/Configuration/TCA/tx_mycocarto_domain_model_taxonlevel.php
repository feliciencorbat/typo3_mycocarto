<?php

return [
    'ctrl' => [
        'title' => 'taxonlevel',
        'label' => 'taxonlevel',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
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
];