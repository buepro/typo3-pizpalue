<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Utility\TcaUtility;

defined('TYPO3') or die('Access denied.');

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
    return;
}

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Define type
     */
    $tabs = [
        'tabOwner' => implode(', ', [
            '--palette--;;paletteCustomerCompany',
            '--palette--;;paletteCustomerUrl',
            '--palette--;;paletteCustomerAddress',
            '--palette--;;paletteCustomerContact',
        ]),
        'tabScaffold' => implode(', ', [
            sprintf('--palette--;%s:paletteScaffoldElements;paletteScaffoldElements', $l10nFile),
            sprintf('--palette--;%s:paletteScaffoldHeader;paletteScaffoldHeader', $l10nFile),
            sprintf('--palette--;%s:paletteScaffoldFooter;paletteScaffoldFooter', $l10nFile),
            sprintf('--palette--;%s:paletteScaffoldCopyright;paletteScaffoldCopyright', $l10nFile),
            sprintf('--palette--;%s:paletteScaffoldContact;paletteScaffoldContact', $l10nFile),
        ]),
        'tabMenu' => implode(', ', [
            sprintf('--palette--;%s:paletteMenuSelect;paletteMenuSelect', $l10nFile),
            sprintf('--palette--;%s:paletteMenuMain;paletteMenuMain', $l10nFile),
            sprintf('--palette--;%s:paletteMenuMainSubpage;paletteMenuMainSubpage', $l10nFile),
            sprintf('--palette--;%s:paletteMenuToggler;paletteMenuToggler', $l10nFile),
            sprintf('--palette--;%s:paletteMenuFast;paletteMenuFast', $l10nFile),
            sprintf('--palette--;%s:paletteMenuFooter;paletteMenuFooter', $l10nFile),
            sprintf('--palette--;%s:paletteMenuCopyright;paletteMenuCopyright', $l10nFile),
            sprintf('--palette--;%s:paletteMenuLanguage;paletteMenuLanguage', $l10nFile),
            sprintf('--palette--;%s:paletteMenuMeta;paletteMenuMeta', $l10nFile),
            sprintf('--palette--;%s:paletteMenuScroll;paletteMenuScroll', $l10nFile),
        ]),
        'tabLogo' => implode(', ', [
            sprintf('--palette--;%s:paletteLogo;paletteLogo', $l10nFile),
            sprintf('--palette--;%s:paletteAppicon;paletteAppicon', $l10nFile),
        ]),
        'tabColors' => implode(', ', [
            sprintf('--palette--;%s:paletteColorsMain;paletteColorsMain', $l10nFile),
            sprintf('--palette--;%s:paletteColorsText;paletteColorsText', $l10nFile),
            sprintf('--palette--;%s:paletteColorsLightHeader;paletteColorsLightHeader', $l10nFile),
            sprintf('--palette--;%s:paletteColorsDarkHeader;paletteColorsDarkHeader', $l10nFile),
            sprintf('--palette--;%s:paletteColorsFooter;paletteColorsFooter', $l10nFile),
            sprintf('--palette--;%s:paletteColorsFooterMeta;paletteColorsFooterMeta', $l10nFile),
        ]),
        'tabStyle' => implode(', ', [
            sprintf('--palette--;%s:paletteStyleGlobal;paletteStyleGlobal', $l10nFile),
            sprintf('--palette--;%s:paletteStyleRounded;paletteStyleRounded', $l10nFile),
            sprintf('--palette--;%s:paletteStyleScss;paletteStyleScss', $l10nFile),
            sprintf('--palette--;%s:paletteStyleVarious;paletteStyleVarious', $l10nFile),
        ]),
        'tabFont' => implode(', ', [
            sprintf('--palette--;%s:paletteFontControl;paletteFontControl', $l10nFile),
            sprintf('--palette--;%s:paletteFontGeneral;paletteFontGeneral', $l10nFile),
            sprintf('--palette--;%s:paletteFontGoogle;paletteFontGoogle', $l10nFile),
            sprintf('--palette--;%s:paletteFontNormal;paletteFontNormal', $l10nFile),
            sprintf('--palette--;%s:paletteFontHeadings;paletteFontHeadings', $l10nFile),
        ]),
        'tabSocial' => implode(', ', [
            sprintf('--palette--;%s:paletteSocialControl;paletteSocialControl', $l10nFile),
            sprintf('--palette--;%s:paletteSocialChannel;paletteSocialChannel', $l10nFile),
        ]),
        'tabFeature' => implode(', ', [
            sprintf('--palette--;%s:paletteFeatureControl;paletteFeatureControl', $l10nFile),
            sprintf('--palette--;%s:paletteFeatureVarious;paletteFeatureVarious', $l10nFile),
        ]),
        'tabVarious' => implode(', ', [
            sprintf('--palette--;%s:paletteAnimation;paletteAnimation', $l10nFile),
            sprintf('--palette--;%s:paletteSeo;paletteSeo', $l10nFile),
            sprintf('--palette--;%s:paletteGoogle;paletteGoogle', $l10nFile),
            sprintf('--palette--;%s:paletteCookie;paletteCookie', $l10nFile),
        ]),
        'tabAdmin' => implode(', ', [
            sprintf('--palette--;%s:paletteAdminGeneral;paletteAdminGeneral', $l10nFile),
            sprintf('--palette--;%s:paletteAgency;paletteAgency', $l10nFile),
            sprintf('--palette--;%s:paletteAdminSiteconf;paletteAdminSiteconf', $l10nFile),
            sprintf('--palette--;%s:paletteAdminFormat;paletteAdminFormat', $l10nFile),
        ]),
    ];
    $tca['types'][0] = TcaUtility::getType($tabs, $l10nFile);

    unset($tca);
})();
