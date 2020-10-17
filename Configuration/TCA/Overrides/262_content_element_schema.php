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
    if (!is_array($GLOBALS['TCA']['tt_content']['types']['pp_schema'])) {
        $GLOBALS['TCA']['tt_content']['types']['pp_schema'] = [];
    }

    /**
     * Adds content element to available objects for `Page TSconfig` in page properties
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/ContentElement/Element/Schema.tsconfig',
        'Pizpalue - Content Element: Schema'
    );

    /**
     * Adds content element to selector list
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'Schema',
            'pp_schema',
            'content-pizpalue-schema',
            'pizpalue'
        ]
    );

    /**
     * Assigns Icon to page view
     */
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pp_schema'] = 'content-pizpalue-schema';

    /**
     * Configures element type
     */
    $GLOBALS['TCA']['tt_content']['types']['pp_schema'] = array_replace_recursive(
        $GLOBALS['TCA']['tt_content']['types']['pp_schema'],
        [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.html_formlabel,
                    bodytext;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tca.schema.jsonLdCode,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                    image, teaser;LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.text,
                --div--;LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:menu.card.options,
                    pi_flexform;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tca.schema.options,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
            ',
            'columnsOverrides' => [
                'bodytext' => [
                    'config' => [
                        'format' => 'javascript',
                        'renderType' => 't3editor',
                        'rows' => 20,
                    ]
                ],
                'teaser' => [
                    'config' => [
                        'rows' => 20
                    ]
                ]
            ]
        ]
    );

    /**
     * Adds flexForm for content element configuration
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:pizpalue/Configuration/FlexForms/Schema.xml',
        'pp_schema'
    );
})('pizpalue');
