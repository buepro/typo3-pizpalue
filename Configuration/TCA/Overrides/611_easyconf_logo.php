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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_logo.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];
    $tca['ctrl']['EXT']['easyconf']['dataHandlerAllowedFields'] .=
        ', logo_file_reference, logo_file_inverted_reference, appicon_generator_archive, appicon_generator_text';

    /**
     * Properties
     */
    $logoProperties = 'fileReference, fileInvertedReference, file, fileInverted, width, height, alt, linktitle';
    $logoTsProperties = 'file, fileInverted, width, height, alt, linktitle';
    $logoHelperProperties = 'fileReference, fileInvertedReference';
    $logoAdvancedProperties = 'alt, linktitle';

    /**
     * App and favicon
     */
    $appiconProperties = 'generatorArchive, generatorText';
    $faviconProperties = 'file';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.logo',
            $logoTsProperties,
            'logo'
        ),
        TcaUtility::getPropertyMap(
            '',
            '',
            $logoHelperProperties,
            'logo'
        ),
        TcaUtility::getPropertyMap(
            '',
            '',
            $appiconProperties,
            'appicon'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.favicon',
            $faviconProperties,
            'appicon'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteLogo' => TcaUtility::getPalette($logoProperties, 'logo'),
        'paletteAppicon' => TcaUtility::getPalette(implode(', ', [$appiconProperties, $faviconProperties]), 'appicon'),
    ]);
    $tca['palettes']['paletteAppicon']['description'] = $l10nFile . ':paletteAppicon.description';

    /**
     * Advanced fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $logoAdvancedProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'logo'
    );

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['onChange' => 'reload'],
        '',
        'logo_file_reference, logo_file_inverted_reference, ' .
        'appicon_generator_archive, appicon_generator_text'
    );

    /**
     * Modify columns
     */
    foreach (['logo_file_reference', 'logo_file_inverted_reference'] as $fieldName) {
        $tca['columns'][$fieldName]['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            $fieldName,
            [
                'maxitems' => 1,
                'appearance' => [
                    'useSortable' => false,
                    'enabledControls' => ['hide' => false],
                ],
                'overrideChildTca' => [
                    'types' => [
                        ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                        ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                        ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette']
                    ],
                ],
            ],
            'svg,jpg,png,gif'
        );
    }
    TcaUtility::modifyColumns(
        $tca['columns'],
        'file, fileInverted, width, height',
        ['displayCond' => [
            'AND' => [
                'FIELD:logo_file_reference:REQ:false',
                'FIELD:logo_file_inverted_reference:REQ:false'
            ]
        ]],
        'logo'
    );
    $tca['columns']['appicon_generator_archive']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'appicon_generator_archive',
        [
            'maxitems' => 1,
            'appearance' => [
                'useSortable' => false,
                'enabledControls' => ['hide' => false],
            ],
            'overrideChildTca' => [
                'types' => [
                    ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                    ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                    ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette']
                ],
            ],
        ],
        'zip'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'generatorText',
        ['config' => ['type' => 'text', 'renderType' => 't3editor', 'format' => 'html', 'eval' => 'trim', 'rows' => 6]],
        'appicon'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'file',
        ['displayCond' => [
            'OR' => [
                'FIELD:appicon_generator_archive:REQ:false',
                'FIELD:appicon_generator_text:REQ:false'
            ]
        ]],
        'appicon'
    );

    unset($tca);
})();
