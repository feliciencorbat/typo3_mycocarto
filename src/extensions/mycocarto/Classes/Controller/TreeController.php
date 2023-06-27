<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Tree;
use Feliciencorbat\Mycocarto\Domain\Repository\TreeRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

#[Controller]
final class TreeController extends ActionController
{
    const NB_TREES_PER_PAGE = 10;

    public function __construct(
        protected readonly TreeRepository $treeRepository,
    )
    {
    }


    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = TreeController::NB_TREES_PER_PAGE;
        $currentPageNumber = 1;
        if ($this->request->hasArgument('page')) {
            $currentPageNumber = (int)$this->request->getArgument('page');
        }

        $allTrees = $this->treeRepository->findAll();
        $paginator = new QueryResultPaginator($allTrees, $currentPageNumber, $itemsPerPage);
        $pagination = new SimplePagination($paginator);
        $paginatedTrees = $this->treeRepository->findPaginatedTrees($itemsPerPage, $currentPageNumber);

        $this->view->assignMultiple([
            'paginator' => $paginator,
            'pagination' => $pagination,
            'trees' => $paginatedTrees
        ]);
        return $this->htmlResponse();
    }

    /**
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * @throws IllegalObjectTypeException
     */
    public function createAction(Tree $newTree): ResponseInterface
    {
        $this->treeRepository->add($newTree);
        return $this->redirect('list');
    }

    /**
     * @param Tree $tree
     * @return ResponseInterface
     */
    public function editAction(Tree $tree): ResponseInterface
    {
        $this->view->assign('tree', $tree);
        return $this->htmlResponse();
    }

    /**
     * @param Tree $tree
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function updateAction(Tree $tree): ResponseInterface
    {
        $this->treeRepository->update($tree);
        return $this->redirect('list');
    }

    /**
     * @param Tree $tree
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(Tree $tree): ResponseInterface
    {
        $this->treeRepository->remove($tree);
        return $this->redirect('list');
    }
}