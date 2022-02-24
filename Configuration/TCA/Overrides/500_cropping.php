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

    // Background image
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForField('tt_content', 'background_image');
    // Content elements with images
    foreach (['image', 'textpic', 'pp_picoverlay'] as $cType) {
        \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('tt_content', $cType, 'image');
    }
    // Content elements with assets
    foreach (['media', 'textmedia', 'pp_emphasize_media'] as $cType) {
        \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('tt_content', $cType, 'assets');
    }
    // Card Group
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('tx_bootstrappackage_card_group_item', '1', 'image');
    // Accordion
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('tx_bootstrappackage_accordion_item', '1', 'media');
    // Carousel Background Image
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForField('tx_bootstrappackage_carousel_item', 'background_image');
    // Carousel Image
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForField('tx_bootstrappackage_carousel_item', 'image');
    // Tab
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('tx_bootstrappackage_tab_item', '1', 'media');
    // Timeline
    \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('tx_bootstrappackage_timeline_item', '1', 'image');
    // Pages
    foreach ([1, 3, 4] as $cType) {
        \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType('pages', $cType, 'thumbnail');
    }
})();
