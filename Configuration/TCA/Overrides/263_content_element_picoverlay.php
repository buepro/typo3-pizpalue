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
     * Adds content element to selector list
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_picoverlay.title',
            'pp_picoverlay',
            'content-pizpalue-picoverlay',
            'pizpalue'
        ]
    );

    /**
     * Assigns Icon to page view
     */
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pp_picoverlay'] = 'content-pizpalue-picoverlay';

    /**
     * Configures element type
     */
    $GLOBALS['TCA']['tt_content']['types']['pp_picoverlay'] = array_replace_recursive(
        $GLOBALS['TCA']['tt_content']['types']['textpic'],
        [
            'showitem' => '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        --palette--;;general,
                        --palette--;;headers,
                        readmore_label,
                        bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images,
                        image,
                    --div--;LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:menu.card.options,
                        pi_flexform;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tca.content_element.options,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                        --palette--;;frames,
                        --palette--;;appearanceLinks,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                        --palette--;;language,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        --palette--;;hidden,
                        --palette--;;access,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                        categories,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                        rowDescription,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                ',
            'columnsOverrides' => [
                'bodytext' => [
                    'config' => [
                        'enableRichtext' => true,
                    ],
                ],
                'image' => [
                    'config' => [
                        'maxitems' => 1,
                    ],
                ],
            ]
        ]
    );

    /**
     * Adds flexForm for content element configuration
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:pizpalue/Configuration/FlexForms/Picoverlay.xml',
        'pp_picoverlay'
    );
})('pizpalue');
