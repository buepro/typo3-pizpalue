<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\UserFunction\HtmlParser;

use Psr\Http\Message\ServerRequestInterface;

class SetupConstantsSubstitutor
{
    public function process(string $content, array $conf, ServerRequestInterface $request): string
    {
        if (str_contains($content, '###')) {
            $typoScriptSetupArray = [];
            $frontendTypoScript = $request->getAttribute('frontend.typoscript');
            if ((bool)$frontendTypoScript && $frontendTypoScript->hasSetup()) {
                $typoScriptSetupArray = $frontendTypoScript->getSetupArray();
            }
            $tmpConstants = $typoScriptSetupArray['constants.'] ?? null;
            if (is_array($tmpConstants)) {
                foreach ($tmpConstants as $key => $val) {
                    if (is_string($val)) {
                        $content = str_replace('###' . $key . '###', $val, $content);
                    }
                }
            }
        }
        return $content;
    }
}
