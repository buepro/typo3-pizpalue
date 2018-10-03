<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') or die('Access denied.');

/***************
 * Configure RTE
 */

//Make the extension configuration accessible
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}

// RTE
if (!$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['RTE']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTS/RTE.txt">');
}

// Reset extConf array to avoid errors
if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}

// Add default RTE configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['pizpalue'] = 'EXT:pizpalue/Configuration/RTE/Default.yaml';

/***************
 * PageTs
 */

// Add BackendLayouts BackendLayouts for the BackendLayout DataProvider
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package'] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package']);
}
if (!$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package']['disablePageTsBackendLayouts']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Extensions/bootstrap_package/Configuration/PageTS/Mod/web_layout.txt">'
    );
}
if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package'] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package']);
}

/***************
 * Register icons
 */

call_user_func(
    function ($extConfString) {

        // register icons
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $icons = ['ppContainerFixed','ppContainer','ppColumns2','ppColumns3','ppColumns4','ppTabs','ppAccordion'];
        foreach ($icons as $iconName) {
            $iconRegistry->registerIcon(
                'tx-pizpalue-' . $iconName,
                \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
                ['source' => 'EXT:pizpalue/Resources/Public/Icons/' . $iconName . '.png']
            );
        }
    },
    $_EXTCONF
);
