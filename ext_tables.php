<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/***************
 * Static templates
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/Main',
    'Pizpalue - Main'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/Theme',
    'Pizpalue - Theme'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/Grids',
    'Pizpalue - Grid CEs'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/t3s_jslidernews/Configuration/TypoScript',
    'Pizpalue - t3s_jslidernews 5.0.2'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/powermail/2.17.2/Configuration/TypoScript',
    'Pizpalue - powermail 2.17.2'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/powermail/3.7.0/Configuration/TypoScript',
    'Pizpalue - powermail 3.7.0'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/news/Configuration/TypoScript',
    'Pizpalue - news 6.1.1'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/newsslider/Configuration/TypoScript',
    'Pizpalue - newsslider 2.0.1'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/femanager/Configuration/TypoScript',
    'Pizpalue - femanager 2.2.0'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Extensions/tt_address/Configuration/TypoScript/3.0.3',
    'Pizpalue - tt_address 3.0.3'
);

/***************
 * Backend Styling
 */
if (TYPO3_MODE == 'BE') {

    //Configure Backend Extension
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']);
    }
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']['loginLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-logo.png';
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']['loginBackgroundImage'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-background.jpg';
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']);
    }
}
