<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Used to calculate variants properties for a column.
 */
class ColumnVariantsUtility
{
    /**
     * @var array
     */
    private static $defaultMultiplier = [
        'extrasmall' => 1.0,
        'small' => 1.0,
        'medium' => 1.0,
        'large' => 1.0,
        'xlarge' => 1.0,
        'default' => 1.0,
    ];

    /**
     * @var string[]
     */
    private static $breakpointMap = [
        'extrasmall' => 'xs',
        'small' => 'sm',
        'medium' => 'md',
        'large' => 'lg',
        'xlarge' => 'xl',
        'default' => 'xxl',
    ];

    /**
     * @var int[]
     */
    private static $breakpointWeight = [
        'xs' => 100,
        'sm' => 200,
        'md' => 300,
        'lg' => 400,
        'xl' => 500,
        'xxl' => 600,
    ];

    /**
     * Filters relevant classes and orders them according the breakpoint weight.
     * 'col col-lg-6 col-md-4' becomes ['col', 'col-md-4', 'col-lg-6']
     */
    public static function getOrderedColumnClasses(string $classes): array
    {
        $unordered = array_filter(
            GeneralUtility::trimExplode(' ', $classes, true),
            static function ($class): bool {
                return strpos($class, 'col') === 0;
            }
        );
        $weighted = [];
        foreach ($unordered as $class) {
            $parts = explode('-', $class);
            $weight = 1;
            if (isset($parts[1])) {
                if (MathUtility::canBeInterpretedAsInteger($parts[1])) {
                    $weight += (int)$parts[1] * 10;
                } else {
                    $weight += self::$breakpointWeight[$parts[1]] ?? 0;
                }
            }
            if (isset($parts[2])) {
                $weight += 1000;
            }
            $weighted[$class] = $weight;
        }
        asort($weighted);
        return array_values(array_flip($weighted));
    }

    /**
     * Calculates the factor for a breakpoint based on the css classes used to define the column and the column count
     * from the row.
     */
    private static function getColumnFactorForBreakpoint(string $classes, int $count, string $breakpoint): ?float
    {
        $result = null;
        // Last element for breakpoint defines the factor.
        $columnClasses = self::getOrderedColumnClasses($classes);
        foreach ($columnClasses as $columnClass) {
            $parts = GeneralUtility::trimExplode('-', $columnClass, true);
            if ($parts[0] !== 'col') {
                continue;
            }
            // CSS definition col
            if (count($parts) === 1) {
                $result = 1 / $count;
                continue;
            }
            // CSS definition col-3 or col-md
            if (count($parts) === 2) {
                if (MathUtility::canBeInterpretedAsInteger($parts[1])) {
                    $result = ((float) $parts[1]) / 12;
                    continue;
                }
                if (!isset(self::$breakpointWeight[$parts[1]])) {
                    continue;
                }
                if (self::$breakpointWeight[self::$breakpointMap[$breakpoint]] >= self::$breakpointWeight[$parts[1]]) {
                    $result = 1 / $count;
                    continue;
                }
            }
            // CSS definition col-md-3
            if (
                count($parts) === 3 &&
                self::$breakpointWeight[self::$breakpointMap[$breakpoint]] >= self::$breakpointWeight[$parts[1]]
            ) {
                $result = ((float) $parts[2]) / 12;
            }
        }
        return $result;
    }

    /**
     * The returned array associates each breakpoint with the columns count:
     *  [
     *      ...
     *      'medium' => 3,
     *      ...
     *  ]
     */
    public static function getColumnCountsFromRowClasses(string $rowClasses, int $defaultCount = 1): array
    {
        $bootstrapBreakpointMap = array_flip(self::$breakpointMap);
        $result = array_fill_keys($bootstrapBreakpointMap, $defaultCount);
        if (preg_match_all('/row-cols-(\w+)-?(\d+)?/', $rowClasses, $matches) !== false) {
            $definedRows = [];
            foreach ($matches[1] as $key => $columnCountOrBootstrapBreakpoint) {
                if (MathUtility::canBeInterpretedAsInteger($columnCountOrBootstrapBreakpoint)) {
                    $result = array_fill_keys($bootstrapBreakpointMap, (int)$columnCountOrBootstrapBreakpoint);
                } else {
                    $definedRows[$columnCountOrBootstrapBreakpoint] = (int)$matches[2][$key];
                }
            }
            // Assign counts to breakpoints. Start from extrasmall breakpoint and pass count to subsequent
            // breakpoints until a count is defined again.
            $previousCount = $result['extrasmall'];
            foreach ($result as $breakpoint => &$count) {
                if (isset($definedRows[self::$breakpointMap[$breakpoint]])) {
                    $previousCount = $count = $definedRows[self::$breakpointMap[$breakpoint]];
                } else {
                    $count = $previousCount;
                }
            }
            unset($count);
        }
        return $result;
    }

    /**
     * Calculates a new multiplier based on the column css classes, the column count and a base multiplier.
     *
     * @param string $class CSS classes used to define the column
     * @param string $rowClass CSS classes assigned to wrapping row. Overwrites $row.
     * @param int $count Column count in row
     * @param array $baseMultiplier
     * @return array
     */
    public static function getMultiplier(
        string $class = '',
        string $rowClass = 'row-cols-1',
        int $count = 1,
        array $baseMultiplier = []
    ): array {
        // Set initial multiplier
        $multiplier = array_merge(self::$defaultMultiplier, $baseMultiplier);
        $multiplier = array_intersect_key($multiplier, self::$defaultMultiplier);
        // Calculate new multipliers
        $columnCountsFromRowClasses = self::getColumnCountsFromRowClasses($rowClass, $count);
        $previousFactor = 1.0;
        foreach ($multiplier as $breakpoint => $value) {
            $factor = self::getColumnFactorForBreakpoint($class, $columnCountsFromRowClasses[$breakpoint], $breakpoint);
            if ($factor !== null) {
                $multiplier[$breakpoint] = $factor * $value;
                $previousFactor = $factor;
            } else {
                // No column definition was present hence column width remains as for previous break point
                $multiplier[$breakpoint] = $previousFactor * $value;
            }
        }
        return $multiplier;
    }
}
