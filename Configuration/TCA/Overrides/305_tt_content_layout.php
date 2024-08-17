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
     * Add columns
     */
    $pizpalueColumns = [
        'tx_pizpalue_layout_breakpoint' => [
            'exclude' => true,
            'label' => $llFile . ':tx_pizpalue_ttc.layoutBreakpoint',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'items' => [
                    [
                        'label' => $llFile . ':tx_pizpalue_ttc.layoutBreakpoint.all',
                        'value' => '',
                    ],
                    [
                        'label' => $llFile . ':tx_pizpalue_ttc.layoutBreakpoint.small',
                        'value' => 'sm',
                    ],
                    [
                        'label' => $llFile . ':tx_pizpalue_ttc.layoutBreakpoint.medium',
                        'value' => 'md',
                    ],
                    [
                        'label' => $llFile . ':tx_pizpalue_ttc.layoutBreakpoint.large',
                        'value' => 'lg',
                    ],
                    [
                        'label' => $llFile . ':tx_pizpalue_ttc.layoutBreakpoint.extralarge',
                        'value' => 'xl',
                    ],
                ],
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
    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_layout'] = [
        'label' => $llFile . ':tx_pizpalue_ttc.palette.layout',
        'showitem' => 'layout;' . $llFile . ':tx_pizpalue_ttc.layout, tx_pizpalue_layout_breakpoint',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pizpalue_layout',
        '',
        'before:frame_class'
    );
    \Buepro\Pizpalue\Utility\TcaUtility::removeFieldsFromPalette('tt_content', 'frames', 'layout');
})();
