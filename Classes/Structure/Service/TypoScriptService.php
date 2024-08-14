<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure\Service;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\TypoScript\TypoScriptService as CoreTypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptService
{
    public function getVariants(string $tsPath = 'variants'): ?array
    {
        $setupArray = null;
        /** @var ServerRequestInterface $serverRequest */
        $serverRequest = $GLOBALS['TYPO3_REQUEST'];
        if (
            class_exists(FrontendTypoScript::class) &&
            ($frontendTypoScript = $serverRequest->getAttribute('frontend.typoscript')) instanceof FrontendTypoScript) {
            $setupArray = $frontendTypoScript->getSetupArray();
        }
        if ($setupArray === null) {
            return null;
        }

        $result = null;
        /** @var CoreTypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(CoreTypoScriptService::class);
        $setup = $typoScriptService->convertTypoScriptArrayToPlainArray($setupArray);
        $parts = GeneralUtility::trimExplode('.', $tsPath, true);
        if (count($parts) === 1 && is_array($setup['lib']['contentElement']['settings']['responsiveimages'][$parts[0]] ?? false)) {
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
