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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_agency.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $contactProperties = 'name, link, phone, email';
    $brandProperties = 'slogan, reference';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.agency',
            implode(', ', [$contactProperties, $brandProperties]),
            'agency'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteAgency' => TcaUtility::getPalette(
            'name, link, phone, email, slogan, --linebreak--, reference',
            'agency'
        ),
    ]);

    /**
     * Advanced fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'reference',
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'agency'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'reference',
        ['config' => ['type' => 'text', 'renderType' => 't3editor', 'format' => 'html']],
        'agency'
    );

    unset($tca);
})();
