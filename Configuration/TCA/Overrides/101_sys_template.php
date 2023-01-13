<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    $pizpalueConfiguration = (array)(\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    ))->get('pizpalue');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'pizpalue',
        'Configuration/TypoScript/Main',
        'Pizpalue - Main'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'pizpalue',
        'Configuration/TypoScript/Bootstrap4',
        'Pizpalue - Bootstrap 4.x'
    );

    if (
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container_elements') &&
        !(bool)($pizpalueConfiguration['autoLoadStaticTSForExtensions'] ?? true) &&
        (($containerElementsVersion = \Buepro\Pizpalue\Utility\VersionUtility::getExtensionVersion('container_elements'))
            === 0 || $containerElementsVersion > 3001001)
    ) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/container_elements/Configuration/TypoScript',
            'Pizpalue - container_elements'
        );
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/news/Configuration/TypoScript',
            'Pizpalue - news'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/news/Configuration/TypoScript/Twb5',
            'Pizpalue - news twb5'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/news/Configuration/TypoScript/RSS',
            'Pizpalue - news RSS feed'
        );
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('eventnews')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/eventnews/Configuration/TypoScript',
            'Pizpalue - eventnews'
        );
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('newsslider')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/newsslider/Configuration/TypoScript',
            'Pizpalue - newsslider 2.0.1'
        );
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('femanager')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/femanager/Configuration/TypoScript',
            'Pizpalue - femanager 2.2.0'
        );
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_address')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/tt_address/Configuration/TypoScript',
            'Pizpalue - tt_address'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/tt_address/DisplayMode/GoogleMap/Configuration/TypoScript',
            'Pizpalue - tt_address Google map'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'pizpalue',
            'Extensions/tt_address/DisplayMode/Teaser/Configuration/TypoScript',
            'Pizpalue - tt_address Teaser'
        );
    }

    $extensionKeys = ['felogin', 'bookmark_pages', 'indexed_search', 'sr_language_menu', 'slickcarousel'];
    foreach ($extensionKeys as $extensionKey) {
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extensionKey)) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
                'pizpalue',
                sprintf('Extensions/%s/Configuration/TypoScript', $extensionKey),
                sprintf('Pizpalue - %s', $extensionKey)
            );
        }
    }
})();
