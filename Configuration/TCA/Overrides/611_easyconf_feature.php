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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_feature.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];
    $tca['ctrl']['EXT']['easyconf']['dataHandlerAllowedFields'] .= ', feature_contact_button_page_uid';

    /**
     * Properties
     */
    $pizpalueControlProperties = 'fontAwesome.enable, revealFooter, slideNavContent, useStyle, content.insertData';
    $bootstrapPackageControlProperties = 'contact.enable';
    $contactProperties = 'contact.label, contact.button.label, contact.button.pageUid';
    $variousProperties = 'imageLoading';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.features',
            implode(',', [$pizpalueControlProperties, $variousProperties]),
            'feature'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme',
            implode(',', [$bootstrapPackageControlProperties, $contactProperties]),
            'feature'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteFeatureControl' => TcaUtility::getPalette(
            implode(',', [$pizpalueControlProperties, $bootstrapPackageControlProperties]),
            'feature',
            3
        ),
        'paletteFeatureContact' => TcaUtility::getPalette(
            $contactProperties,
            'feature'
        ),
        'paletteFeatureVarious' => TcaUtility::getPalette(
            $variousProperties,
            'feature'
        ),
    ]);

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'contact.enable',
        ['onChange' => 'reload'],
        'feature'
    );

    /**
     * Conditional fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $contactProperties,
        ['displayCond' => 'FIELD:feature_contact_enable:REQ:true'],
        'feature'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        implode(',', [$pizpalueControlProperties, $bootstrapPackageControlProperties]),
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'feature'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'imageLoading',
        ['config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
            [$l10nFile . ':feature_image_loading.eager', 'eager'],
            [$l10nFile . ':feature_image_loading.lazy', 'lazy'],
            [$l10nFile . ':feature_image_loading.auto', 'auto'],
        ]]],
        'feature'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'contact.button.pageUid',
        ['config' => ['type' => 'group', 'allowed' => 'pages', 'maxitems' => 1, 'size' => 1]],
        'feature'
    );

    unset($tca);
})();
