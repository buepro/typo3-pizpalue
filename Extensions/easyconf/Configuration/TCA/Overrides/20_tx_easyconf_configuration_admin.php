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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_admin.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $adminEasyconfProperties = 'showAllProperties';
    $adminSiteProperties = 'siteMode, isMaintenancePage';
    $adminFormatProperties = 'dateFormat, dateStrftimeFormat, timeFormat, timeStrftimeFormat, ' .
        'dateTimeFormat, dateTimeStrftimeFormat';
    $adminSiteconfProperties = 'overwriteBase, overwriteBaseVariants, overwriteRobots, overwriteSitemap, ' .
        'manageNewsRouteEnhancer, removeLegacyNewsRouteEnhancers, overwritePageNotFound';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            SiteConfigurationMapper::class,
            'easyconf.data.admin.easyconf',
            $adminEasyconfProperties,
            'admin_easyconf'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.agency',
            $adminSiteProperties,
            'admin_site'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.general',
            $adminFormatProperties,
            'admin_format'
        ),
        TcaUtility::getPropertyMap(
            SiteConfigurationMapper::class,
            'easyconf.data.admin.siteConfiguration',
            $adminSiteconfProperties,
            'admin_siteconf'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteAdminGeneral' => TcaUtility::getPalette(
            'easyconf_showAllProperties, --linebreak--, site_siteMode, site_isMaintenancePage',
            'admin'
        ),
        'paletteAdminFormat' => TcaUtility::getPalette($adminFormatProperties, 'admin_format'),
        'paletteAdminSiteconf' => TcaUtility::getPalette($adminSiteconfProperties, 'admin_siteconf', 3),
    ]);
    $tca['palettes']['paletteAdminSiteconf']['description'] = $l10nFile . ':paletteAdminSiteconf.description';

    /**
     * Advanced fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $adminSiteconfProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'admin_siteconf'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $adminFormatProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'admin_format'
    );

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'showAllProperties',
        ['onChange' => 'reload'],
        'admin_easyconf'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'easyconf_showAllProperties, site_isMaintenancePage',
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'admin'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $adminSiteconfProperties,
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'admin_siteconf'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'siteMode',
        ['config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
            [$l10nFile . ':admin_site_site_mode.production', 0],
            [$l10nFile . ':admin_site_site_mode.maintenance', 1],
            [$l10nFile . ':admin_site_site_mode.develop', 2],
            [$l10nFile . ':admin_site_site_mode.review', 3],
        ]]],
        'admin_site'
    );

    unset($tca);
})();
