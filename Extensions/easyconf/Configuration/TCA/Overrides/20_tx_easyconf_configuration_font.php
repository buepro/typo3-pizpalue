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

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_font.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $fontControlProperties = 'googleFont.enable, enableHeadingsFont';
    $fontGeneralProperties = 'font-size-root';
    $fontNormalProperties = 'font-family-base, font-weight-normal, font-weight-light, font-weight-bold, font-size-base';
    $fontHeadingsProperties = 'headings-font-family, headings-font-weight, headings-font-style, headings-line-height, ' .
        'h1-font-size, h2-font-size, h3-font-size, h4-font-size, h5-font-size, h6-font-size';
    $fontGoogleProperties = 'font, weight';
    $fontGoogleHeadingsProperties = 'google-headings-webfont, google-headings-webfont-weight';
    $fontAdvancedProperties = 'general_font-size-root, ' .
        'normal_font-weight-light, normal_font-weight-bold, normal_font-size-base, ' .
        'headings_headings-font-style, headings_headings-line-height, headings_h4-font-size, headings_h5-font-size, ' .
        'headings_h6-font-size';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $fontGeneralProperties,
            'font_general'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $fontNormalProperties,
            'font_normal'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $fontHeadingsProperties,
            'font_headings'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme',
            $fontControlProperties,
            'font_control'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.googleFont',
            $fontGoogleProperties,
            'font_google'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $fontGoogleHeadingsProperties,
            'font'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteFontControl' => TcaUtility::getPalette($fontControlProperties, 'font_control', 4),
        'paletteFontGeneral' => TcaUtility::getPalette($fontGeneralProperties, 'font_general', 2),
        'paletteFontNormal' => TcaUtility::getPalette($fontNormalProperties, 'font_normal', 2),
        'paletteFontHeadings' => TcaUtility::getPalette($fontHeadingsProperties, 'font_headings', 2),
        'paletteFontGoogle' => TcaUtility::getPalette(
            implode(', ', [
                'google_font, google_weight',
                '--linebreak--',
                $fontGoogleHeadingsProperties
            ]),
            'font',
            0
        ),
    ]);

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $fontControlProperties,
        ['onChange' => 'reload'],
        'font_control'
    );

    /**
     * Advanced properties
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $fontAdvancedProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'font'
    );

    /**
     * Conditional properties
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'headings-font-family',
        ['displayCond' => 'FIELD:font_control_enable_headings_font:REQ:true'],
        'font_headings'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $fontGoogleProperties,
        [
            'config' => ['required' => true],
            'displayCond' => 'FIELD:font_control_google_font_enable:REQ:true'
        ],
        'font_google'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $fontGoogleHeadingsProperties,
        [
            'config' => ['required' => true],
            'displayCond' => ['AND' => [
                'FIELD:font_control_google_font_enable:REQ:true',
                'FIELD:font_control_enable_headings_font:REQ:true'
            ]]
        ],
        'font'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $fontControlProperties,
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'font_control'
    );
    $googleFonts = [
        ['Roboto', 'Roboto'],
        ['Open Sans', 'Open Sans'],
        ['Lato', 'Lato'],
        ['Monserrat', 'Monserrat'],
        ['Roboto Condensed', 'Roboto Condensed'],
        ['Source Sans Pro', 'Source Sans Pro'],
        ['Oswald', 'Oswald'],
        ['Raleway', 'Raleway'],
        ['PT Sans', 'PT Sans'],
        ['Noto Sans', 'Noto Sans'],
        ['Open Sans Condensed', 'Open Sans Condensed'],
        ['Ubuntu', 'Ubuntu'],
        ['Poppins', 'Poppins'],
        ['---', ''],
        ['Slabo 27px', 'Slabo 27px'],
        ['Merriweather', 'Merriweather'],
        ['Roboto Slab', 'Roboto Slab'],
        ['Noto Serif', 'Noto Serif'],
        ['Playfair Display', 'Playfair Display'],
        ['Bitter', 'Bitter'],
        ['Arvo', 'Arvo'],
        ['Libre Baskerville', 'Libre Baskerville'],
        ['---', ''],
        ['Lobster', 'Lobster'],
        ['Indie Flower', 'Indie Flower']
    ];
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['config' => ['valuePicker' => ['items' => $googleFonts]]],
        '',
        'font_google_font, font_google-headings-webfont'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['config' => ['eval' => 'num', 'required' => true]],
        '',
        'font_normal_font-weight-normal, font_headings_headings-font-weight'
    );

    unset($tca);
})();
