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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_scaffold.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $scaffoldGeneralProperties = 'showFooter';
    $scaffoldHeaderProperties = 'pp-navbar-height-xs, pp-navbar-height-sm, pp-navbar-height-md, pp-navbar-height-lg, '
        . 'pp-navbar-height-xl, pp-navbar-height-xxl, pp-header-overlay-breakpoint';
    $scaffoldFooterProperties = 'rowClass, leftColumnClass, middleColumnClass, rightColumnClass';
    $scaffoldAdvancedProperties = implode(', ', [
        $scaffoldFooterProperties,
        'pp-navbar-height-xs, pp-navbar-height-sm, pp-navbar-height-lg, pp-navbar-height-xl, pp-navbar-height-xxl, '
            . 'pp-header-overlay-breakpoint',
    ]);

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.scaffold',
            $scaffoldGeneralProperties,
            'scaffold'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $scaffoldHeaderProperties,
            'scaffold'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.scaffold.footer',
            $scaffoldFooterProperties,
            'scaffold'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteScaffoldHeader' => TcaUtility::getPalette($scaffoldHeaderProperties, 'scaffold', 3),
        'paletteScaffoldFooter' => TcaUtility::getPalette(
            'showFooter, --linebreak--, rowClass, --linebreak--, leftColumnClass, middleColumnClass, rightColumnClass',
            'scaffold',
            0
        ),
    ]);

    /**
     * Advanced fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $scaffoldAdvancedProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'scaffold'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        '',
        'scaffold_show_footer'
    );

    unset($tca);
})();
