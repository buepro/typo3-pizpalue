<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/**
 * Define aspect ratios
 */
(static function (): void {
    $defaultAspectRatios = $GLOBALS['TCA']['tt_content']['columns']['background_image']['config']['overrideChildTca']
        ['columns']['crop']['config']['cropVariants']['default']['allowedAspectRatios'];
    $aspectRatios = [
        '2:1' => [
            'title' => '2:1',
            'value' => 2
        ],
        '16:9' => $defaultAspectRatios['16:9'],
        '4:3' => $defaultAspectRatios['4:3'],
        '1:1' => $defaultAspectRatios['1:1'],
        '3:4' => [
            'title' => '3:4',
            'value' => 0.75
        ],
        '9:16' => [
            'title' => '9:16',
            'value' => 0.5625
        ],
        '1:2' => [
            'title' => '1:2',
            'value' => 0.5
        ],
        'NaN' => $defaultAspectRatios['NaN'],
    ];

    // Background image
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForField($aspectRatios, 'tt_content', 'background_image');
    // Content elements with images
    foreach (['image', 'textpic', 'pp_picoverlay'] as $cType) {
        \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tt_content', $cType, 'image');
    }
    // Content elements with assets
    foreach (['media', 'textmedia', 'pp_emphasize_media'] as $cType) {
        \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tt_content', $cType, 'assets');
    }
    // Card Group
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tx_bootstrappackage_card_group_item', '1', 'image');
    // Accordion
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tx_bootstrappackage_accordion_item', '1', 'media');
    // Carousel Background Image
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForField($aspectRatios, 'tx_bootstrappackage_carousel_item', 'background_image');
    // Carousel Image
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForField($aspectRatios, 'tx_bootstrappackage_carousel_item', 'image');
    // Tab
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tx_bootstrappackage_tab_item', '1', 'media');
    // Timeline
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tx_bootstrappackage_timeline_item', '1', 'image');
    // Pages
    foreach ([1, 3, 4] as $cType) {
        \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'pages', $cType, 'thumbnail');
    }
})();
