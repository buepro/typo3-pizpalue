<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

class VersionUtility
{
    public static function getExtensionVersion(string $extension): int
    {
        return (int)\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(ltrim(
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getExtensionVersion($extension),
            'vV'
        ))['version_int'];
    }
}
