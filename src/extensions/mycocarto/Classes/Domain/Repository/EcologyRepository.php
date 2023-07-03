<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class EcologyRepository extends Repository
{
    use PaginationTrait;

    protected $defaultOrderings = [
        'name' => QueryInterface::ORDER_ASCENDING,
    ];
}