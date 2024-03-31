<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    if (
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news') &&
        \Buepro\Pizpalue\Utility\VersionUtility::getExtensionVersion('news') >= 11000000
    ) {
        $typeList = [];
        foreach ($GLOBALS['TCA']['tt_content']['types'] as $key => &$type) {
            if (strpos((string)$key, 'news_') !== 0) {
                continue;
            }
            $typeList[] = $key;
            // Configure dummy asset field
            $type['columnsOverrides']['assets']['label'] =
                'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.dummyAsset';
            $type['columnsOverrides']['assets']['config']['maxitems'] = 1;
            $type['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']
                ['cropVariants'] = \Buepro\Pizpalue\Utility\TcaUtility::getCropVariants();
        }
        unset($type);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'assets',
            implode(',', $typeList),
            'after:pi_flexform'
        );
        // Set cropping for media in news records
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_media']['config']['overrideChildTca']['columns']
            ['crop']['config']['cropVariants'] = \Buepro\Pizpalue\Utility\TcaUtility::getCropVariants();
    }

    // Legacy version
    // @todo Mark deprecated
    if (
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news') &&
        \Buepro\Pizpalue\Utility\VersionUtility::getExtensionVersion('news') < 11000000 &&
        // The following prevents errors in case news is installed after pizpalue
        isset($GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['news_pi1'])
    ) {
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
