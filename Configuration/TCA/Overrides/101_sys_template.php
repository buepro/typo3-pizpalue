<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function ($_EXTKEY) {
    /***************
     * Static templates
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript/Main',
        'Pizpalue - Main'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript/Bootstrap3/Rendering',
        'Pizpalue - Bootstrap 3.x (LESS)'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript/Upgrade9',
        'Pizpalue - Upgrade 9'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript/ContentElement',
        'Pizpalue - Content elements'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/news/Configuration/TypoScript',
        'Pizpalue - news'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/slickcarousel/Configuration/TypoScript',
        'Pizpalue - slickcarousel'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/news/Configuration/TypoScript/RSS',
        'Pizpalue - news RSS feed'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/newsslider/Configuration/TypoScript',
        'Pizpalue - newsslider 2.0.1'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/femanager/Configuration/TypoScript',
        'Pizpalue - femanager 2.2.0'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/tt_address/4.3.0/Configuration/TypoScript',
        'Pizpalue - tt_address 4.3.0'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/tt_address/GoogleMap/Configuration/TypoScript',
        'Pizpalue - tt_address Google map'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/tt_address/Teaser/Configuration/TypoScript',
        'Pizpalue - tt_address Teaser'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/indexed_search/Configuration/TypoScript',
        'Pizpalue - indexed_search'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/felogin/Configuration/TypoScript',
        'Pizpalue - felogin'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Extensions/gridelements/Configuration/TypoScript/DataProcessingLibContentElement',
        'Pizpalue - gridelements w/DataProcessing'
    );
    /**
     * @deprecated since version 11.1.2, will be removed in version 12.0.0
     */
    (function ($_EXTKEY) {
        $context = \TYPO3\CMS\Core\Core\Environment::getContext();
        trigger_error(
            'Static extension templates are deprecated since version 11.1.2, will be removed in \
                    version 12.0.0',
            E_USER_DEPRECATED
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/gridelements/Configuration/TypoScript',
            'Pizpalue DEPRECIATED - Gridelements CEs'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/gridelements/Configuration/TypoScript/Rendering',
            'Pizpalue DEPRECIATED - Gridelements rendering (include as last)'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/tt_address/3.0.3/Configuration/TypoScript',
            'Pizpalue DEPRECIATED - tt_address 3.0.3'
        );
    })($_EXTKEY);
})('pizpalue');
