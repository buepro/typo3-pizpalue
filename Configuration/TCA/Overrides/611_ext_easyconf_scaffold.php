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
    $tca['ctrl']['EXT']['easyconf']['dataHandlerAllowedFields'] .= ', scaffold_contact_button_page_uid';

    /**
     * Properties
     */
    $scaffoldPizpalueElementsProperties = 'showFooter';
    $scaffoldBootstrapElementsProperties = 'copyright.enable, contact.enable';
    $scaffoldHeaderProperties = 'pp-navbar-height-xs, pp-navbar-height-sm, pp-navbar-height-md, pp-navbar-height-lg, '
        . 'pp-navbar-height-xl, pp-navbar-height-xxl, pp-header-overlay-breakpoint';
    $scaffoldFooterProperties = 'rowClass, leftColumnClass, middleColumnClass, rightColumnClass';
    $scaffoldCopyrightProperties = 'text';
    $scaffoldContactProperties = 'label, button.label, button.pageUid';
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
            $scaffoldPizpalueElementsProperties,
            'scaffold'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme',
            $scaffoldBootstrapElementsProperties,
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
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'easyconf.substitutions.page.theme.copyright',
            $scaffoldCopyrightProperties,
            'scaffold_copyright'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.contact',
            $scaffoldContactProperties,
            'scaffold_contact'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteScaffoldElements' => TcaUtility::getPalette(
            implode(',', [$scaffoldPizpalueElementsProperties, $scaffoldBootstrapElementsProperties]),
            'scaffold',
            3
        ),
        'paletteScaffoldHeader' => TcaUtility::getPalette($scaffoldHeaderProperties, 'scaffold', 3),
        'paletteScaffoldFooter' => TcaUtility::getPalette(
            'rowClass, --linebreak--, leftColumnClass, middleColumnClass, rightColumnClass',
            'scaffold',
            0
        ),
        'paletteScaffoldCopyright' => TcaUtility::getPalette('text', 'scaffold_copyright'),
        'paletteScaffoldContact' => TcaUtility::getPalette($scaffoldContactProperties, 'scaffold_contact'),
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
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        implode(',', [$scaffoldPizpalueElementsProperties, $scaffoldBootstrapElementsProperties]),
        ['onChange' => 'reload'],
        'scaffold'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        implode(',', [$scaffoldPizpalueElementsProperties, $scaffoldBootstrapElementsProperties]),
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'scaffold'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $scaffoldFooterProperties,
        ['displayCond' => 'FIELD:scaffold_show_footer:REQ:true'],
        'scaffold'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $scaffoldCopyrightProperties,
        ['displayCond' => 'FIELD:scaffold_copyright_enable:REQ:true'],
        'scaffold_copyright'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['config' => ['type' => 'text', 'renderType' => 't3editor', 'format' => 'html', 'rows' => 3]],
        '',
        'scaffold_copyright_text'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $scaffoldContactProperties,
        ['displayCond' => 'FIELD:scaffold_contact_enable:REQ:true'],
        'scaffold_contact'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'button.pageUid',
        ['config' => ['type' => 'group', 'allowed' => 'pages', 'maxitems' => 1, 'size' => 1]],
        'scaffold_contact'
    );

    unset($tca);
})();
