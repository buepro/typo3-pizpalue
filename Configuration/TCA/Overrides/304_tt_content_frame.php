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
     * Change labels
     */
    $GLOBALS['TCA']['tt_content']['palettes']['frames']['label'] = $llFile . ':tx_pizpalue_ttc.palette.frames';
    foreach ($GLOBALS['TCA']['tt_content']['types'] as &$type) {
        $type['showitem'] = str_replace(
            '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames',
            '--palette--;;frames',
            $type['showitem']
        );
    }
    unset($type);

    /**
     * Group frame fields
     */
    \Buepro\Pizpalue\Utility\TcaUtility::removeFieldsFromPalette(
        'tt_content',
        'frames',
        'frame_class, frame_layout, frame_options'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        'frame_class;' . $llFile . ':tx_pizpalue_ttc.frame_class, frame_layout, --linebreak--, frame_options'
    );
})();
