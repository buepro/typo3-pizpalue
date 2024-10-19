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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_feature.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $pizpalueControlProperties = 'fontAwesome.enable, revealFooter, slideNavContent, content.insertData';
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
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteFeatureControl' => TcaUtility::getPalette(
            $pizpalueControlProperties,
            'feature',
            3
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
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $pizpalueControlProperties,
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'feature'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'imageLoading',
        ['config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
            [
                'label' => $l10nFile . ':feature_image_loading.eager',
                'value' => 'eager',
            ],
            [
                'label' => $l10nFile . ':feature_image_loading.lazy',
                'value' => 'lazy',
            ],
            [
                'label' => $l10nFile . ':feature_image_loading.auto',
                'value' => 'auto',
            ],
        ]]],
        'feature'
    );

    unset($tca);
})();
