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
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_cookie.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];
    $tca['ctrl']['EXT']['easyconf']['dataHandlerAllowedFields'] .= ', cookie_content_href';

    /**
     * Properties
     */
    $controlProperties = 'enable, static';
    $locationProperties = 'law.regionalLaw, location, law.countryCode';
    $appearanceProperties = 'layout, position';
    $contentProperties = 'content.href';
    $behaviourProperties = 'type, revokable, cookie.expiryDays';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.cookieconsent',
            implode(', ', [$controlProperties, $locationProperties, $appearanceProperties, $contentProperties,
                $behaviourProperties]),
            'cookie'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));
    $tca['columns']['cookie_revokable']['description'] = $l10nFile . ':cookie_revokable.description';
    $tca['columns']['cookie_location']['description'] = $l10nFile . ':cookie_location.description';
    $tca['columns']['cookie_law_country_code']['description'] = $l10nFile . ':cookie_law_country_code.description';
    $tca['columns']['cookie_law_regional_law']['description'] = $l10nFile . ':cookie_law_regional_law.description';

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteCookie' => TcaUtility::getPalette(
            implode(', ', ['enable, --linebreak--',
                $contentProperties, '--linebreak--',
                $locationProperties, '--linebreak--',
                'static, --linebreak--',
                $appearanceProperties, '--linebreak--',
                'type, --linebreak--, revokable, --linebreak--, cookie.expiryDays',
            ]),
            'cookie',
            0
        ),
    ]);

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'enable, static, law.regionalLaw, location',
        ['onChange' => 'reload', 'config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'cookie'
    );

    /**
     * Conditional fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $contentProperties,
        ['displayCond' => 'FIELD:cookie_enable:REQ:true'],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        implode(', ', ['static', $locationProperties, $appearanceProperties, $behaviourProperties]),
        ['displayCond' => ['AND' => [
            'FIELD:cookie_enable:REQ:true',
            'FIELD:admin_easyconf_show_all_properties:REQ:true',
        ]]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'location',
        ['displayCond' => ['AND' => [
            'FIELD:cookie_enable:REQ:true',
            'FIELD:admin_easyconf_show_all_properties:REQ:true',
            'FIELD:cookie_law_regional_law:REQ:false',
        ]]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'law.countryCode',
        ['displayCond' => ['AND' => [
            'FIELD:cookie_enable:REQ:true',
            'FIELD:admin_easyconf_show_all_properties:REQ:true',
            'FIELD:cookie_law_regional_law:REQ:false',
            'FIELD:cookie_location:REQ:false',
        ]]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'position',
        ['displayCond' => ['AND' => [
            'FIELD:cookie_enable:REQ:true',
            'FIELD:admin_easyconf_show_all_properties:REQ:true',
            'FIELD:cookie_static:REQ:false',
        ]]],
        'cookie'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'static, revokable, location, law.regionalLaw',
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'layout',
        ['config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
            [
                'label' => $l10nFile . ':cookie_layout.basic',
                'value' => 'basic',
            ],
            [
                'label' => $l10nFile . ':cookie_layout.basic-close',
                'value' => 'basic-close',
            ],
            [
                'label' => $l10nFile . ':cookie_layout.basic-header',
                'value' => 'basic-header',
            ],
        ]]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'type',
        ['config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
            [
                'label' => $l10nFile . ':cookie_type.info',
                'value' => 'info',
            ],
            [
                'label' => $l10nFile . ':cookie_type.opt-in',
                'value' => 'opt-in',
            ],
            [
                'label' => $l10nFile . ':cookie_type.opt-out',
                'value' => 'opt-out',
            ],
        ]]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'position',
        ['config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
            [
                'label' => $l10nFile . ':cookie_position.top',
                'value' => 'top',
            ],
            [
                'label' => $l10nFile . ':cookie_position.bottom',
                'value' => 'bottom',
            ],
            [
                'label' => $l10nFile . ':cookie_position.top-left',
                'value' => 'top-left',
            ],
            [
                'label' => $l10nFile . ':cookie_position.top-right',
                'value' => 'top-right',
            ],
            [
                'label' => $l10nFile . ':cookie_position.bottom-left',
                'value' => 'bottom-left',
            ],
            [
                'label' => $l10nFile . ':cookie_position.bottom-right',
                'value' => 'bottom-right',
            ],
        ]]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'content.href',
        ['config' => ['type' => 'link', 'allowedTypes' => ['page']]],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'cookie.expiryDays',
        ['config' => ['size' => 4, 'eval' => 'num']],
        'cookie'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'law.countryCode',
        ['config' => ['size' => 4]],
        'cookie'
    );

    unset($tca);
})();
