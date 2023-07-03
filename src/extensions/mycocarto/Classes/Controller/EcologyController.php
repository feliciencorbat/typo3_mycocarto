<?php

namespace Feliciencorbat\Mycocarto\Controller;

use Exception;
use Feliciencorbat\Mycocarto\Domain\Model\Ecology;
use Feliciencorbat\Mycocarto\Domain\Repository\EcologyRepository;
use Feliciencorbat\Mycocarto\Domain\Repository\ObservationRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
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
        protected readonly ObservationRepository $observationRepository,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
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

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple([
            'paginator' => $paginationInfos[0],
            'pagination' => $paginationInfos[1],
            'ecologies' => $paginatedEcologies
        ]);
        return $moduleTemplate->renderResponse();
    }

    /**
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        return $moduleTemplate->renderResponse();
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
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assign('ecology', $ecology);
        return $moduleTemplate->renderResponse();
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
     */
    public function deleteAction(Ecology $ecology): ResponseInterface
    {
        try {
            $this->observationRepository->testIfEcologyExistsInObservation($ecology);
            $this->ecologyRepository->remove($ecology);
        } catch(Exception $e) {
            $this->addFlashMessage($e->getMessage(),'Erreur', ContextualFeedbackSeverity::ERROR);
        } finally {
            return $this->redirect('list');
        }
    }
}