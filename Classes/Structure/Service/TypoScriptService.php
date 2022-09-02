<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure\Service;

use TYPO3\CMS\Core\TypoScript\TypoScriptService as CoreTypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptService
{
    public function getVariants(string $tsPath = 'variants'): ?array
    {
        $result = null;
        /** @var CoreTypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(CoreTypoScriptService::class);
        $setup = $typoScriptService->convertTypoScriptArrayToPlainArray($GLOBALS['TSFE']->tmpl->setup);
        $parts = GeneralUtility::trimExplode('.', $tsPath, true);
        if (count($parts) === 1 && is_array($setup['lib']['contentElement']['settings']['responsiveimages'][$parts[0]])) {
            $result = $setup['lib']['contentElement']['settings']['responsiveimages'][$parts[0]];
        }
        if ($result === null && count($parts) > 0) {
            foreach ($parts as $part) {
                if (is_array($setup)) {
                    $setup = $setup[$part] ?? null;
                }
            }
            if (is_array($setup)) {
                $result = $setup;
            }
        }
        return is_array($result) && $result !== [] ? $result : null;
    }
}
