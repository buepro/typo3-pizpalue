<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * Define side ratios
     */
    $defaultAspectRatios = $GLOBALS['TCA']['tt_content']['columns']['background_image']['config']['overrideChildTca']
        ['columns']['crop']['config']['cropVariants']['default']['allowedAspectRatios'];
    $aspectRatios = [
        '2:1' => [
            'title' => '2:1',
            'value' => 2
        ],
        '16:9' => $defaultAspectRatios['16:9'],
        '4:3' => $defaultAspectRatios['4:3'],
        '1:1' => $defaultAspectRatios['1:1'],
        '3:4' => [
            'title' => '3:4',
            'value' => 0.75
        ],
        '9:16' => [
            'title' => '9:16',
            'value' => 0.5625
        ],
        '1:2' => [
            'title' => '1:2',
            'value' => 0.5
        ],
        'NaN' => $defaultAspectRatios['NaN'],
    ];

    /**
     * Assign side ratios to content elements with images
     */
    foreach (['image', 'textpic', 'pp_picoverlay'] as $contentElement) {
        if (!isset($GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['image']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'])) {
            $GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['image']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] =
                $GLOBALS['TCA']['tt_content']['types']['image']['columnsOverrides']['image']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];
        }
        $cropVariants = &$GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['image']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];
        $cropVariants['default']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['large']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['medium']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['small']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['extrasmall']['allowedAspectRatios'] = $aspectRatios;
    }

    /**
     * Assign side ratios to content elements with assets
     */
    foreach (['list', 'media', 'textmedia'] as $contentElement) {
        if (!isset($GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'])) {
            $GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] =
                $GLOBALS['TCA']['tt_content']['types']['media']['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];
        }
        $cropVariants = &$GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];
        $cropVariants['default']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['large']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['medium']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['small']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['extrasmall']['allowedAspectRatios'] = $aspectRatios;
    }

    /**
     * Assign side ratios to extension news
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
        $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['assets']['label'] = 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.dummyAsset';
        $GLOBALS['TCA']['tt_content']['types']['list']['columnsOverrides']['assets']['config']['maxitems'] = 1;
        foreach ([0, 1, 2] as $type) {
            $GLOBALS['TCA']['tx_news_domain_model_news']['types'][$type]['columnsOverrides']['fal_media']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] =
                $GLOBALS['TCA']['tt_content']['types']['media']['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];
        }
    }
})();
