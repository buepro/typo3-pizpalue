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
    $tcaColumns = &$GLOBALS['TCA']['tt_content']['columns'];

    /**
     * Add columns
     */
    $pizpalueInnerSpaceItems = [
        [
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpace.none',
            'value' => 'none',
        ],
        [
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpace.small',
            'value' => 'small',
        ],
        [
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpace.default',
            'value' => '',
        ],
        [
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpace.large',
            'value' => 'large',
        ],
        [
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpace.extraLarge',
            'value' => 'extra-large',
        ],
    ];

    /**
     * Add columns
     */
    $pizpalueColumns = [
        'tx_pizpalue_inner_space_before_class' => [
            'exclude' => true,
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpaceBeforeClass',
            'displayCond' => 'FIELD:frame_class:!=:none',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'eval' => 'trim',
                'items' => $pizpalueInnerSpaceItems,
            ],
        ],
        'tx_pizpalue_inner_space_after_class' => [
            'exclude' => true,
            'label' => $llFile . ':tx_pizpalue_ttc.innerSpaceAfterClass',
            'displayCond' => 'FIELD:frame_class:!=:none',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'eval' => 'trim',
                'items' => $pizpalueInnerSpaceItems,
            ],
        ],
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $pizpalueColumns
    );

    /**
     * Palette
     */
    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_space'] = [
        'label' => $llFile . ':tx_pizpalue_ttc.palette.space',
        'showitem' => 'space_before_class, space_after_class, --linebreak--, tx_pizpalue_inner_space_before_class, tx_pizpalue_inner_space_after_class',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pizpalue_space',
        '',
        'after:frame_options'
    );
    \Buepro\Pizpalue\Utility\TcaUtility::removeFieldsFromPalette(
        'tt_content',
        'frames',
        'space_before_class, space_after_class, tx_pizpalue_inner_space_before_class, tx_pizpalue_inner_space_after_class'
    );

    unset($tcaColumns);
})();
