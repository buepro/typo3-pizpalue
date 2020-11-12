<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function () {
    /**
     * Add content element group to content element selector
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItemGroup(
        'tt_content',
        'CType',
        'pizpalue',
        'Pizpalue'
    );

    /**
     * Add 'tile` layout items
     */
    $items = $GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'];
    $items[] = [
        'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:tt_content.layout.tile21',
        'pp-tile-21'
    ];
    $items[] = [
        'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:tt_content.layout.tile11',
        'pp-tile-11'
    ];
    $items[] = [
        'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:tt_content.layout.tile12',
        'pp-tile-12'
    ];
    $GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'] = $items;

    /**
     * Add `emphasize media` layout for textmedia contentelement
     */
    $items = $GLOBALS['TCA']['tt_content']['types']['textmedia']['columnsOverrides']['layout']['config']['items'] ?? $GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'];
    $items[] = [
        'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:tt_content.layout.emphasize_media',
        'pp-emphasize-media'
    ];
    $GLOBALS['TCA']['tt_content']['types']['textmedia']['columnsOverrides']['layout']['config']['items'] = $items;
})();
