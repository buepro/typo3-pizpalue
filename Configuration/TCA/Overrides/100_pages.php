<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Configuration/TsConfig/Page/TCEFORM.tsconfig',
        'Pizpalue - Content elements'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Extensions/gridelements/Configuration/TsConfig/Page/Pizpalue.tsconfig',
        'Pizpalue - Extension gridelements'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Extensions/news/Configuration/TsConfig/Page/Pizpalue.tsconfig',
        'Pizpalue - Extension news'
    );
})();
