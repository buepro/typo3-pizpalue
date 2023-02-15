<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/**
 * Add and configure image related fields to content elements
 */
(static function (): void {
    $imageColumns = [
        'tx_pizpalue_image_scaling' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_scaling',
            'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_scaling.description',
            'config' => [
                'type' => 'text',
                'default' => implode(',' . chr(10), ['xxl: 1.0', 'xl: 1.0', 'lg: 1.0', 'md: 1.0', 'sm: 1.0', 'xs: 1.0']),
                'rows' => 6,
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
            'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_scaling.description',
            'config' => [
                'type' => 'text',
                'default' => implode(',' . chr(10), ['xxl: 0', 'xl: 0', 'lg: 0', 'md: 0', 'sm: 0', 'xs: 0']),
                'valuePicker' => [
                    'items' => [
                        [
                            'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.undefined',
                            implode(',' . chr(10), ['xxl: 0', 'xl: 0', 'lg: 0', 'md: 0', 'sm: 0', 'xs: 0'])
                        ],
                        ['2:1', implode(',' . chr(10), ['xxl: 2.0', 'xl: 2.0', 'lg: 2.0', 'md: 2.0', 'sm: 2.0', 'xs: 2.0'])],
                        ['16:9', implode(',' . chr(10), ['xxl: 1.7778', 'xl: 1.7778', 'lg: 1.7778', 'md: 1.7778', 'sm: 1.7778', 'xs: 1.7778'])],
                        ['4:3', implode(',' . chr(10), ['xxl: 1.3333', 'xl: 1.3333', 'lg: 1.3333', 'md: 1.3333', 'sm: 1.3333', 'xs: 1.3333'])],
                        ['1:1', implode(',' . chr(10), ['xxl: 1.0', 'xl: 1.0', 'lg: 1.0', 'md: 1.0', 'sm: 1.0', 'xs: 1.0'])],
                        ['3:4', implode(',' . chr(10), ['xxl: 0.75', 'xl: 0.75', 'lg: 0.75', 'md: 0.75', 'sm: 0.75', 'xs: 0.75'])],
                        ['9:16', implode(',' . chr(10), ['xxl: 0.5625', 'xl: 0.5625', 'lg: 0.5625', 'md: 0.5625', 'sm: 0.5625', 'xs: 0.5625'])],
                        ['1:2', implode(',' . chr(10), ['xxl: 0.5', 'xl: 0.5', 'lg: 0.5', 'md: 0.5', 'sm: 0.5', 'xs: 0.5'])]
                    ],
                ],
                'rows' => 6,
            ],
        ],
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $imageColumns
    );

    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_imagesize'] = [
        'showitem' => 'tx_pizpalue_image_variants, tx_pizpalue_image_scaling, tx_pizpalue_image_aspect_ratio',
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pizpalue_imagesize',
        'image,textmedia,textpic,pp_card',
        'before: imageorient'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'tx_pizpalue_image_variants;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.carousel.image_variants',
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
