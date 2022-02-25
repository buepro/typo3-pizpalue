<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/**
 * Add page TSconfig objects in case they aren't automatically loaded
 */
(static function (): void {
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
(static function (): void {
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

/**
 * Additional fields
 */
(static function (): void {
    $GLOBALS['TCA']['pages']['columns']['tx_pizpalue_background_image'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:field.background_image',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'tx_pizpalue_background_image',
            [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                'overrideChildTca' => [
                    'types' => [
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_UNKNOWN => [
                            'showitem' => '
                            --palette--;;filePalette
                        '
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;;filePalette
                        '
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            crop,
                            --palette--;;filePalette
                        '
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;;filePalette
                        '
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;;filePalette
                        '
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;;filePalette
                        '
                        ],
                    ],
                ],
                'minitems' => 0,
                'maxitems' => 1,
            ],
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
        ),
        'l10n_mode' => 'exclude',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_pizpalue_background_image',
        '',
        'before:thumbnail'
    );
})();
