<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container_elements')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:pizpalue/Extensions/container_elements/Configuration/FlexForm/Columns2.xml',
            'ce_columns2'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:pizpalue/Extensions/container_elements/Configuration/FlexForm/Columns3.xml',
            'ce_columns3'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:pizpalue/Extensions/container_elements/Configuration/FlexForm/Columns4.xml',
            'ce_columns4'
        );
    }
})();
