<?php declare(strict_types=1);

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
            'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_card.title',
            'pp_card',
            'content-pizpalue-card',
            'pizpalue',
        ]
    );

    /**
     * Assigns Icon to page view
     */
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pp_card'] = 'content-pizpalue-card';

    /**
     * Configure element type
     */
    $GLOBALS['TCA']['tt_content']['types']['pp_card'] = array_replace_recursive(
        $GLOBALS['TCA']['tt_content']['types']['textmedia'],
        ['columnsOverrides' => [
            'pages' => [
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_card.headerText',
                'config' => [
                    'type' => 'text',
                    'enableRichtext' => true,
                ],
            ],
            'teaser' => [
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_card.footerText',
                'config' => [
                    'enableRichtext' => true,
                ],
            ],
        ]]
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_card.tab.border,pages,teaser',
        'pp_card',
        'after:bodytext'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:ce_card.tab.adjust,pi_flexform',
        'pp_card',
        'after:image_zoom'
    );

    /**
     * Adds flexForm for content element configuration
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:pizpalue/Configuration/FlexForms/Card.xml',
        'pp_card'
    );
})('pizpalue');
