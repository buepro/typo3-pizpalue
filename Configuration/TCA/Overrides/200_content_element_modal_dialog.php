<?php

defined('TYPO3_MODE') || die();

call_user_func(function ($extensionKey) {

    /***************
     * Enable Content Element
     */
    if (!is_array($GLOBALS['TCA']['tt_content']['types']['modal_dialog'])) {
        $GLOBALS['TCA']['tt_content']['types']['modal_dialog'] = [];
    }

    /***************
     * Adds content element to new content element wizard
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TsConfig/Page/ContentElement/Element/ModalDialog.tsconfig',
        'Pizpalue Content Element: Modal Dialog'
    );

    /***************
     * Adds content element to selector list
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:modal_dialog.title',
            'modal_dialog',
            'content-pizpalue-modal-dialog'
        ]
    );

},'pizpalue');

