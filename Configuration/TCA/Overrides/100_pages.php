<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

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
        'config' => [
            'type' => 'file',
            'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            'disallowed' => '',
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
            ],
            'overrideChildTca' => [
                'types' => [
                    \TYPO3\CMS\Core\Resource\FileType::UNKNOWN->value => [
                        'showitem' => '
                            --palette--;;filePalette
                        ',
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::TEXT->value => [
                        'showitem' => '
                            --palette--;;filePalette
                        ',
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::IMAGE->value => [
                        'showitem' => '
                            crop,
                            --palette--;;filePalette
                        ',
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::AUDIO->value => [
                        'showitem' => '
                            --palette--;;filePalette
                        ',
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::VIDEO->value => [
                        'showitem' => '
                            --palette--;;filePalette
                        ',
                    ],
                    \TYPO3\CMS\Core\Resource\FileType::APPLICATION->value => [
                        'showitem' => '
                            --palette--;;filePalette
                        ',
                    ],
                ],
            ],
            'minitems' => 0,
            'maxitems' => 1,
        ],
        'l10n_mode' => 'exclude',
    ];
    $GLOBALS['TCA']['pages']['columns']['tx_pizpalue_css'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_pages.css',
        'config' => [
            'type' => 'text',
            'renderType' => 'codeEditor',
            'rows' => 8,
            'cols' => 50,
            'eval' => 'Buepro\\Pizpalue\\UserFunction\\FormEngine\\CssEval',
        ],
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_pizpalue_background_image',
        '',
        'before:thumbnail'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_pizpalue_css',
        '',
        'after:content_from_pid'
    );
})();
