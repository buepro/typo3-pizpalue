<?php
declare(strict_types=1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Used to calculate the structure multiplier.
 */
class StructureUtility
{
    private static $defaultMultiplier = [
        'extrasmall' => 1,
        'small' => 1,
        'medium' => 1,
        'large' => 1,
        'default' => 1
    ];

    private static $breakpointMap = [
        'extrasmall' => '',
        'small' => 'sm',
        'medium' => 'md',
        'large' => 'lg',
        'default' => 'xl'
    ];

    /**
     * Calculates the factor for a breakpoint based on the css items used to define the column and the column count
     * from the row.
     *
     * @param array $items CSS items defining the column (e.g. ['col', 'col-md', 'col-lg-4'])
     * @param int $count
     * @param string $breakpoint
     * @return bool|float|int
     */
    private static function getColumnFactorForBreakpoint(array $items, int $count, string $breakpoint)
    {
        $factor = false;
        foreach ($items as $item) {
            $parts = GeneralUtility::trimExplode('-', $item, true);
            if ($parts[0] !== 'col') {
                continue;
            }
            // CSS definition `col`
            if (count($parts) === 1 && $breakpoint === 'extrasmall') {
                $factor = 1 / $count;
            }
            // CSS definition `col-3`
            if (count($parts) === 2 && (int) $parts[1] > 0 && $breakpoint === 'extrasmall') {
                $factor = ((int) $parts[1]) / 12;
            }
            // CSS definition `col-md`
            if (count($parts) === 2 && $parts[1] === self::$breakpointMap[$breakpoint]) {
                $factor = 1 / $count;
            }
            // CSS definition `col-md-3`
            if (count($parts) === 3 && $parts[1] === self::$breakpointMap[$breakpoint]) {
                $factor = ((int) $parts[2]) / 12;
            }
        }
        return $factor;
    }

    /**
     * Calculates a new multiplier based on the column css, the column count and a base multiplier
     *
     * @param string $class CSS classes used to define the column
     * @param int $count Columns count in row
     * @param array $baseMultiplier
     * @return array
     */
    public static function getMultiplierForColumn(string $class = '', int $count = 1, array $baseMultiplier = []): array
    {
        // Set initial multiplier
        $multiplier = array_merge(self::$defaultMultiplier, $baseMultiplier);
        $multiplier = array_intersect_key($multiplier, self::$defaultMultiplier);
        // Calculate new multipliers
        $items = GeneralUtility::trimExplode(' ', $class, true);
        $previousFactor = 1.0;
        foreach ($multiplier as $breakpoint => $value) {
            $factor = self::getColumnFactorForBreakpoint($items, $count, $breakpoint);
            if (false === $factor) {
                // No column definition was present hence column width remains as for previous break point
                $multiplier[$breakpoint] = $previousFactor * $value;
            } else {
                $multiplier[$breakpoint] = $factor * $value;
                $previousFactor = $factor;
            }
        }
        return $multiplier;
    }
}
