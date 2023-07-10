<?php

declare(strict_types=1);

use Feliciencorbat\Mycocarto\Controller\ObservationController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
// extension name, matching the PHP namespaces (but without the vendor)
    'mycocarto',
    // arbitrary, but unique plugin name (not visible in the backend)
    'Observations',
    // all actions
    [ObservationController::class => 'list, new, create, edit, update, delete, showMap'],
    // non-cacheable actions
    [ObservationController::class => 'list, new, create, edit, update, delete, showMap'],
);

call_user_func(function()
{
    ExtensionManagementUtility::addTypoScript(
        'mycocarto',
        'constants',
        "@import 'EXT:mycocarto/Configuration/TypoScript/constants.typoscript'"
    );
});

call_user_func(function()
{
    ExtensionManagementUtility::addTypoScript(
        'mycocarto',
        'setup',
        "@import 'EXT:mycocarto/Configuration/TypoScript/setup.typoscript'"
    );
});