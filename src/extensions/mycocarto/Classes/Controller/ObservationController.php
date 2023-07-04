<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Ecology;
use Feliciencorbat\Mycocarto\Domain\Model\Observation;
use Feliciencorbat\Mycocarto\Domain\Repository\EcologyRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\TreeRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

class ObservationController extends ActionController
{
    use PaginationTrait;

    const NB_OBSERVATIONS_PER_PAGE = 10;

    public function __construct(
        protected readonly ObservationRepository $observationRepository,
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly EcologyRepository $ecologyRepository,
        protected readonly TreeRepository $treeRepository,
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
        $this->treeRepository->setDefaultQuerySettings($this->treeRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $trees = $this->treeRepository->findAll();
        $this->view->assignMultiple([
            'speciesList' => $speciesList,
            'ecologies' => $ecologies,
            'trees' => $trees,
        ]);
        return $this->htmlResponse();
    }

    /**
     * @throws IllegalObjectTypeException
     */
    public function createAction(Observation $newObservation): ResponseInterface
    {
        //var_dump($newObservation->getTrees());
        //die;
        $this->observationRepository->add($newObservation);
        return $this->redirect('list');
    }

    /**
     * @param Observation $observation
     * @return ResponseInterface
     */
    public function editAction(Observation $observation): ResponseInterface
    {
        $this->speciesRepository->setDefaultQuerySettings($this->speciesRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $speciesList = $this->speciesRepository->findAll();
        $this->ecologyRepository->setDefaultQuerySettings($this->ecologyRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $ecologies = $this->ecologyRepository->findAll();
        $this->treeRepository->setDefaultQuerySettings($this->treeRepository->createQuery()->getQuerySettings()->setRespectStoragePage(false));
        $trees = $this->treeRepository->findAll();
        $this->view->assignMultiple([
            'speciesList' => $speciesList,
            'ecologies' => $ecologies,
            'observation' => $observation,
            'trees' => $trees
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