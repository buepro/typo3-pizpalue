<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die('Access denied.');

if (!ExtensionManagementUtility::isLoaded('easyconf')) {
    $GLOBALS['TCA']['tx_easyconf_configuration']['ctrl'] = [
        'title' => '',
        'readOnly'  => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => '',
    ];
    return;
}

(function () {
    $GLOBALS['TCA']['tx_easyconf_configuration']['columns'] = [];
    $tcaOverridesPathForPackage = ExtensionManagementUtility::extPath('pizpalue')
        . 'Extensions/easyconf/Configuration/TCA/Overrides';
    if (!is_dir($tcaOverridesPathForPackage)) {
        return;
    }
    $files = scandir($tcaOverridesPathForPackage);
    if ($files === false) {
        return;
    }
    foreach ($files as $file) {
        if (is_file($tcaOverridesPathForPackage . '/' . $file)
            && ($file !== '.')
            && ($file !== '..')
            && (substr($file, -4, 4) === '.php')
        ) {
            require $tcaOverridesPathForPackage . '/' . $file;
        }
    }
})();
