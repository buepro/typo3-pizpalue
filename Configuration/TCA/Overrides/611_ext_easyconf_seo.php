<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;

defined('TYPO3') or die('Access denied.');

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
    return;
}

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_seo.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $googleProperties = 'meta.google-site-verification, tracking.google.trackingID, tracking.google.ga4MeasureId, ' .
        'tracking.google.tagManagerContainerId';
    $seoProperties = 'optimizeLinkTargets, consentTrackingCode';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page',
            $googleProperties,
            'seo'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.seo',
            $seoProperties,
            'seo'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));
    $tca['columns']['seo_optimize_link_targets']['description'] = $l10nFile . ':seo_optimize_link_targets.description';
    $tca['columns']['seo_consent_tracking_code']['description'] = $l10nFile . ':seo_consent_tracking_code.description';

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteGoogle' => TcaUtility::getPalette(
            $googleProperties,
            'seo'
        ),
        'paletteSeo' => TcaUtility::getPalette(
            $seoProperties,
            'seo',
            3
        ),
    ]);

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $seoProperties,
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'seo'
    );

    unset($tca);
})();
