<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


/***************
 * Static templates
  */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Main',
    'Pizpalue - Main');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY,
    'Configuration/TypoScript/Grids', 'Pizpalue - Grid CEs');


/***************
 * Backend Styling
 */
if (TYPO3_MODE == 'BE') {
    /**
     * Configure Backend Extension
     */
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']);
    }

    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']['loginLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-logo.png';
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']['loginBackgroundImage'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-background.jpg';

    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']);
    }
}
