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
        $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] =
            $GLOBALS['TCA']['tt_content']['types']['image']['columnsOverrides']['image']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];

        // Set cropping for media in news records
        $aspectRatios = $GLOBALS['TCA']['tt_content']['types']['image']['columnsOverrides']['image']['config']
            ['overrideChildTca']['columns']['crop']['config']['cropVariants']['default']['allowedAspectRatios'];
        foreach ([0, 1, 2] as $cType) {
            \Buepro\Pizpalue\Utility\TcaUtility::setAllowedAspectRatiosForCType($aspectRatios, 'tx_news_domain_model_news', $cType, 'fal_media');
        }
    }
})();
