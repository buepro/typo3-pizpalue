<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

class VectorUtility
{
    public static function negate(array $vector): array
    {
        foreach ($vector as $key => $value) {
            $vector[$key] = -(float)$value;
        }
        return $vector;
    }

    public static function addVector(array $a, array $b): array
    {
        $result = [];
        foreach ($a as $key => $value) {
            $result[$key] = (float)$value;
            if (isset($b[$key])) {
                $result[$key] += (float)$b[$key];
                unset($b[$key]);
            }
        }
        foreach ($b as $key => $value) {
            $result[$key] = (float)$value;
        }
        return $result;
    }
}
