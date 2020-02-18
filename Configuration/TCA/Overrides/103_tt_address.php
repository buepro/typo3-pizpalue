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
        'Extensions/tt_address/GoogleMap/Configuration/TsConfig/Page.tsconfig',
        'Pizpalue - Extension tt_address Google map'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'pizpalue',
        'Extensions/tt_address/Teaser/Configuration/TsConfig/Page.tsconfig',
        'Pizpalue - Extension tt_address Teaser'
    );
})();
