<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure;

use Buepro\Pizpalue\Structure\TypoScript;
use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Structure\VariantsModifierStack;

class VariantsModifierStackTest extends TypoScriptBasedTest
{
    public function testStackManipulation(): void
    {
        VariantsModifierStack::resetStack();
        self::assertCount(0, VariantsModifierStack::getStack());
        VariantsModifierStack::pushVariantsModifier($variantsModifier1 = new VariantsModifier());
        self::assertSame($variantsModifier1, VariantsModifierStack::getVariantsModifier());
        VariantsModifierStack::pushVariantsModifier($variantsModifier2 = new VariantsModifier());
        self::assertSame($variantsModifier2, VariantsModifierStack::getVariantsModifier());
        self::assertCount(2, VariantsModifierStack::getStack());
        self::assertSame($variantsModifier2, VariantsModifierStack::popVariantsModifier());
        self::assertCount(1, VariantsModifierStack::getStack());
        self::assertSame($variantsModifier1, VariantsModifierStack::popVariantsModifier());
        self::assertCount(0, VariantsModifierStack::getStack());
        self::assertNull(VariantsModifierStack::popVariantsModifier());
    }

    private function getVariantsModifierForPageLayout75(): VariantsModifier
    {
        return (new VariantsModifier())->setModification(TypoScript::getVariants(
            'lib.contentElement.settings.responsiveimages.backendlayout.subnavigation_left.0'
        ) ?? []);
    }

    private function getVariantsModifierForColumns50(): VariantsModifier
    {
        return (new VariantsModifier())
            ->setMargins(-40)
            ->setMultiplier(['default' => 0.5, 'xlarge' => 0.5, 'large' => 0.5])
            ->setCorrections(40);
    }

    private function getVariantsForModalDialog(): VariantsModifier
    {
        return (new VariantsModifier())->setVariants(TypoScript::getVariants(
            'lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.md'
        ) ?? []);
    }

    private function getVariantsVector(array $variants, string $property): array
    {
        $result = [];
        foreach ($variants as $breakpoint => $properties) {
            if (isset($properties[$property])) {
                $result[$breakpoint] = $properties[$property];
            }
        }
        return $result;
    }

    public function testGetVariantsForPage75(): void
    {
        $this->resetSingletonInstances = true;
        VariantsModifierStack::resetStack();
        VariantsModifierStack::pushVariantsModifier($this->getVariantsModifierForPageLayout75());
        $variants = TypoScript::getVariants() ?? [];
        $expected = [];
        foreach ($variants as $breakpoint => $value) {
            $expected[$breakpoint] = ((float)$value['width'] + 40) * 0.75 - 40;
        }
        foreach (['medium', 'small', 'extrasmall'] as $breakpoint) {
            $expected[$breakpoint] = $variants[$breakpoint]['width'];
        }
        $actual = $this->getVariantsVector(VariantsModifierStack::getVariants(), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }

    public function testGetVariantsForColumn50(): void
    {
        $this->resetSingletonInstances = true;
        VariantsModifierStack::resetStack();
        VariantsModifierStack::pushVariantsModifier($this->getVariantsModifierForPageLayout75());
        VariantsModifierStack::pushVariantsModifier($this->getVariantsModifierForColumns50());
        $variants = TypoScript::getVariants() ?? [];
        $expected = [];
        foreach ($variants as $breakpoint => $value) {
            $expected[$breakpoint] = ((((float)$value['width'] + 40) * 0.75 - 40) + 40) * 0.5 - 40;
        }
        foreach (['medium', 'small', 'extrasmall'] as $breakpoint) {
            $expected[$breakpoint] = $variants[$breakpoint]['width'];
        }
        $actual = $this->getVariantsVector(VariantsModifierStack::getVariants(), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }

    public function testGetVariantsForColumn50UsingFullPageWidth(): void
    {
        $this->resetSingletonInstances = true;
        VariantsModifierStack::resetStack();
        VariantsModifierStack::pushVariantsModifier($this->getVariantsModifierForColumns50());
        $variants = TypoScript::getVariants('pageVariants') ?? [];
        $expected = [];
        foreach ($variants as $breakpoint => $value) {
            $expected[$breakpoint] = ((float)$value['width'] + 40) * 0.5 - 40;
        }
        foreach (['medium', 'small', 'extrasmall'] as $breakpoint) {
            $expected[$breakpoint] = $variants[$breakpoint]['width'];
        }
        $actual = $this->getVariantsVector(VariantsModifierStack::getVariants('pageVariants'), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }

    public function testGetVariantsForModalDialog(): void
    {
        $this->resetSingletonInstances = true;
        VariantsModifierStack::resetStack();
        VariantsModifierStack::pushVariantsModifier($this->getVariantsModifierForColumns50());
        VariantsModifierStack::pushVariantsModifier($this->getVariantsForModalDialog());
        $expected = $this->getVariantsVector(TypoScript::getVariants(
            'lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.md'
        ) ?? [], 'width');
        $actual = $this->getVariantsVector(VariantsModifierStack::getVariants(), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }
}
