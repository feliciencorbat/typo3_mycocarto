<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Ecology;
use Feliciencorbat\Mycocarto\Domain\Repository\EcologyRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

#[Controller]
final class EcologyController extends ActionController
{
    use PaginationTrait;

    const NB_ECOLOGIES_PER_PAGE = 10;

    public function __construct(
        protected readonly EcologyRepository $ecologyRepository,
    )
    {
    }


    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = EcologyController::NB_ECOLOGIES_PER_PAGE;
        $allEcologies = $this->ecologyRepository->findAll();
        $paginationInfos = $this->paginateObjectsList($itemsPerPage, $this->request, $allEcologies);

        $paginatedEcologies = $this->ecologyRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['name']);

        $this->view->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'ecologies' => $paginatedEcologies
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
    public function createAction(Ecology $newEcology): ResponseInterface
    {
        $this->ecologyRepository->add($newEcology);
        return $this->redirect('list');
    }

    /**
     * @param Ecology $ecology
     * @return ResponseInterface
     */
    public function editAction(Ecology $ecology): ResponseInterface
    {
        $this->view->assign('ecology', $ecology);
        return $this->htmlResponse();
    }

    /**
     * @param Ecology $ecology
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function updateAction(Ecology $ecology): ResponseInterface
    {
        $this->ecologyRepository->update($ecology);
        return $this->redirect('list');
    }

    /**
     * @param Ecology $ecology
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(Ecology $ecology): ResponseInterface
    {
        $this->ecologyRepository->remove($ecology);
        return $this->redirect('list');
    }
}