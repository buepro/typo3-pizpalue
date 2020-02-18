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
    if (!is_array($GLOBALS['TCA']['tt_content']['types']['pp_modal_dialog'])) {
        $GLOBALS['TCA']['tt_content']['types']['pp_modal_dialog'] = [];
    }

    /**
     * Adds content element to new content element wizard
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/ContentElement/Element/ModalDialog.tsconfig',
        'Pizpalue Content Element: Modal Dialog'
    );

    /**
     * Adds content element to selector list
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_modal_dialog.title',
            'pp_modal_dialog',
            'content-pizpalue-modal-dialog'
        ]
    );

    /**
     * Assigns Icon to page view
     */
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pp_modal_dialog'] = 'content-pizpalue-modal-dialog';

    /**
     * Configures element type
     */
    $GLOBALS['TCA']['tt_content']['types']['pp_modal_dialog'] = array_replace_recursive(
        $GLOBALS['TCA']['tt_content']['types']['pp_modal_dialog'],
        [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    header;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tca.modal_dialog.header,
                    records;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:records_formlabel,
                --div--;LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:menu.card.options,
                    pi_flexform;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tca.modal_dialog.options,
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
            '
        ]
    );

    /**
     * Adds flexForm for content element configuration
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:pizpalue/Configuration/FlexForms/ModalDialog.xml',
        'pp_modal_dialog'
    );
})('pizpalue');
