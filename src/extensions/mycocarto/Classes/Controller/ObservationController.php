<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Observation;
use Feliciencorbat\Mycocarto\Domain\Model\User;
use Feliciencorbat\Mycocarto\Domain\Repository\EcologyRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\TreeRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

class ObservationController extends ActionController
{
    use PaginationTrait;

    const NB_OBSERVATIONS_PER_PAGE = 10;

    const ADMIN_GROUP_NAME = "mycocarto_frontend_admin";

    public function __construct(
        protected readonly ObservationRepository $observationRepository,
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly EcologyRepository $ecologyRepository,
        protected readonly TreeRepository $treeRepository,
        protected readonly UserRepository $userRepository,
    )
    {
    }

    /**
     * Convert date format
     *
     * @return void
     */
    public function initializeCreateAction(): void
    {
        $this->convertDate('newObservation');
    }

    /**
     * Convert date format
     *
     * @return void
     */
    public function initializeUpdateAction(): void
    {
        $this->convertDate('observation');
    }

    private function convertDate(string $argument): void
    {
        $this->arguments[$argument]
            ->getPropertyMappingConfiguration()
            ->forProperty('date')
            ->setTypeConverterOption(DateTimeConverter::class, DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.Y');
    }

    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $user = $this->getCurrentUser();

        // if user is admin, get all observations, else get user's observations
        $isAdmin = $this->isAdmin($user);
        if ($isAdmin) {
            $allObservations = $this->observationRepository->findAll();
        } else {
            $allObservations = $this->observationRepository->findBy(['user' => $user]);
        }

        $itemsPerPage = ObservationController::NB_OBSERVATIONS_PER_PAGE;
        $paginationInfos = $this->paginateObjectsList($itemsPerPage, $this->request, $allObservations);

        if ($isAdmin) {
            $paginatedObservations = $this->observationRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['date']);
        } else {
            $paginatedObservations = $this->observationRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['date'], $user);
        }

        $this->view->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'observations' => $paginatedObservations,
            'isAdmin' => $isAdmin
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
        //add current user in observation
        $newObservation->setUser($this->getCurrentUser());

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

    /**
     * @param Observation $observation
     * @return ResponseInterface
     */
    public function showMapAction(Observation $observation): ResponseInterface
    {
        $this->view->assignMultiple([
            'observation' => $observation,
        ]);
        return $this->htmlResponse();
    }

    /**
     * Get current user (authenticated user)
     *
     * @return User
     */
    private function getCurrentUser(): User
    {
        $authUser = $this->request->getAttribute('frontend.user');
        return $this->userRepository->findByUid($authUser->user['uid']);
    }

    /**
     * Test if user is admin (belongs to mycocarto_frontend_admin)
     *
     * @param User $user
     * @return bool
     */
    private function isAdmin(User $user): bool
    {
        foreach($user->getUsergroup()->toArray() as $userGroup) {
            if($userGroup->getTitle() == ObservationController::ADMIN_GROUP_NAME) {
                return true;
            }
        }

        return false;
    }
}