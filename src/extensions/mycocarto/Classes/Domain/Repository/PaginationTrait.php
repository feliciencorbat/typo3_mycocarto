<?php

namespace Feliciencorbat\Mycocarto\Domain\Repository;

use Feliciencorbat\Mycocarto\Domain\Model\User;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

trait PaginationTrait
{
    /**
     * Find class objects with limit and page number, ordered by properties array
     *
     * @param  int       $limit
     * @param  int       $page
     * @param  array     $propertiesOrdering
     * @param  User|null $user
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findPaginatedObjects(int $limit, int $page, array $propertiesOrdering, ?User $user = null): QueryResultInterface
    {
        //properties to order
        $orderingArray = [];
        foreach ($propertiesOrdering as $key => $propertyOrdering) {
            if ($propertyOrdering == "ASC") {
                $orderingArray[$key] = QueryInterface::ORDER_ASCENDING;
            } else {
                $orderingArray[$key] = QueryInterface::ORDER_DESCENDING;
            }
        }

        $query = $this->createQuery();

        //if get observations by non-admin user, get user's observations only
        if ($user != null) {
            $query->matching($query->equals('user', $user));
        }

        $query->setOrderings($orderingArray);
        $query->setLimit($limit);
        $query->setOffset(($page-1) * $limit);
        return $query->execute();
    }
}
