<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * @link https://github.com/b13/container/issues/272
     */
    if (ExtensionManagementUtility::isLoaded('container')) {
        $backendPartialsPath = 'EXT:pizpalue/Sysext/backend/Resources/Private/Partials';
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() === 11) {
            $backendPartialsPath = 'EXT:pizpalue/Sysext/backend/Resources/Deprecated/Private/Partials';
        }
        foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'] as &$config) {
            if (!isset(array_flip($config['gridPartialPaths'])[$backendPartialsPath])) {
                $config['gridPartialPaths'][] = $backendPartialsPath;
            }
        }
        unset($config);
    }
})();
