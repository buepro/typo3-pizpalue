<?php

defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'teaser, readmore_label',
    'textmedia',
    'before:bodytext'
);

$GLOBALS['TCA']['tt_content']['types']['textmedia'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types']['textmedia'],
    [
        'columnsOverrides' => [
            'teaser' => [
                'config' => [
                    'enableRichtext' => true
                ]
            ]
        ]
    ]
);
