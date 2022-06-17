<?php

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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_colors.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $colorMainProperties = 'primary, secondary, complementary, body-bg';
    $colorTextProperties = 'body-color, headings-color, link-color, link-hover-color';
    $colorLightHeaderProperties = 'navbar-light-bg, navbar-light-color, navbar-light-hover-bg, navbar-light-hover-color, ' .
        'navbar-light-active-bg, navbar-light-active-color, navbar-light-disabled-bg, navbar-light-disabled-color';
    $colorDarkHeaderProperties = 'navbar-dark-bg, navbar-dark-color, navbar-dark-hover-bg, navbar-dark-hover-color, ' .
        'navbar-dark-active-bg, navbar-dark-active-color, navbar-dark-disabled-bg, navbar-dark-disabled-color';
    $colorFooterProperties = 'footer-bg, footer-color, footer-link-color, footer-link-hover-color';
    $colorFooterMetaProperties = 'footer-meta-bg, footer-meta-color, footer-meta-link-color, footer-meta-link-hover-color';
    $colorProperties = implode(', ', [$colorMainProperties, $colorTextProperties, $colorLightHeaderProperties,
        $colorDarkHeaderProperties, $colorFooterProperties, $colorFooterMetaProperties]);
    $colorAdvancedProperties = implode(', ', [
        TcaUtility::excludeProperties($colorLightHeaderProperties, 'navbar-light-bg, navbar-light-color'),
        TcaUtility::excludeProperties($colorDarkHeaderProperties, 'navbar-dark-bg, navbar-dark-color'),
        TcaUtility::excludeProperties($colorFooterProperties, 'footer-bg, footer-color'),
        TcaUtility::excludeProperties($colorFooterMetaProperties, 'footer-meta-bg, footer-meta-color'),
    ]);

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $colorProperties,
            'color'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteColorsMain' => TcaUtility::getPalette($colorMainProperties, 'color', 4),
        'paletteColorsText' => TcaUtility::getPalette($colorTextProperties, 'color', 4),
        'paletteColorsLightHeader' => TcaUtility::getPalette($colorLightHeaderProperties, 'color', 4),
        'paletteColorsDarkHeader' => TcaUtility::getPalette($colorDarkHeaderProperties, 'color', 4),
        'paletteColorsFooter' => TcaUtility::getPalette($colorFooterProperties, 'color', 4),
        'paletteColorsFooterMeta' => TcaUtility::getPalette($colorFooterMetaProperties, 'color', 4),
    ]);

    /**
     * Advanced fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $colorAdvancedProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'color'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'primary',
        ['config' => ['renderType' => 'colorpicker']],
        'color'
    );

    unset($tca);
})();
