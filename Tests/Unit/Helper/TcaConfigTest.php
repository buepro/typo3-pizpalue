<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Helper;

use Buepro\Pizpalue\Helper\TcaConfig;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TcaConfigTest extends UnitTestCase
{
    private function getDefaultColorItems(): array
    {
        $result = [[TcaConfig::LL_PREFIX . 'normal', '']];
        foreach (TcaConfig::BOOTSTRAP_PACKAGE_COLORS as $color) {
            $result[] = [TcaConfig::LL_PREFIX . $color, $color];
        }
        return $result;
    }

    public function getColorItemsReturnsCorrectItemsDataProvider(): array
    {
        $defaultColorItems = $this->getDefaultColorItems();
        $colorItemsWithoutNormal = $defaultColorItems;
        unset($colorItemsWithoutNormal[0]);
        $colorItemsWithFormat = array_map(static function (array $item): array {
            return $item[1] === '' ? $item : [$item[0], sprintf('var(--bs-%s)', $item[1])];
        }, $defaultColorItems);
        return [
            'default parameters' => [true, '', $defaultColorItems],
            'without normal' => [false, '', array_values($colorItemsWithoutNormal)],
            'with format' => [true, 'var(--bs-%s)', $colorItemsWithFormat],
        ];
    }

    /**
     * @test
     * @dataProvider getColorItemsReturnsCorrectItemsDataProvider
     */
    public function getColorItemsReturnsCorrectItems(bool $includeNormal = false, string $format = '', array $expected = []): void
    {
        self::assertSame($expected, TcaConfig::getColorItems($includeNormal, $format));
    }
}
