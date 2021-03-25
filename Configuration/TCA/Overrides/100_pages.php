<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Configuration/TsConfig/Page/TCEMAIN.tsconfig',
        'Pizpalue - TCEMAIN'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Configuration/TsConfig/Page/TCEFORM.tsconfig',
        'Pizpalue - TCEFORM'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Configuration/TsConfig/Page/RTE.tsconfig',
        'Pizpalue - RTE'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Extensions/news/Configuration/TsConfig/Page/Pizpalue.tsconfig',
        'Pizpalue - Extension news'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Extensions/eventnews/Configuration/TsConfig/Page/Pizpalue.tsconfig',
        'Pizpalue - Extension eventnews'
    );
})();
