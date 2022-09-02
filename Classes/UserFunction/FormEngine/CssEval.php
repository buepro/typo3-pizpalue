<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\UserFunction\FormEngine;

class CssEval
{
    public function evaluateFieldValue(?string $value, string $is_in, bool &$set): string
    {
        return ($value === null || $value === '') ? '' : strip_tags($value);
    }
}
