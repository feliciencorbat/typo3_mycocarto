<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class EcologyRepository extends Repository
{
    public function findPaginatedEcologies(int $limit, int $page): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setOrderings(
            array(
                'name' => QueryInterface::ORDER_ASCENDING,
            )
        );
        $query->setLimit($limit);
        $query->setOffset(($page-1) * $limit);
        return $query->execute();
    }
}