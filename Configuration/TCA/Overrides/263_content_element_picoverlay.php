<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function ($extensionKey) {
    /**
     * Enables Content Element
     */
    if (!is_array($GLOBALS['TCA']['tt_content']['types']['pp_picoverlay'])) {
        $GLOBALS['TCA']['tt_content']['types']['pp_picoverlay'] = [];
    }

    /**
     * Adds content element to available objects for `Page TSconfig` in page properties
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/ContentElement/Element/Picoverlay.tsconfig',
        'Pizpalue - Content Element: Picture showing overlay'
    );

    /**
     * Adds content element to selector list
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'Picture overlay',
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
    $GLOBALS['TCA']['tt_content']['types']['pp_picoverlay'] = [
        'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;headers,
                    readmore_label,
                    bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images,
                    image,
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
    ];
})('pizpalue');
