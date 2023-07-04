<?php

namespace Feliciencorbat\Mycocarto\Documentation\Userfuncs\Tca;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class Tca
{
    public function completeSpeciesTitle(&$parameters): void
    {
        $record = BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
        $newTitle = $record['genus'] . " " . $record['species'];
        $parameters['title'] = $newTitle;
    }

    public function completeObservationTitle(&$parameters): void
    {
        $record = BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
        $newTitle = date("d.m.Y", $record['date']) . " - " . $record['species'];
        $parameters['title'] = $newTitle;
    }
}