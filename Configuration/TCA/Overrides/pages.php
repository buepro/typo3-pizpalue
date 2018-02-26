<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'pizpalue',
    'Configuration/PageTS/CE.txt',
    'Pizpalue: Content element'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'pizpalue',
    'Configuration/PageTS/Grids.txt',
    'Pizpalue: Grid content elements'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'pizpalue',
    'Extensions/news/Configuration/PageTS/General.txt',
    'Pizpalue: Extension news'
);
