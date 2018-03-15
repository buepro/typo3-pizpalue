<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'pizpalue',
    'Configuration/PageTS/CE.txt',
    'Pizpalue: Content element'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'pizpalue',
    'Configuration/PageTS/Grids.txt',
    'Pizpalue: Grid content elements'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'pizpalue',
    'Extensions/news/Configuration/PageTS/General.txt',
    'Pizpalue: Extension news'
);
