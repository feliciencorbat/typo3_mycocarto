<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class SpeciesRepository extends Repository
{
    use PaginationTrait;

    protected $defaultOrderings = [
        'genus' => QueryInterface::ORDER_ASCENDING,
        'species' => QueryInterface::ORDER_ASCENDING,
    ];
}