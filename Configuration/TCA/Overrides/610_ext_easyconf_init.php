<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
    $GLOBALS['TCA']['tx_easyconf_configuration']['ctrl'] = [
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

$GLOBALS['TCA']['tx_easyconf_configuration']['columns'] = [];
