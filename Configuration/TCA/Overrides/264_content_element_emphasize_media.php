<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function ($extensionKey): void {
    /**
     * Add content element to selector list
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_emphasize_media.title',
            'pp_emphasize_media',
            'content-pizpalue-emphasize-media',
            'pizpalue'
        ]
    );

    /**
     * Assigns Icon to page view
     */
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pp_emphasize_media'] = 'content-pizpalue-emphasize-media';

    /**
     * Configure element type
     */
    $GLOBALS['TCA']['tt_content']['types']['pp_emphasize_media'] = array_replace_recursive(
        $GLOBALS['TCA']['tt_content']['types']['textmedia'],
        ['columnsOverrides' => [
            'teaser' => [
                'config' => [
                    'enableRichtext' => true,
                ],
            ],
        ]]
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        'teaser, readmore_label',
        'pp_emphasize_media',
        'before:bodytext'
    );
})('pizpalue');
