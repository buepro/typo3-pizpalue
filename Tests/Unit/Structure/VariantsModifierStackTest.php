<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure;

use Buepro\Pizpalue\Structure\Service\TypoScriptService;
use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class VariantsModifierStackTest extends TypoScriptBasedTestCase
{
    /** @var VariantsModifierStack $variantsModifierStack */
    protected $variantsModifierStack;

    public function setUp(): void
    {
        parent::setUp();
        $this->variantsModifierStack = GeneralUtility::makeInstance(VariantsModifierStack::class);
    }

    public function testStackManipulation(): void
    {
        $this->resetSingletonInstances = true;
        $this->variantsModifierStack->resetStack();
        self::assertCount(0, $this->variantsModifierStack->getStack());
        $this->variantsModifierStack->pushVariantsModifier($variantsModifier1 = new VariantsModifier());
        self::assertSame($variantsModifier1, $this->variantsModifierStack->getVariantsModifier());
        $this->variantsModifierStack->pushVariantsModifier($variantsModifier2 = new VariantsModifier());
        self::assertSame($variantsModifier2, $this->variantsModifierStack->getVariantsModifier());
        self::assertCount(2, $this->variantsModifierStack->getStack());
        self::assertSame($variantsModifier2, $this->variantsModifierStack->popVariantsModifier());
        self::assertCount(1, $this->variantsModifierStack->getStack());
        self::assertSame($variantsModifier1, $this->variantsModifierStack->popVariantsModifier());
        self::assertCount(0, $this->variantsModifierStack->getStack());
        self::assertNull($this->variantsModifierStack->popVariantsModifier());
    }

    private function getVariantsModifierForPageLayout75(): VariantsModifier
    {
        return (new VariantsModifier())->setModification((new TypoScriptService())->getVariants(
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
        return (new VariantsModifier())->setVariants((new TypoScriptService())->getVariants(
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
        $this->variantsModifierStack->resetStack();
        $this->variantsModifierStack->pushVariantsModifier($this->getVariantsModifierForPageLayout75());
        $variants = (new TypoScriptService())->getVariants() ?? [];
        $expected = [];
        foreach ($variants as $breakpoint => $value) {
            $expected[$breakpoint] = ((float)$value['width'] + 40) * 0.75 - 40;
        }
        foreach (['medium', 'small', 'extrasmall'] as $breakpoint) {
            $expected[$breakpoint] = $variants[$breakpoint]['width'];
        }
        $actual = $this->getVariantsVector($this->variantsModifierStack->getVariants(), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }

    public function testGetVariantsForColumn50(): void
    {
        $this->resetSingletonInstances = true;
        $this->variantsModifierStack->resetStack();
        $this->variantsModifierStack->pushVariantsModifier($this->getVariantsModifierForPageLayout75());
        $this->variantsModifierStack->pushVariantsModifier($this->getVariantsModifierForColumns50());
        $variants = (new TypoScriptService())->getVariants() ?? [];
        $expected = [];
        foreach ($variants as $breakpoint => $value) {
            $expected[$breakpoint] = ((((float)$value['width'] + 40) * 0.75 - 40) + 40) * 0.5 - 40;
        }
        foreach (['medium', 'small', 'extrasmall'] as $breakpoint) {
            $expected[$breakpoint] = $variants[$breakpoint]['width'];
        }
        $actual = $this->getVariantsVector($this->variantsModifierStack->getVariants(), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }

    public function testGetVariantsForColumn50UsingFullPageWidth(): void
    {
        $this->resetSingletonInstances = true;
        $this->variantsModifierStack->resetStack();
        $this->variantsModifierStack->pushVariantsModifier($this->getVariantsModifierForColumns50());
        $variants = (new TypoScriptService())->getVariants('pageVariants') ?? [];
        $expected = [];
        foreach ($variants as $breakpoint => $value) {
            $expected[$breakpoint] = ((float)$value['width'] + 40) * 0.5 - 40;
        }
        foreach (['medium', 'small', 'extrasmall'] as $breakpoint) {
            $expected[$breakpoint] = $variants[$breakpoint]['width'];
        }
        $actual = $this->getVariantsVector($this->variantsModifierStack->getVariants('pageVariants'), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }

    public function testGetVariantsForModalDialog(): void
    {
        $this->resetSingletonInstances = true;
        $this->variantsModifierStack->resetStack();
        $this->variantsModifierStack->pushVariantsModifier($this->getVariantsModifierForColumns50());
        $this->variantsModifierStack->pushVariantsModifier($this->getVariantsForModalDialog());
        $expected = $this->getVariantsVector((new TypoScriptService())->getVariants(
            'lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.md'
        ) ?? [], 'width');
        $actual = $this->getVariantsVector($this->variantsModifierStack->getVariants(), 'width');
        foreach ($expected as $breakpoint => $value) {
            // We allow 1px rounding error
            self::assertLessThan(1, abs($actual[$breakpoint] - $value));
        }
    }
}
