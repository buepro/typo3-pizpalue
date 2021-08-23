<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(function ($_EXTKEY) {
    /***************
     * Static templates
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript/Main',
        'Pizpalue - Main'
    );
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/news/Configuration/TypoScript',
            'Pizpalue - news'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/news/Configuration/TypoScript/RSS',
            'Pizpalue - news RSS feed'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('eventnews')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/eventnews/Configuration/TypoScript',
            'Pizpalue - eventnews'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('slickcarousel')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/slickcarousel/Configuration/TypoScript',
            'Pizpalue - slickcarousel'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('newsslider')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/newsslider/Configuration/TypoScript',
            'Pizpalue - newsslider 2.0.1'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('femanager')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/femanager/Configuration/TypoScript',
            'Pizpalue - femanager 2.2.0'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_address')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/tt_address/Configuration/TypoScript',
            'Pizpalue - tt_address'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/tt_address/DisplayMode/GoogleMap/Configuration/TypoScript',
            'Pizpalue - tt_address Google map'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/tt_address/DisplayMode/Teaser/Configuration/TypoScript',
            'Pizpalue - tt_address Teaser'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('felogin')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/felogin/Configuration/TypoScript',
            'Pizpalue - felogin'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('bookmark_pages')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/bookmark_pages/Configuration/TypoScript',
            'Pizpalue - bookmark_pages'
        );
    }
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('indexed_search')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $_EXTKEY,
            'Extensions/indexed_search/Configuration/TypoScript',
            'Pizpalue - indexed_search'
        );
    }
})('pizpalue');
