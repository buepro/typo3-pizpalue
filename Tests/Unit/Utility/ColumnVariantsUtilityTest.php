<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Utility;

use Buepro\Pizpalue\Utility\ColumnVariantsUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColumnVariantsUtilityTest extends UnitTestCase
{
    public const BREAKPOINTS = ['extrasmall', 'small', 'medium', 'large', 'xlarge', 'default'];

    public function testGetMultiplierDataProvider(): array
    {
        return [
            'col, no row' => ['col', '', 0, [], array_fill_keys(self::BREAKPOINTS, 1.0)],
            'col, 1 row' => ['col', '', 1, [], array_fill_keys(self::BREAKPOINTS, 1.0)],
            'col, 2 rows' => ['col', '', 2, [], array_fill_keys(self::BREAKPOINTS, 0.5)],
            'col, 3 rows' => ['col', '', 3, [], array_fill_keys(self::BREAKPOINTS, 1/3)],
            'col-1, no row' => ['col-1', '', 0, [], array_fill_keys(self::BREAKPOINTS, 1/12)],
            'col-1, 2 rows' => ['col-1', '', 2, [], array_fill_keys(self::BREAKPOINTS, 1/12)],
            'col-4, no row' => ['col-4', '', 0, [], array_fill_keys(self::BREAKPOINTS, 1/3)],
            'col-sm-4, no row' => [
                'col-sm-4', '', 0, [],
                array_merge(array_fill_keys(self::BREAKPOINTS, 1/3), [
                    'extrasmall' => 1.0
                ]),
            ],
            'col-md-4, no row' => [
                'col-md-4', '', 0, [],
                array_merge(array_fill_keys(self::BREAKPOINTS, 1/3), [
                    'extrasmall' => 1.0,
                    'small' => 1.0,
                ]),
            ],
            'col-lg-4, no row' => [
                'col-lg-4', '', 0, [],
                array_merge(array_fill_keys(self::BREAKPOINTS, 1/3), [
                    'extrasmall' => 1.0,
                    'small' => 1.0,
                    'medium' => 1.0,
                ]),
            ],
            'col-xl-4, no row' => [
                'col-xl-4', '', 0, [],
                array_merge(
                    array_fill_keys(self::BREAKPOINTS, 1.0),
                    [
                    'xlarge' => 1/3,
                    'default' => 1/3,
                ]
                ),
            ],
            'col-xxl-4, no row' => [
                'col-xxl-4', '', 0, [],
                array_merge(
                    array_fill_keys(self::BREAKPOINTS, 1.0),
                    [
                    'default' => 1/3,
                ]
                ),
            ],
            'col-md-6 col-lg-4 col-xxl-3' => [
                'col-md-6 col-lg-4 col-xxl-3', '', 0, [],
                [
                    'extrasmall' => 1.0,
                    'small' => 1.0,
                    'medium' => 0.5,
                    'large' => 1/3,
                    'xlarge' => 1/3,
                    'default' => 1/4,
                ]
            ],
            'col-md-6 col-lg-4 col-xxl-3 with base multiplier' => [
                'col-md-6 col-lg-4 col-xxl-3', '', 0,
                [
                    'extrasmall' => 0.8,
                    'small' => 0.7,
                    'medium' => 0.6,
                    'large' => 0.5,
                    'xlarge' => 0.4,
                    'default' => 0.3,
                ],
                [
                    'extrasmall' => 0.8,
                    'small' => 0.7,
                    'medium' => 0.6 * 0.5,
                    'large' => 0.5 * 1/3,
                    'xlarge' => 0.4 * 1/3,
                    'default' => 0.3 * 1/4,
                ]
            ],
            'rowClass defines column' => [
                'col', 'row-cols-4', 1, [],
                array_fill_keys(self::BREAKPOINTS, 1/4),
            ],
            'rowClass overwrites breakpoint' => [
                'col', 'row-cols-xl-4', 1, [],
                array_merge(array_fill_keys(self::BREAKPOINTS, 1.0), [
                    'xlarge' => 1/4,
                    'default' => 1/4,
                ]),
            ],
            'rowClass overwrites count' => [
                'col', 'row-cols-3', 2, [],
                array_fill_keys(self::BREAKPOINTS, 1/3),
            ],
        ];
    }

    /**
     * @dataProvider testGetMultiplierDataProvider
     */
    public function testGetMultiplier(string $class, string $rowClass, int $count, array $baseMultiplier, array $expected): void
    {
        if ($rowClass === '' && $count === 0 && $baseMultiplier === []) {
            self::assertSame($expected, ColumnVariantsUtility::getMultiplier($class));
        } else {
            self::assertSame($expected, ColumnVariantsUtility::getMultiplier($class, $rowClass, $count, $baseMultiplier));
        }
    }
}
