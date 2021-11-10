<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure;

use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class VariantsModifierTest extends UnitTestCase
{
    public function testGetterFromMarginsGuttersMultiplierAndCorrections(): void
    {
        $breakpoints = array_keys(StructureVariantsUtility::getStructureVariants());

        // Provide a scalar
        $variantsModifier = (new VariantsModifier())
            ->setMargins(-20)
            ->setGutters(10)
            ->setMultiplier(0.5)
            ->setCorrections(5);
        self::assertSame(array_fill_keys($breakpoints, -20.0), $variantsModifier->getMargins());
        self::assertSame(array_fill_keys($breakpoints, 10.0), $variantsModifier->getGutters());
        self::assertSame(array_fill_keys($breakpoints, 0.5), $variantsModifier->getMultiplier());
        self::assertSame(array_fill_keys($breakpoints, 5.0), $variantsModifier->getCorrections());

        // Provide a complete array
        $expected = array_fill_keys($breakpoints, 20.0);
        self::assertSame($expected, $variantsModifier->setMargins($expected)->getMargins());
        self::assertSame($expected, $variantsModifier->setGutters($expected)->getGutters());
        self::assertSame($expected, $variantsModifier->setMultiplier($expected)->getMultiplier());
        self::assertSame($expected, $variantsModifier->setCorrections($expected)->getCorrections());

        // Provide an uncomplete array
        unset($expected['medium']);
        self::assertSame($expected, $variantsModifier->setMargins($expected)->getMargins());
        self::assertSame($expected, $variantsModifier->setGutters($expected)->getGutters());
        self::assertSame($expected, $variantsModifier->setMultiplier($expected)->getMultiplier());
        self::assertSame($expected, $variantsModifier->setCorrections($expected)->getCorrections());
    }

    public function testSetModification(): void
    {
        $breakpoints = array_keys(StructureVariantsUtility::getStructureVariants());

        $variantsModifier = new VariantsModifier();
        $variantsModifier->setModification([
            'margins' => -20,
            'gutters' => 5,
            'multiplier' => 0.5,
            'corrections' => 30,
        ]);
        self::assertSame(array_fill_keys($breakpoints, -20.0), $variantsModifier->getMargins());
        self::assertSame(array_fill_keys($breakpoints, 5.0), $variantsModifier->getGutters());
        self::assertSame(array_fill_keys($breakpoints, 0.5), $variantsModifier->getMultiplier());
        self::assertSame(array_fill_keys($breakpoints, 30.0), $variantsModifier->getCorrections());
    }
}
