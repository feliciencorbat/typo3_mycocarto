<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

trait PaginationTrait
{
    /**
     * Find class objects with limit and page number, ordered by properties array
     *
     * @param int $limit
     * @param int $page
     * @param array $propertiesOrdering
     * @return QueryResultInterface
     */
    public function findPaginatedObjects(int $limit, int $page, array $propertiesOrdering): QueryResultInterface
    {
        //properties to order
        $orderingArray = [];
        foreach($propertiesOrdering as $propertyOrdering) {
            $orderingArray[$propertyOrdering] = QueryInterface::ORDER_ASCENDING;
        }

        $query = $this->createQuery();
        $query->setOrderings($orderingArray);
        $query->setLimit($limit);
        $query->setOffset(($page-1) * $limit);
        return $query->execute();
    }
}