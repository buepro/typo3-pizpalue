<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(static function () {
    /**
     * Defines side ratios
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
     * Assigns side ratios to content elements with images
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
     * Assigns side ratios to content elements with assets
     */
    foreach (['media', 'textmedia'] as $contentElement) {
        $cropVariants = &$GLOBALS['TCA']['tt_content']['types'][$contentElement]['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'];
        $cropVariants['default']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['large']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['medium']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['small']['allowedAspectRatios'] = $aspectRatios;
        $cropVariants['extrasmall']['allowedAspectRatios'] = $aspectRatios;
    }
})();
