<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Utility;

use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class StructureVariantsUtilityTest extends UnitTestCase
{
    /**
     * @var array $initialVariants
     */
    protected $initialVariants = [
        'default' => [
            'breakpoint' => 1400,
            'width' => 1280,
            'sizes' => [
                '1x' => [
                    'multiplier' => 1
                ],
            ],
        ],
        'xlarge' => [
            'breakpoint' => 1200,
            'width' => 1100,
            'sizes' => [
                '1x' => [
                    'multiplier' => 1
                ],
            ],
        ],
        'large' => [
            'breakpoint' => 992,
            'width' => 920,
            'sizes' => [
                '1x' => [
                    'multiplier' => 1
                ],
            ],
        ],
        'medium' => [
            'breakpoint' => 768,
            'width' => 680,
            'sizes' => [
                '1x' => [
                    'multiplier' => 1
                ],
            ],
        ],
        'small' => [
            'breakpoint' => 576,
            'width' => 500,
            'sizes' => [
                '1x' => [
                    'multiplier' => 1
                ],
            ],
        ],
        'extrasmall' => [
            'width' => 374,
            'sizes' => [
                '1x' => [
                    'multiplier' => 1
                ],
            ],
        ]
    ];

    public function getVectorPropertyReturnsVectorDataProvider(): array
    {
        $expected = array_fill_keys(StructureVariantsUtility::BREAKPOINTS, 5.5);
        return [
            'skalar float' => [5.5, $expected],
            'skalar int' => [5, array_fill_keys(StructureVariantsUtility::BREAKPOINTS, 5)],
            'partial array' => [['small' => 6.6], ['small' => 6.6]],
            'complete array' => [$expected, $expected],
        ];
    }

    /**
     * @dataProvider getVectorPropertyReturnsVectorDataProvider
     * @test
     * @param float|array $property
     */
    public function getVectorPropertyReturnsVector($property, array $expected): void
    {
        $this::assertSame($expected, StructureVariantsUtility::getVectorProperty($property));
    }

    private function removeMarginsFromVariants(array $variants, array $margins): array
    {
        $result = $variants;
        foreach ($variants as $breakpoint => $value) {
            $result[$breakpoint]['width'] -= $margins[$breakpoint];
        }
        return $result;
    }

    public function marginIsRespectedDataProvider(): array
    {
        $partialMargins = ['medium' => 20];
        $partialExpected = $this->initialVariants;
        $partialExpected['medium']['width'] -= 20;
        $partialMixedMargins = $partialMargins;
        $partialMixedMargins['small'] = -20;
        $partialMixedExpected = $partialExpected;
        $partialMixedExpected['small']['width'] -= -20;
        return [
            'null' => [$this->initialVariants, null, $this->initialVariants],
            'positive margins' => [
                $this->initialVariants,
                $margins = array_fill_keys(StructureVariantsUtility::BREAKPOINTS, 5),
                $this->removeMarginsFromVariants($this->initialVariants, $margins)
            ],
            'negative margins' => [
                $this->initialVariants,
                $margins = array_fill_keys(StructureVariantsUtility::BREAKPOINTS, -5),
                $this->removeMarginsFromVariants($this->initialVariants, $margins)
            ],
            'partially defined margins' => [$this->initialVariants, $partialMargins, $partialExpected],
            'partially defined mixed margins' => [$this->initialVariants, $partialMixedMargins, $partialMixedExpected],
        ];
    }

    /**
     * @dataProvider marginIsRespectedDataProvider
     * @test
     */
    public function marginIsRespected(array $variants, ?array $margins, array $expected): void
    {
        $this::assertSame($expected, StructureVariantsUtility::getStructureVariants($variants, $margins));
    }
}
