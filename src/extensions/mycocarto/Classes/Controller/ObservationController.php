<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ObservationController extends ActionController
{
    use PaginationTrait;

    const NB_OBSERVATIONS_PER_PAGE = 10;

    public function __construct(
        protected readonly ObservationRepository $observationRepository,
    )
    {
    }

    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = ObservationController::NB_OBSERVATIONS_PER_PAGE;
        $allObservations = $this->observationRepository->findAll();
        $paginationInfos = $this->paginateObjectsList($itemsPerPage, $this->request, $allObservations);

        $paginatedObservations = $this->observationRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['name']);

        $this->view->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'observations' => $paginatedObservations
        ]);
        return $this->htmlResponse();
    }
}