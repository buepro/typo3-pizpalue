<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Mapper\SiteConfigurationMapper;
use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;

defined('TYPO3') or die('Access denied.');

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_style.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $styleGlobalScssProperties = 'enable-rounded, enable-shadows, enable-gradients';
    $styleGlobalSiteProperties = 'enableScss';
    $styleGlobalProperties = implode(', ', [$styleGlobalScssProperties, $styleGlobalSiteProperties]);
    $styleRoundedProperties = 'border-radius, border-radius-sm, border-radius-lg, frame-embedded-border-radius';
    $styleRoundedAdvancedProperties = 'border-radius-sm, border-radius-lg, frame-embedded-border-radius';
    $styleScssProperties = 'declarations';
    $styleVariousProperties = 'headings-margin-bottom, grid-gutter-width, pp-tile-gutter';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $styleGlobalScssProperties,
            'style_global'
        ),
        TcaUtility::getPropertyMap(
            SiteConfigurationMapper::class,
            'easyconf.data.style.global',
            $styleGlobalSiteProperties,
            'style_global'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $styleRoundedProperties,
            'style_rounded'
        ),
        TcaUtility::getPropertyMap(
            '',
            '',
            $styleScssProperties,
            'style_scss'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $styleVariousProperties,
            'style_various'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteStyleGlobal' => TcaUtility::getPalette($styleGlobalProperties, 'style_global', 4),
        'paletteStyleRounded' => TcaUtility::getPalette($styleRoundedProperties, 'style_rounded', 4),
        'paletteStyleScss' => TcaUtility::getPalette($styleScssProperties, 'style_scss'),
        'paletteStyleVarious' => TcaUtility::getPalette($styleVariousProperties, 'style_various', 4),
    ]);

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'enable-rounded, enableScss',
        ['onChange' => 'reload'],
        'style_global'
    );

    /**
     * Conditional fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'border-radius',
        ['displayCond' => 'FIELD:style_global_enable-rounded:REQ:true'],
        'style_rounded'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $styleRoundedAdvancedProperties,
        ['displayCond' => ['AND' => [
            'FIELD:style_global_enable-rounded:REQ:true',
            'FIELD:admin_easyconf_show_all_properties:REQ:true',
        ]]],
        'style_rounded'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $styleScssProperties,
        ['displayCond' => 'FIELD:style_global_enable_scss:REQ:true'],
        'style_scss'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $styleGlobalProperties,
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'style_global'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $styleGlobalScssProperties,
        ['tx_easyconf' => ['valueMap' => [0 => 'false', 1 => 'true']]],
        'style_global'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $styleScssProperties,
        ['config' => ['type' => 'text', 'renderType' => 't3editor', 'format' => 'css', 'rows' => 10]],
        'style_scss'
    );

    unset($tca);
})();
