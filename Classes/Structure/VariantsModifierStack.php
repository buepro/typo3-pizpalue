<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure;

use Buepro\Pizpalue\DataProcessing\StructureProcessor;
use Buepro\Pizpalue\Service\BackendlayoutService;
use Buepro\Pizpalue\Service\ContentElementService;
use Buepro\Pizpalue\Structure\Service\TypoScriptService;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class VariantsModifierStack
{
    /**
     * Maintains VariantsModifiers from elements modifying the available content space.
     *
     * @var VariantsModifier[]
     */
    private static $stack = [];

    /**
     * Data from the content element currently being rendered.
     *
     * @var array
     * @see StructureProcessor
     */
    private static $contentElementData = [];

    public static function getStack(): array
    {
        return self::$stack;
    }

    public static function resetStack(): void
    {
        self::$stack = [];
    }

    public static function setContentElementData(array $data): void
    {
        self::$contentElementData = array_intersect_key(
            $data,
            array_flip(['uid', 'pid', 'tx_pizpalue_image_variants', 'tx_pizpalue_background_image_variants'])
        );
    }

    public static function getContentElementData(): array
    {
        return self::$contentElementData;
    }

    /**
     * Get the last pushed variantsModifier. Doesn't change self::stack.
     *
     * @return VariantsModifier
     */
    public static function getVariantsModifier(): VariantsModifier
    {
        if (count(self::$stack) > 0) {
            $variantsModifier = array_pop(self::$stack);
            self::$stack[] = $variantsModifier;
        } else {
            $variantsModifier = new VariantsModifier();
        }
        return $variantsModifier;
    }

    public static function pushVariantsModifier(VariantsModifier $variantsModifier): void
    {
        self::$stack[] = $variantsModifier;
    }

    public static function popVariantsModifier(): ?VariantsModifier
    {
        if (count(self::$stack) > 0) {
            return array_pop(self::$stack);
        }
        return null;
    }

    /**
     * Calculates the resulting variants for an initial variants (obtained by the $specifier).
     *
     * ----------------------
     * Functional description
     * ----------------------
     *
     * --------------------------------------------------+
     * (5) [Content element with images]                 |
     * (4) [Structure element 3 column]                  |
     * (3) [Structure element 2 column]                   > stack
     * (2) [Structure element 1 column]                  |
     * (1) [Backendlayout column]                        |
     * --------------------------------------------------+
     * [Initial variants (depends on (5))]               |
     * --------------------------------------------------+
     *
     * Content space modifying elements (e.g. backendlayouts, structure elements) push a variantsModifier to the stack.
     * When it comes to calculate the resulting variants for a content element the variantsModifiers are applied to
     * an initial variants. The initial variants are mostly defined by the content element the variants are calculated
     * for. This is why the related fields from content element data are saved to self::contentElementData.
     *
     * When processing the stack a variantsModifier might contain a variants. This variants serves
     * as a new base for the subsequent variantsModifiers. A use case might be a modal dialog element where the button
     * to activate the modal dialog is rendered in a column. For the images in the modal dialog to be rendered
     * correctly a new variants base has to be available. In the following illustration the stack element (3) would
     * contain a variants hence the variantsModifiers (1) and (2) haven't an effect anymore.
     *
     * --------------------------------------------------+
     * (5) [Content element with images]                 |
     * (4) [Structure element column]                    |
     * (3) [Content element to render modal dialog]       > stack
     * (2) [Structure element column]                    |
     * (1) [Backendlayout column]                        |
     * --------------------------------------------------+
     *
     * The specifier is used to obtain the initial variants and can be of type array or string. In case an array is
     * supplied it will be used directly. In case of a string the interpretation is as following:
     *
     *  - Empty string: A default variants should be used
     *  - Field name:   In case self::contentElementData contains a field named $specifier its content will be used
     *                  to obtain a variants
     *  - TS path:      $specifier defines a complete TS path or the last segment from the default path.
     *
     * @param array|string $specifier
     * @return array
     * @see StructureProcessor, BackendlayoutService, ContentElementService
     */
    public static function getVariants($specifier = ''): array
    {
        $initialVariants = null;
        if (is_array($specifier)) {
            $initialVariants = $specifier;
        }
        if (is_string($specifier)) {
            $initialVariants = self::getInitialVariants($specifier);
        }
        $variants = StructureVariantsUtility::getStructureVariants($initialVariants);
        ($modificationStack = GeneralUtility::makeInstance(ModificationStack::class))->reset();
        foreach (self::$stack as $variantsModifier) {
            $variants = $variantsModifier->getVariants() ?? $variants;
            $modification = new Modification($variants, $variantsModifier);
            $variants = $modification->getResultingVariants();
            $modificationStack->addModification($modification);
        }
        return $variants;
    }

    private static function getInitialVariants(string $specifier = ''): ?array
    {
        $result = null;
        $typoScriptService = new TypoScriptService();

        // Get default variants
        if ($specifier === '') {
            // Default variants defined by the current content element
            if (isset(self::$contentElementData['tx_pizpalue_image_variants'])) {
                $result = $typoScriptService->getVariants(self::$contentElementData['tx_pizpalue_image_variants']);
            }
            if ($result === null) {
                // Default variants defined in TS
                $result = $typoScriptService->getVariants('variants');
            }
            return $result;
        }

        // Get specified variants
        $result = null;
        if (array_key_exists($specifier, self::$contentElementData)) {
            // Variants defined by the current content element
            $result = $typoScriptService->getVariants(self::$contentElementData[$specifier]);
        }
        if ($result === null) {
            // Variants from TS ($variants contains TS path or last TS path segment)
            $result = $typoScriptService->getVariants($specifier);
        }
        if ($result === null) {
            // Default variants defined in TS
            $result = $typoScriptService->getVariants('variants');
        }
        return $result;
    }
}
