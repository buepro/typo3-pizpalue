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
        [$llFile . ':tx_pizpalue_ttc.innerSpace.none', 'none'],
        [$llFile . ':tx_pizpalue_ttc.innerSpace.small', 'small'],
        [$llFile . ':tx_pizpalue_ttc.innerSpace.default', ''],
        [$llFile . ':tx_pizpalue_ttc.innerSpace.large', 'large'],
        [$llFile . ':tx_pizpalue_ttc.innerSpace.extraLarge', 'extra-large'],
    ];
    $pizpalueColumns = [
        'tx_pizpalue_layout_breakpoint' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'items' => [
                    [$llFile . ':tx_pizpalue_ttc.layoutBreakpoint.all', ''],
                    [$llFile . ':tx_pizpalue_ttc.layoutBreakpoint.small', 'sm'],
                    [$llFile . ':tx_pizpalue_ttc.layoutBreakpoint.medium', 'md'],
                    [$llFile . ':tx_pizpalue_ttc.layoutBreakpoint.large', 'lg'],
                    [$llFile . ':tx_pizpalue_ttc.layoutBreakpoint.extralarge', 'xl'],
                ],
            ],
        ],
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
     * Add fields
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        'tx_pizpalue_layout_breakpoint',
        'after:layout'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        '--linebreak--, tx_pizpalue_inner_space_before_class, tx_pizpalue_inner_space_after_class',
        'after:space_after_class'
    );

    /**
     * Group frame fields
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        '--linebreak--',
        'replace:frame_class'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        'frame_class',
        'before:frame_layout'
    );

    unset($tcaColumns);
})();
