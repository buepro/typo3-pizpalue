<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
        // Enable dummy asset field
        $fields = $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['news_pi1'];
        $fields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $fields, true);
        $fields[] = 'assets';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['news_pi1'] = implode(',', $fields);

        // Configure dummy asset field
        $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['assets']['label'] =
            'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.dummyAsset';
        $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['assets']['config']['maxitems'] = 1;

        // Set cropping for dummy asset field
        $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['assets']['config']['overrideChildTca']
            ['columns']['crop']['config']['cropVariants'] = \Buepro\Pizpalue\Utility\TcaUtility::getCropVariants();

        // Set cropping for media in news records
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_media']['config']['overrideChildTca']['columns']
            ['crop']['config']['cropVariants'] = \Buepro\Pizpalue\Utility\TcaUtility::getCropVariants();
    }
})();
