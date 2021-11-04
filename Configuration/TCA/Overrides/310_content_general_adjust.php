<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * Extend and configure fields (mainly non pizpalue fields).
 */
(static function () {
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
     * Enable background image for all frame classes
     */
    unset($GLOBALS['TCA']['tt_content']['columns']['background_image']['displayCond'],
        $GLOBALS['TCA']['tt_content']['columns']['background_image_options']['displayCond']);
})();
