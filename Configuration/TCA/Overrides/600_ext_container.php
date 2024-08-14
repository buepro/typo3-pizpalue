<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * @link https://github.com/b13/container/issues/272
     */
    if (ExtensionManagementUtility::isLoaded('container')) {
        $backendPartialsPath = 'EXT:pizpalue/Sysext/backend/Resources/Private/Partials';
        foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'] as &$config) {
            if (!isset(array_flip($config['gridPartialPaths'])[$backendPartialsPath])) {
                $config['gridPartialPaths'][] = $backendPartialsPath;
            }
        }
        unset($config);
    }
})();
