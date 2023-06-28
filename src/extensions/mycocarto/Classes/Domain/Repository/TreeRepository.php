<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class TreeRepository extends Repository
{
    use PaginationTrait;
}