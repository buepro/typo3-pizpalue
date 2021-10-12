<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * Add page TSconfig objects in case they aren't automatically loaded
 */
(static function () {
    $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    );
    $pizpalueConfiguration = $extensionConfiguration->get('pizpalue');

    if (!(bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
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
            'Configuration/TsConfig/Page/ContentElement/All.tsconfig',
            'Pizpalue - Content elements'
        );
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
                'pizpalue',
                'Extensions/news/Configuration/TsConfig/Page.tsconfig',
                'Pizpalue - Extension news'
            );
        }
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('eventnews')) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
                'pizpalue',
                'Extensions/eventnews/Configuration/TsConfig/Page.tsconfig',
                'Pizpalue - Extension eventnews'
            );
        }
    }
})();

/**
 * Add page TSconfig objects that need to be loaded manually.
 * (e.g. because they are used just on a specific page or might conflict when used together)
 */
(static function () {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_address')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
            'pizpalue',
            'Extensions/tt_address/DisplayMode/GoogleMap/Configuration/TsConfig/Page.tsconfig',
            'Pizpalue - Extension tt_address Google map'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
            'pizpalue',
            'Extensions/tt_address/DisplayMode/Teaser/Configuration/TsConfig/Page.tsconfig',
            'Pizpalue - Extension tt_address Teaser'
        );
    }
})();
