<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/**
 * Extend and configure fields (mainly non pizpalue fields).
 */
(static function (): void {
    /**
     * Adds complementary class to frame background (after secondary, in case it still exists)
     */
    $tcaItems = $GLOBALS['TCA']['tt_content']['columns']['background_color_class']['config']['items'];
    $items = [];
    $complementaryAdded = false;
    foreach ($tcaItems as [$value, $text]) {
        $items[] = [$value, $text];
        if ($value === 'secondary') {
            $items[] = ['complementary', 'complementary'];
            $complementaryAdded = true;
        }
    }
    if (!$complementaryAdded) {
        $items[] = ['complementary', 'complementary'];
    }
    $GLOBALS['TCA']['tt_content']['columns']['background_color_class']['config']['items'] = $items;

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
})();
