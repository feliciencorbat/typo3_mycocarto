<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Ecology;
use Feliciencorbat\Mycocarto\Domain\Model\Observation;
use Feliciencorbat\Mycocarto\Domain\Repository\EcologyRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

class ObservationController extends ActionController
{
    use PaginationTrait;

    const NB_OBSERVATIONS_PER_PAGE = 10;

    public function __construct(
        protected readonly ObservationRepository $observationRepository,
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly EcologyRepository $ecologyRepository,
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

        $paginatedObservations = $this->observationRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['date']);

        $this->view->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'observations' => $paginatedObservations
        ]);
        return $this->htmlResponse();
    }

    /**
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $this->speciesRepository->setDefaultQuerySettings($this->speciesRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $speciesList = $this->speciesRepository->findAll();
        $this->ecologyRepository->setDefaultQuerySettings($this->ecologyRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $ecologies = $this->ecologyRepository->findAll();
        $this->view->assignMultiple([
            'speciesList' => $speciesList,
            'ecologies' => $ecologies,
        ]);
        return $this->htmlResponse();
    }

    /**
     * @throws IllegalObjectTypeException
     */
    public function createAction(Observation $newObservation): ResponseInterface
    {
        $this->observationRepository->add($newObservation);
        return $this->redirect('list');
    }

    /**
     * @param Ecology $ecology
     * @return ResponseInterface
     */
    public function editAction(Observation $observation): ResponseInterface
    {
        $this->speciesRepository->setDefaultQuerySettings($this->speciesRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $speciesList = $this->speciesRepository->findAll();
        $this->ecologyRepository->setDefaultQuerySettings($this->ecologyRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $ecologies = $this->ecologyRepository->findAll();
        $this->view->assignMultiple([
            'speciesList' => $speciesList,
            'ecologies' => $ecologies,
            'observation' => $observation
        ]);
        return $this->htmlResponse();
    }

    /**
     * @param Observation $observation
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function updateAction(Observation $observation): ResponseInterface
    {
        $this->observationRepository->update($observation);
        return $this->redirect('list');
    }

    /**
     * @param Observation $observation
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(Observation $observation): ResponseInterface
    {
        $this->observationRepository->remove($observation);
        return $this->redirect('list');
    }
}