<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Tree;
use Feliciencorbat\Mycocarto\Domain\Repository\TreeRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

#[Controller]
final class TreeController extends ActionController
{
    use PaginationTrait;

    const NB_TREES_PER_PAGE = 10;

    public function __construct(
        protected readonly TreeRepository $treeRepository,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    )
    {
    }


    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = TreeController::NB_TREES_PER_PAGE;
        $allTrees = $this->treeRepository->findAll();
        $paginationInfos = $this->paginateObjectsList($itemsPerPage, $this->request, $allTrees);

        $paginatedTrees = $this->treeRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['scientificName']);

        $this->view->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'trees' => $paginatedTrees
        ]);
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
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
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
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