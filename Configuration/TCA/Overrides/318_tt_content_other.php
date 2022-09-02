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
    // Column definition
    $pizpalueColumns = [
        'tx_pizpalue_layout_breakpoint' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'items' => [
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.all', ''],
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.small', 'sm'],
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.medium', 'md'],
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.large', 'lg'],
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.extralarge', 'xl'],
                ],
            ],
        ],
        'tx_pizpalue_animation' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animation',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animationNone', 0],
                ],
            ],
        ],
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $pizpalueColumns
    );

    /**
     * Define palettes
     */
    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_behaviour'] = [
        'showitem' => 'tx_pizpalue_animation',
    ];

    /**
     * Add tx_pizpalue_layout_breakpoint after layout field
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        'tx_pizpalue_layout_breakpoint',
        'replace:frame_class'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        '--linebreak--,frame_class',
        'after:space_after_class'
    );

    /**
     * Add palettes content types
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.behaviour;pizpalue_behaviour',
        '',
        'after: tx_pizpalue_background_image_variants'
    );
})();
