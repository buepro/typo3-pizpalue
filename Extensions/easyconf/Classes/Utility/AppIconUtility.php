<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class AppIconUtility
{
    public static function getHtmlWithLineBreaks(string $htmlWithoutLinebreaks): string
    {
        $tokenedHtml = str_replace('>', '>###,###', $htmlWithoutLinebreaks);
        $html = GeneralUtility::trimExplode('###,###', $tokenedHtml, true);
        return implode(CRLF, $html);
    }

    public static function getHtmlWithoutLineBreaks(string $htmlWithLineBreaks): string
    {
        return trim(str_replace([CR, LF], '', $htmlWithLineBreaks));
    }
}
