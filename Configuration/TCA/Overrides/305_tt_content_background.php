<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/**
 * Add fields to content elements
 */
(static function (): void {
    $llFile = 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf';

    /**
     * Add background image variants field
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        [
            'tx_pizpalue_background_image_variants' => [
                'exclude' => true,
                'label' => $llFile . ':tx_pizpalue_ttc.background_image_variants',
                'description' => $llFile . ':tx_pizpalue_ttc.background_image_variants.description',
                'default' => 'pageVariants',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'default' => 'pageVariants',
                    'items' => [
                        [
                            'label' => $llFile . ':tx_pizpalue_ttc.image_variants.content',
                            'value' => 'variants',
                        ],
                        [
                            'label' => $llFile . ':tx_pizpalue_ttc.image_variants.page',
                            'value' => 'pageVariants',
                        ],
                    ],
                ],
            ],
        ]
    );

    /**
     * Enable background image and background color for all frame classes
     */
    unset($GLOBALS['TCA']['tt_content']['columns']['background_image']['displayCond'],
        $GLOBALS['TCA']['tt_content']['columns']['background_color_class']['displayCond']);

    /**
     * Conditionally show background image related fields
     */
    $GLOBALS['TCA']['tt_content']['columns']['background_image']['onChange'] = 'reload';
    $GLOBALS['TCA']['tt_content']['columns']['background_image_options']['displayCond'] = 'FIELD:background_image:REQ:true';
    $GLOBALS['TCA']['tt_content']['columns']['tx_pizpalue_background_image_variants']['displayCond'] = 'FIELD:background_image:REQ:true';

    /**
     * Palette
     */
    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_background'] = [
        'label' => $llFile . ':tx_pizpalue_ttc.palette.background',
        'showitem' => 'background_color_class, --linebreak--, background_image, --linebreak--, tx_pizpalue_background_image_variants, background_image_options',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pizpalue_background',
        '',
        'after:frame_options'
    );
    \Buepro\Pizpalue\Utility\TcaUtility::removeFieldsFromPalette(
        'tt_content',
        'frames',
        'background_color_class, background_image, background_image_options'
    );
})();
