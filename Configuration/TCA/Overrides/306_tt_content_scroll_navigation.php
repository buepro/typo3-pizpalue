<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    $GLOBALS['TCA']['tt_content']['columns']['tx_pizpalue_scroll_navigation_enable'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.scroll_navigation_enable',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'default' => 0,
        ],
    ];
    $GLOBALS['TCA']['tt_content']['columns']['tx_pizpalue_scroll_navigation_title'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.scroll_navigation_title',
        'config' => [
            'type' => 'input',
            'eval' => 'trim',
        ],
    ];
    $GLOBALS['TCA']['tt_content']['columns']['tx_pizpalue_scroll_navigation_position'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.scroll_navigation_position',
        'config' => [
            'type' => 'input',
            'default' => 0,
            'eval' => 'num',
            'size' => 6,
        ],
    ];
    $GLOBALS['TCA']['tt_content']['palettes']['pp-scroll-navigation'] = [
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.palette.pp-scroll-navigation',
        'showitem' => 'tx_pizpalue_scroll_navigation_enable, tx_pizpalue_scroll_navigation_title, tx_pizpalue_scroll_navigation_position',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pp-scroll-navigation',
        '',
        'after:linkToTop'
    );
})();
