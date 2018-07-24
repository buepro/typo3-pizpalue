<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

$GLOBALS['TCA']['tt_content']['palettes']['pizpalue_attributes'] = [
    'showitem' => '
        tx_pizpalue_classes,
        tx_pizpalue_style,
        tx_pizpalue_attributes
    ',
];

$tmp_pizpalue_columns = [

    'tx_pizpalue_classes' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes',
        'config' => [
            'type' => 'input',
            'size' => 50,
            'max' => 255,
            'eval' => 'trim'
        ],
    ],
    'tx_pizpalue_style' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.style',
        'config' => [
            'type' => 'input',
            'size' => 50,
            'max' => 255,
            'eval' => 'trim'
        ],
    ],
    'tx_pizpalue_attributes' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.additionalAttributes',
        'config' => [
            'type' => 'input',
            'size' => 50,
            'max' => 255,
            'eval' => 'trim'
        ],
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $tmp_pizpalue_columns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--palette--;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.attributes;pizpalue_attributes,',
    '',
    'after: linkToTop'
);
