<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Exception;
use Feliciencorbat\Mycocarto\Domain\Model\Species;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Http\GbifSpecies;
use Feliciencorbat\Mycocarto\Service\SpeciesWithTaxa;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;

#[Controller]
final class SpeciesController extends ActionController
{
    use PaginationTrait;

    const NB_SPECIES_PER_PAGE = 10;

    /**
     * @var JsonView
     */
    protected $defaultViewObjectName = JsonView::class;


    public function __construct(
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly ObservationRepository $observationRepository,
        protected readonly GbifSpecies $gbifSpecies,
        protected readonly SpeciesWithTaxa $speciesWithTaxa,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    ) {
    }


    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = SpeciesController::NB_SPECIES_PER_PAGE;
        $allSpecies = $this->speciesRepository->findAll();
        $paginationInfos = $this->paginateObjectsList($itemsPerPage, $this->request, $allSpecies);

        $paginatedSpecies = $this->speciesRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['genus' => "ASC", 'species' => "ASC"]);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple(
            [
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'speciesList' => $paginatedSpecies
            ]
        );
        return $moduleTemplate->renderResponse();
    }

    /**
     * @return ResponseInterface
     */
    #[IgnoreValidation(['argumentName' => 'newSpecies'])]
    public function newAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        return $moduleTemplate->renderResponse();
    }

    /**
     * @param string $scientificName
     * @return ResponseInterface
     */
    public function createAction(string $scientificName): ResponseInterface
    {
        return $this->redirect('list');
    }

    /**
     * @param  Species $species
     * @return ResponseInterface
     */
    public function updateAction(Species $species): ResponseInterface
    {
        try {
            $species = $this->gbifSpecies->getSpeciesByScientificName($species->getCanonicalName());
            $this->speciesWithTaxa->persistCompleteSpecies($species, "update");
            return $this->redirect('list');
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list');
        }
    }

    /**
     * @param  Species $species
     * @return ResponseInterface
     */
    public function deleteAction(Species $species): ResponseInterface
    {
        try {
            $this->observationRepository->testIfSpeciesExistsInObservation($species);
            $this->speciesRepository->remove($species);
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
        } finally {
            return $this->redirect('list');
        }
    }

    public function getSpeciesByQueryAction(): ResponseInterface
    {
        try {
            $params = $this->request->getQueryParams();
            $search = $params["term"];
            $speciesList = $this->speciesRepository->getSpeciesByQuery($search);
            $jsonOutput = json_encode($speciesList);
            return $this->jsonResponse($jsonOutput);
        } catch (Exception $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('list', 'Observation');
        }
    }
}
