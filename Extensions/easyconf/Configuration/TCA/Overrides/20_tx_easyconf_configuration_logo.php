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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_logo.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];
    $tca['ctrl']['EXT']['easyconf']['dataHandlerAllowedFields'] .=
        ', logo_file_reference, logo_file_inverted_reference, appicon_generator_archive, appicon_generator_text';

    /**
     * Properties
     */
    $logoProperties = 'fileReference, fileInvertedReference, file, fileInverted, width, height, alt, linktitle';
    $logoTsProperties = 'file, fileInverted, width, height';
    $logoTsSubstitutionProperties = 'alt, linktitle';
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
            TypoScriptConstantMapper::class,
            'easyconf.substitutions.page.logo',
            $logoTsSubstitutionProperties,
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
    $tca['columns']['appicon_generator_text']['description'] = $l10nFile . ':appicon_generator_text.description';
    $tca['columns']['appicon_file']['description'] = $l10nFile . ':appicon_file.description';

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteLogo' => TcaUtility::getPalette($logoProperties, 'logo'),
        'paletteAppicon' => TcaUtility::getPalette('generatorArchive, --linebreak--, file, generatorText', 'appicon'),
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
    TcaUtility::modifyColumns(
        $tca['columns'],
        'generatorText',
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'appicon'
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
        'appicon_generator_archive'
    );

    /**
     * Modify columns
     */
    foreach (['logo_file_reference', 'logo_file_inverted_reference'] as $fieldName) {
        $tca['columns'][$fieldName]['config'] = [
            'type' => 'file',
            'allowed' => 'svg,jpg,png,gif',
            'disallowed' => '',
            'maxitems' => 1,
            'appearance' => [
                'useSortable' => false,
                'enabledControls' => ['hide' => false],
            ],
            'overrideChildTca' => [
                'types' => [
                    ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                    ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                    ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                ],
            ],
        ];
    }
    TcaUtility::modifyColumns(
        $tca['columns'],
        'file, fileInverted, width, height',
        ['displayCond' => [
            'AND' => [
                'FIELD:logo_file_reference:REQ:false',
                'FIELD:logo_file_inverted_reference:REQ:false',
            ],
        ]],
        'logo'
    );
    $tca['columns']['appicon_generator_archive']['config'] = [
        'type' => 'file',
        'allowed' => 'zip',
        'disallowed' => '',
        'maxitems' => 1,
        'appearance' => [
            'useSortable' => false,
            'enabledControls' => ['hide' => false],
        ],
        'overrideChildTca' => [
            'types' => [
                ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
                ['showitem' => '--palette--;;filePalette'], ['showitem' => '--palette--;;filePalette'],
            ],
        ],
    ];
    TcaUtility::modifyColumns(
        $tca['columns'],
        'generatorText',
        ['config' => ['type' => 'text', 'renderType' => 't3editor', 'format' => 'html', 'eval' => 'trim', 'rows' => 10]],
        'appicon'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'file',
        ['displayCond' => 'FIELD:appicon_generator_archive:REQ:false'],
        'appicon'
    );

    unset($tca);
})();
