<?php

namespace Feliciencorbat\Mycocarto\EventListener;

use Exception;
use Feliciencorbat\Mycocarto\Controller\SpeciesController;
use Feliciencorbat\Mycocarto\Http\GbifSpecies;
use Feliciencorbat\Mycocarto\Service\SpeciesWithTaxa;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Mvc\BeforeActionCallEvent;

class NewSpeciesListener
{
    public function __construct(
        protected readonly GbifSpecies $gbifSpecies,
        protected readonly SpeciesWithTaxa $speciesWithTaxa,
        protected readonly SpeciesController $speciesController
    ) {
    }

    public function __invoke(BeforeActionCallEvent $event): void
    {
        if ($event->getControllerClassName() == "Feliciencorbat\Mycocarto\Controller\SpeciesController" && $event->getActionMethodName() == "createAction") {

            try {
                $scientificName = $event->getPreparedArguments()[0];
                $species = $this->gbifSpecies->getSpeciesByScientificName($scientificName);
                $this->speciesWithTaxa->persistCompleteSpecies($species, "create");
            } catch(Exception $e) {
                $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                $notificationQueue = $flashMessageService->getMessageQueueByIdentifier(
                    FlashMessageQueue::NOTIFICATION_QUEUE
                );
                $message = GeneralUtility::makeInstance(FlashMessage::class,
                    $e->getMessage(),
                    "Erreur",
                    ContextualFeedbackSeverity::ERROR,
                    true
                );
                $notificationQueue->enqueue($message);
            }
        }
    }
}