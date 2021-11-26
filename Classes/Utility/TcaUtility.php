<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

class TcaUtility
{
    public static function assignAllowedAspectRatiosToCropVariants(
        array $aspectRatios,
        array &$cropVariants,
        array $breakpoints = ['default', 'xlarge', 'large', 'medium', 'small', 'extrasmall']
    ): void {
        foreach ($breakpoints as $breakpoint) {
            $cropVariants[$breakpoint]['allowedAspectRatios'] = $aspectRatios;
        }
    }

    public static function setAllowedAspectRatiosForField(
        array $aspectRatios,
        string $table,
        string $field,
        array $breakpoints = ['default', 'xlarge', 'large', 'medium', 'small', 'extrasmall']
    ): void {
        if (
            !isset($GLOBALS['TCA'][$table]['columns']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'])
        ) {
            $GLOBALS['TCA'][$table]['columns']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [];
        }
        self::assignAllowedAspectRatiosToCropVariants(
            $aspectRatios,
            $GLOBALS['TCA'][$table]['columns']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'],
            $breakpoints
        );
    }

    public static function setAllowedAspectRatiosForCType(
        array $aspectRatios,
        string $table,
        string $cType,
        string $field,
        array $breakpoints = ['default', 'xlarge', 'large', 'medium', 'small', 'extrasmall']
    ): void {
        if (
            !isset($GLOBALS['TCA'][$table]['types'][$cType]['columnsOverrides']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'])
        ) {
            $GLOBALS['TCA'][$table]['types'][$cType]['columnsOverrides']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [];
        }
        self::assignAllowedAspectRatiosToCropVariants(
            $aspectRatios,
            $GLOBALS['TCA'][$table]['types'][$cType]['columnsOverrides']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'],
            $breakpoints
        );
    }
}
