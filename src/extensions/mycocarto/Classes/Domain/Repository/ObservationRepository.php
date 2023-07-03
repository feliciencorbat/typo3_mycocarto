<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ObservationRepository extends Repository
{
    use PaginationTrait;

    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
    ];
}