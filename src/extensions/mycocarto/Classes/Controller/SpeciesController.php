<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Exception;
use Feliciencorbat\Mycocarto\Domain\Model\Species;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Http\GbifSpecies;
use Feliciencorbat\Mycocarto\Service\SpeciesWithTaxa;
use GuzzleHttp\Exception\ClientException;
use http\Exception\RuntimeException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Error\Http\BadRequestException;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

#[Controller]
final class SpeciesController extends ActionController
{
    use PaginationTrait;

    const NB_SPECIES_PER_PAGE = 10;

    public function __construct(
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly ObservationRepository $observationRepository,
        protected readonly GbifSpecies $gbifSpecies,
        protected readonly SpeciesWithTaxa $speciesWithTaxa,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    )
    {
    }


    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = SpeciesController::NB_SPECIES_PER_PAGE;
        $allSpecies = $this->speciesRepository->findAll();
        $paginationInfos = $this->paginateObjectsList($itemsPerPage, $this->request, $allSpecies);

        $paginatedSpecies = $this->speciesRepository->findPaginatedObjects($itemsPerPage, $paginationInfos[2], ['genus', 'species']);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'speciesList' => $paginatedSpecies
        ]);
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
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function createAction(): ResponseInterface
    {
        try {
            $scientificName = $this->request->getArgument('scientificName');
            $species = $this->gbifSpecies->getSpeciesByScientificName($scientificName);
            $this->speciesWithTaxa->persistCompleteSpecies($species);
            return $this->redirect('list');
        } catch(JsonException|BadRequestException|RuntimeException|ClientException $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('new');
        }
    }

    /**
     * @param Species $species
     * @return ResponseInterface
     */
    public function deleteAction(Species $species): ResponseInterface
    {
        try {
            $this->observationRepository->testIfSpeciesExistsInObservation($species);
            $this->speciesRepository->remove($species);
        } catch(Exception $e) {
            $this->addFlashMessage($e->getMessage(),'Erreur', ContextualFeedbackSeverity::ERROR);
        } finally {
            return $this->redirect('list');
        }
    }
}