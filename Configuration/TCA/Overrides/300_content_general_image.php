<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * Add and configure image related fields to content elements
 */
(static function () {
    $imageColumns = [
        'tx_pizpalue_image_scaling' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_scaling',
            'config' => [
                'type' => 'text',
                'default' => implode(',' . chr(10), ['xl: 1.0', 'lg: 1.0', 'md: 1.0', 'sm: 1.0', 'xs: 1.0']),
            ]
        ],
        'tx_pizpalue_image_variants' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'variants',
                'items' => [
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.content', 'variants'],
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.page', 'pageVariants'],
                ],
            ],
        ],
        'tx_pizpalue_image_aspect_ratio' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_aspect_ratio',
            'config' => [
                'type' => 'text',
                'default' => implode(',' . chr(10), ['xl: 0', 'lg: 0', 'md: 0', 'sm: 0', 'xs: 0']),
                'valuePicker' => [
                    'items' => [
                        [
                            'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.undefined',
                            implode(',' . chr(10), ['xl: 0', 'lg: 0', 'md: 0', 'sm: 0', 'xs: 0'])
                        ],
                        ['2:1', implode(',' . chr(10), ['xl: 2.0', 'lg: 2.0', 'md: 2.0', 'sm: 2.0', 'xs: 2.0'])],
                        ['16:9', implode(',' . chr(10), ['xl: 1.7778', 'lg: 1.7778', 'md: 1.7778', 'sm: 1.7778', 'xs: 1.7778'])],
                        ['4:3', implode(',' . chr(10), ['xl: 1.3333', 'lg: 1.3333', 'md: 1.3333', 'sm: 1.3333', 'xs: 1.3333'])],
                        ['1:1', implode(',' . chr(10), ['xl: 1.0', 'lg: 1.0', 'md: 1.0', 'sm: 1.0', 'xs: 1.0'])],
                        ['3:4', implode(',' . chr(10), ['xl: 0.75', 'lg: 0.75', 'md: 0.75', 'sm: 0.75', 'xs: 0.75'])],
                        ['9:16', implode(',' . chr(10), ['xl: 0.5625', 'lg: 0.5625', 'md: 0.5625', 'sm: 0.5625', 'xs: 0.5625'])],
                        ['1:2', implode(',' . chr(10), ['xl: 0.5', 'lg: 0.5', 'md: 0.5', 'sm: 0.5', 'xs: 0.5'])]
                    ],
                ],
            ],
        ],
        'tx_pizpalue_background_image_variants' => [
            'exclude' => true,
            'displayCond' => 'FIELD:frame_class:!=:none',
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.background_image_variants',
            'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.background_image_variants.description',
            'default' => 'pageVariants',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'pageVariants',
                'items' => [
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.content', 'variants'],
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.page', 'pageVariants'],
                ],
            ],
        ],
        // @deprecated since 11.4.0
        // @todo remove support for inline background image
        'tx_pizpalue_bgmedia' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.bgmedia',
            'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:deprecatedItemNote',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'tx_pizpalue_bgmedia',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => 'crop,--palette--;;filePalette',
                            ]
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $imageColumns
    );

    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_imagesize'] = [
        'showitem' => 'tx_pizpalue_image_variants, tx_pizpalue_image_scaling, tx_pizpalue_image_aspect_ratio',
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        '--linebreak--, tx_pizpalue_background_image_variants, --linebreak--, tx_pizpalue_bgmedia',
        'after: background_image_options'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pizpalue_imagesize',
        'image,textmedia,textpic',
        'before: imageorient'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'tx_pizpalue_image_variants',
        'carousel,carousel_fullscreen,carousel_small',
        'after: pi_flexform'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'tx_pizpalue_image_variants',
        'list',
        'after: list_type'
    );
})();
