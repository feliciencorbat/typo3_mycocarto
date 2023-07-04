<?php

use Feliciencorbat\Mycocarto\Documentation\Userfuncs\Tca\Tca;

return [
    'ctrl' => [
        'title' => 'Species',
        'label' => 'species',
        'label_userFunc' => Tca::class . '->completeSpeciesTitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:mycocarto/Resources/Public/Icons/icon_mycocarto.svg'
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
                'foreign_table' => 'tx_mycocarto_domain_model_taxon',
                'required' => true,
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'genus, species, author, family',
        ]
    ],
];