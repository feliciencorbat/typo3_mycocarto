<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Exception;
use Feliciencorbat\Mycocarto\Domain\Model\Observation;
use Feliciencorbat\Mycocarto\Domain\Model\User;
use Feliciencorbat\Mycocarto\Domain\Repository\EcologyRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\TreeRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\UserRepository;
use Feliciencorbat\Mycocarto\Service\PdfReport;
use Psr\Http\Message\ResponseInterface;
use TCPDF;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Exception\AccessDeniedException;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

#[Controller]
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
        protected readonly PdfReport $pdfReport
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
        try {
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
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }

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
     * @param Observation $newObservation
     * @return ResponseInterface
     */
    public function createAction(Observation $newObservation): ResponseInterface
    {
        try {
            //add current user in observation
            $newObservation->setUser($this->getCurrentUser());

            $this->observationRepository->add($newObservation);
            return $this->redirect('list');

        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }
    }

    /**
     * @param Observation $observation
     * @return ResponseInterface
     */
    public function editAction(Observation $observation): ResponseInterface
    {
        try {
            $this->isAuthorized($observation);
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
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }
    }

    /**
     * @param Observation $observation
     * @return ResponseInterface
     */
    public function updateAction(Observation $observation): ResponseInterface
    {
        try {
            $this->isAuthorized($observation);
            $this->observationRepository->update($observation);
            return $this->redirect('list');
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }

    }

    /**
     * @param Observation $observation
     * @return ResponseInterface
     */
    public function deleteAction(Observation $observation): ResponseInterface
    {
        try {
            $this->isAuthorized($observation);
            $this->observationRepository->remove($observation);
            return $this->redirect('list');
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }
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
     * Generate report for mycocarto admin users
     *
     * @return ResponseInterface|void
     */
    public function reportAction()
    {
        //test if admin
        if (!$this->isAdmin($this->getCurrentUser())) {
            $this->addFlashMessage("Vous n'avez pas le droit de générer le rapport.", 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }

        $this->pdfReport->generatePdfReport();
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

    /**
     * Test if observation belongs to user or user is admin
     *
     * @param Observation $observation
     * @return void
     * @throws AccessDeniedException
     */
    private function isAuthorized(Observation $observation): void
    {
        $user = $this->getCurrentUser();
        //if not admin and not user's observation, throw exception
        if (!$this->isAdmin($user) && $observation->getUser()->getUid() != $user->getUid()) {
            throw new AccessDeniedException("Vous n'avez pas le droit de modifier cette observation.", 403);
        }
    }
}