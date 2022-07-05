<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * @todo Might be removed upon solving b13/container issue 272
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container')) {
        foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'] as &$config) {
            if (!isset(array_flip($config['gridPartialPaths'])['EXT:pizpalue/Resources/Private/Partials/Backend'])) {
                $config['gridPartialPaths'][] = 'EXT:pizpalue/Resources/Private/Partials/Backend';
            }
        }
        unset($config);
    }
})();
