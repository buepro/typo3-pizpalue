<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Mapper\EasyconfMapper;
use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;

defined('TYPO3') or die('Access denied.');

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
    return;
}

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_menu.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];
    $tca['ctrl']['EXT']['easyconf']['dataHandlerAllowedFields'] .= ', menu_fast_items_first_content_uid, ' .
        'menu_fast_items_first_page_uid, menu_fast_items_second_content_uid, menu_fast_items_second_page_uid' .
        'menu_fast_items_third_content_uid, menu_fast_items_third_page_uid, menu_scroll_page_uid';

    /**
     * Properties
     */
    $menuMainProperties = 'style, type';
    $menuMainControlProperties = 'enableSubpageDefinition';
    $menuMainSubpageProperties = 'subpageStyle, subpageType';
    $menuTogglerProperties = 'grid-float-breakpoint, pp-header-toggler-width, pp-header-toggler-padding, ' .
        'pp-header-toggler-line-gap, pp-header-toggler-line-width, pp-header-toggler-line-height';
    $menuTogglerAdvancedProperties = 'pp-header-toggler-line-gap, pp-header-toggler-line-width, pp-header-toggler-line-height';
    $menuThemeSelectProperties = 'breadcrumb.enable, meta.enable, footer.enable, language.enable';
    $menuPizpalueSelectProperties = 'fast.enable, scroll.enable';
    $menuMetaProperties = 'navigationValue, navigationType, includeNotInMenu';
    $menuFooterProperties = 'navigationValue, navigationType, includeNotInMenu, levels, icon.enable, ' .
        'icon.width, icon.height';
    $menuLanguageProperties = 'languageValue';
    $menuFastProperties = 'items.first.iconClass, items.first.contentUid, items.first.pageUid, ' .
        'items.second.iconClass, items.second.contentUid, items.second.pageUid, ' .
        'items.third.iconClass, items.third.contentUid, items.third.pageUid';
    $menuScrollProperties = 'pageUid';
    $menuScrollAdvancedProperties = 'dataKey, menuId, offset';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.navigation',
            $menuMainProperties,
            'menu_main'
        ),
        TcaUtility::getPropertyMap(
            EasyconfMapper::class,
            'pizpalue.menu.main',
            $menuMainControlProperties,
            'menu_main'
        ),
        TcaUtility::getPropertyMap(
            EasyconfMapper::class,
            'pizpalue.menu.main',
            $menuMainSubpageProperties,
            'menu_main'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'plugin.bootstrap_package.settings.scss',
            $menuTogglerProperties,
            'menu_toggler'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme',
            $menuThemeSelectProperties,
            'menu'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.menu',
            $menuPizpalueSelectProperties,
            'menu'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.meta',
            $menuMetaProperties,
            'menu_meta'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.footernavigation',
            $menuFooterProperties,
            'menu_footer'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.language',
            $menuLanguageProperties,
            'menu_language'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.menu.fast',
            $menuFastProperties,
            'menu_fast'
        ),
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.menu.scroll',
            implode(', ', [$menuScrollProperties, $menuScrollAdvancedProperties]),
            'menu_scroll'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));
    $tca['columns']['menu_scroll_page_uid']['description'] = $l10nFile . ':menu_scroll_page_uid.description';
    $tca['columns']['menu_scroll_data_key']['description'] = $l10nFile . ':menu_scroll_data_key.description';
    $tca['columns']['menu_scroll_menu_id']['description'] = $l10nFile . ':menu_scroll_menu_id.description';
    $tca['columns']['menu_scroll_offset']['description'] = $l10nFile . ':menu_scroll_offset.description';

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteMenuMain' => TcaUtility::getPalette(
            $menuMainProperties . ', enableSubpageDefinition',
            'menu_main',
            2
        ),
        'paletteMenuMainSubpage' => TcaUtility::getPalette($menuMainSubpageProperties, 'menu_main'),
        'paletteMenuToggler' => TcaUtility::getPalette($menuTogglerProperties, 'menu_toggler', 3),
        'paletteMenuSelect' => TcaUtility::getPalette(
            implode(', ', [$menuThemeSelectProperties, $menuPizpalueSelectProperties]),
            'menu',
            4
        ),
        'paletteMenuMeta' => TcaUtility::getPalette($menuMetaProperties, 'menu_meta', 0),
        'paletteMenuFooter' => TcaUtility::getPalette(
            'navigationValue, navigationType, levels, includeNotInMenu, ' .
            '--linebreak--, icon.enable, --linebreak--, icon.width, icon.height',
            'menu_footer',
            0
        ),
        'paletteMenuLanguage' => TcaUtility::getPalette($menuLanguageProperties, 'menu_language'),
        'paletteMenuFast' => TcaUtility::getPalette($menuFastProperties, 'menu_fast', 3),
        'paletteMenuScroll' => TcaUtility::getPalette(
            implode(', ', [$menuScrollProperties, $menuScrollAdvancedProperties]),
            'menu_scroll',
            2
        ),
    ]);
    $tca['palettes']['paletteMenuFast']['description'] = $l10nFile . ':paletteMenuFast.description';
    $tca['palettes']['paletteMenuScroll']['description'] = $l10nFile . ':paletteMenuScroll.description';

    /**
     * Advanced fields
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $menuTogglerAdvancedProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'menu_toggler'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $menuScrollAdvancedProperties,
        ['displayCond' => 'FIELD:admin_easyconf_show_all_properties:REQ:true'],
        'menu_scroll'
    );

    /**
     * Fields triggering reload
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['onChange' => 'reload'],
        '',
        'menu_main_enable_subpage_definition, menu_meta_enable, menu_footer_enable, menu_footer_icon_enable, ' .
        'menu_language_enable, menu_fast_enable, menu_scroll_enable'
    );

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        '',
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        '',
        'menu_main_enable_subpage_definition, menu_breadcrumb_enable, menu_meta_enable, ' .
            'menu_meta_include_not_in_menu, menu_footer_enable, menu_footer_include_not_in_menu, ' .
            'menu_footer_icon_enable, menu_language_enable, menu_fast_enable, menu_fast_items_second_enable, ' .
            'menu_fast_items_third_enable, menu_scroll_enable'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'style, subpageStyle',
        [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'none',
                'items' => [
                    [sprintf('%s:none', $l10nFile), 'none'],
                    [sprintf('%s:menu_main_style.default', $l10nFile), 'default'],
                    [sprintf('%s:menu_main_style.default-transition', $l10nFile), 'default-transition'],
                    [sprintf('%s:menu_main_style.inverse', $l10nFile), 'inverse'],
                    [sprintf('%s:menu_main_style.inverse-transition', $l10nFile), 'inverse-transition'],
                ],
            ],
        ],
        'menu_main'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'type, subpageType',
        [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'none',
                'items' => [
                    [sprintf('%s:none', $l10nFile), 'none'],
                    [sprintf('%s:menu_main_type.default', $l10nFile), ''],
                    [sprintf('%s:menu_main_type.top', $l10nFile), 'top'],
                ],
            ],
        ],
        'menu_main'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'subpageStyle, subpageType',
        ['displayCond' => 'FIELD:menu_main_enable_subpage_definition:REQ:true'],
        'menu_main'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'navigationValue, navigationType, includeNotInMenu',
        ['displayCond' => 'FIELD:menu_meta_enable:REQ:true'],
        'menu_meta'
    );
    $tca['columns']['menu_footer_enable']['tx_easyconf']['path'] = 'page.theme.footernavigation.enable';
    TcaUtility::modifyColumns(
        $tca['columns'],
        'navigationValue, navigationType, includeNotInMenu, levels, icon.enable',
        ['displayCond' => 'FIELD:menu_footer_enable:REQ:true'],
        'menu_footer'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'icon.width, icon.height',
        ['displayCond' => ['AND' => ['FIELD:menu_footer_enable:REQ:true', 'FIELD:menu_footer_icon_enable:REQ:true']]],
        'menu_footer'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'languageValue',
        ['displayCond' => ['AND' => ['FIELD:admin_easyconf_show_all_properties:REQ:true', 'FIELD:menu_language_enable:REQ:true']]],
        'menu_language'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        TcaUtility::excludeProperties($menuFastProperties, 'enable'),
        ['displayCond' => 'FIELD:menu_fast_enable:REQ:true'],
        'menu_fast'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'items.first.contentUid, items.second.contentUid, items.third.contentUid',
        ['config' => ['type' => 'group', 'allowed' => 'tt_content', 'maxitems' => 1, 'size' => 1]],
        'menu_fast'
    );
    $tca['columns']['menu_fast_enable']['tx_easyconf']['path'] = 'pizpalue.menu.fast.enable';
    TcaUtility::modifyColumns(
        $tca['columns'],
        'items.first.pageUid, items.second.pageUid, items.third.pageUid',
        ['config' => ['type' => 'group', 'allowed' => 'pages', 'maxitems' => 1, 'size' => 1]],
        'menu_fast'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $menuScrollProperties,
        ['displayCond' => 'FIELD:menu_scroll_enable:REQ:true'],
        'menu_scroll'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $menuScrollAdvancedProperties,
        ['displayCond' => ['AND' => ['FIELD:admin_easyconf_show_all_properties:REQ:true', 'FIELD:menu_scroll_enable:REQ:true']]],
        'menu_scroll'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        'pageUid',
        ['config' => ['type' => 'group', 'allowed' => 'pages', 'maxitems' => 1, 'size' => 1]],
        'menu_scroll'
    );

    unset($tca);
})();
