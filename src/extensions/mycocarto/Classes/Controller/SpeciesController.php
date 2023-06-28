<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Feliciencorbat\Mycocarto\Domain\Model\Species;
use Feliciencorbat\Mycocarto\Domain\Repository\SpeciesRepository;
use Feliciencorbat\Mycocarto\Http\GbifSpecies;
use Feliciencorbat\Mycocarto\Service\SpeciesWithTaxa;
use GuzzleHttp\Exception\ClientException;
use http\Exception\RuntimeException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Core\Error\Http\BadRequestException;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Exception\SqlErrorException;

#[Controller]
final class SpeciesController extends ActionController
{
    const NB_SPECIES_PER_PAGE = 10;

    public function __construct(
        protected readonly SpeciesRepository $speciesRepository,
        protected readonly GbifSpecies $gbifSpecies,
        protected readonly SpeciesWithTaxa $speciesWithTaxa
    )
    {
    }


    /**
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $itemsPerPage = SpeciesController::NB_SPECIES_PER_PAGE;
        $currentPageNumber = 1;
        if ($this->request->hasArgument('page')) {
            $currentPageNumber = (int)$this->request->getArgument('page');
        }

        $allSpecies = $this->speciesRepository->findAll();
        $paginator = new QueryResultPaginator($allSpecies, $currentPageNumber, $itemsPerPage);
        $pagination = new SimplePagination($paginator);
        $paginatedSpecies = $this->speciesRepository->findPaginatedObjects($itemsPerPage, $currentPageNumber, ['genus', 'species']);

        $this->view->assignMultiple([
            'paginator' => $paginator,
            'pagination' => $pagination,
            'speciesList' => $paginatedSpecies
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
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function createAction(): ResponseInterface
    {
        try {
            $scientificName = $this->request->getArgument('scientificName');
            $species = $this->gbifSpecies->getSpeciesByScientificName($scientificName);
        } catch(JsonException|BadRequestException|RuntimeException|ClientException $e) {
            $this->addFlashMessage($e->getMessage(), 'Erreur', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('new');
        }

        $this->speciesWithTaxa->persistCompleteSpecies($species);
        return $this->redirect('list');
    }

    /**
     * @param Species $species
     * @return ResponseInterface
     */
    public function editAction(Species $species): ResponseInterface
    {
        $this->view->assign('species', $species);
        return $this->htmlResponse();
    }

    /**
     * @param Species $species
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function updateAction(Species $species): ResponseInterface
    {
        $this->speciesRepository->update($species);
        return $this->redirect('list');
    }

    /**
     * @param Species $species
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(Species $species): ResponseInterface
    {
        $this->speciesRepository->remove($species);
        return $this->redirect('list');
    }
}