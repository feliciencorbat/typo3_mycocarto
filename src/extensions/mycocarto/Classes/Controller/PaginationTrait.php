<?php

namespace Feliciencorbat\Mycocarto\Controller;

use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

trait PaginationTrait {

    public function paginateObjectsList(int $itemsPerPage, RequestInterface $request, QueryResultInterface $allObjects): array
    {
        $currentPageNumber = 1;
        if ($request->hasArgument('page')) {
            $currentPageNumber = (int)$request->getArgument('page');
        }

        $paginator = new QueryResultPaginator($allObjects, $currentPageNumber, $itemsPerPage);
        $pagination = new SimplePagination($paginator);

        return [$paginator, $pagination, $currentPageNumber];
    }

}