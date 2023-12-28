<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Utility;

use Buepro\Pizpalue\Utility\VectorUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class VectorUtilityTest extends UnitTestCase
{
    public static function negateGetsNegativeVectorDataProvider(): array
    {
        return [
            'array with numeric keys' => [[1, -2, 3], [-(float)1, (float)2, -(float)3]],
            'array with string keys' => [
                ['a' => 1, 'b' => -2, 'c' => 3],
                ['a' => -(float)1, 'b' => (float)2, 'c' => -(float)3],
            ],
        ];
    }

    /**
     * @dataProvider negateGetsNegativeVectorDataProvider
     * @test
     */
    public function negateGetsNegativeVector(array $vector, array $expected): void
    {
        self::assertSame($expected, VectorUtility::negate($vector));
    }

    public static function addVectorReturnsSumDataProvider(): array
    {
        return [
            'arrays with numeric keys 1' => [
                [-10, 2, 3], [1, -20, 3], [-(float)9, -(float)18, (float)6],
            ],
            'arrays with numeric keys 2' => [
                [-10, 2, 3], [1, -20, 3, 4], [-(float)9, -(float)18, (float)6, (float)4],
            ],
            'arrays with numeric keys 3' => [
                [-10, 2, 3, 4], [1, -20, 3], [-(float)9, -(float)18, (float)6, (float)4],
            ],
            'array with string keys 1' => [
                ['a' => -10, 'b' => 2, 'c' => 3],
                ['a' => 1, 'b' => -20, 'c' => 3],
                ['a' => -(float)9, 'b' => -(float)18, 'c' => (float)6],
            ],
            'array with string keys 2' => [
                ['a' => -10, 'b' => 2, 'c' => 3],
                ['a' => 1, 'b' => -20, 'c' => 3, 'd' => 4],
                ['a' => -(float)9, 'b' => -(float)18, 'c' => (float)6, 'd' => (float)4],
            ],
            'array with string keys 3' => [
                ['a' => -10, 'b' => 2, 'c' => 3, 'd' => 4],
                ['a' => 1, 'b' => -20, 'c' => 3],
                ['a' => -(float)9, 'b' => -(float)18, 'c' => (float)6, 'd' => (float)4],
            ],
        ];
    }

    /**
     * @dataProvider addVectorReturnsSumDataProvider
     * @test
     */
    public function addVectorReturnsSum(array $a, array $b, array $expected): void
    {
        self::assertSame($expected, VectorUtility::addVector($a, $b));
    }
}
