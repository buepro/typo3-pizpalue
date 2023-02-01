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
     * Palette
     */
    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_background'] = [
        'label' => $llFile . ':tx_pizpalue_ttc.palette.background',
        'showitem' => 'background_color_class, --linebreak--, background_image, --linebreak--, background_image_options, --linebreak--, tx_pizpalue_background_image_variants',
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;;pizpalue_background',
        '',
        'after:frame_options'
    );
    \Buepro\Pizpalue\Utility\TcaUtility::removeFieldsFromPalette(
        'tt_content',
        'frames',
        'background_color_class, background_image, tx_pizpalue_background_image_variants, background_image_options'
    );
})();
