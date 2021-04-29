<?php
declare(strict_types=1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

use BK2K\BootstrapPackage\Utility\ImageVariantsUtility;
use Buepro\Pizpalue\DataProcessing\StructureProcessor;
use Buepro\Pizpalue\Domain\Model\VariantsModifier;
use Buepro\Pizpalue\Service\BackendlayoutService;
use Buepro\Pizpalue\Service\ContentElementService;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StructureVariantsUtility
 *
 */
class StructureVariantsUtility
{
    /**
     * Maintains VariantsModifiers from elements modifying the available content space.
     *
     * @var VariantsModifier[]
     * @see ImageVariantsUtility::getImageVariants()
     */
    private static $variantsModifierStack = [];

    /**
     * Data from the content element currently being rendered.
     *
     * @var array
     * @see StructureProcessor
     */
    private static $contentElementData = [];

    /**
     * Get the last pushed variantsModifier. Doesn't change self::variantsModifierStack.
     *
     * @return VariantsModifier
     */
    public static function getVariantsModifier(): VariantsModifier
    {
        if (count(self::$variantsModifierStack)) {
            $variantsModifier = array_pop(self::$variantsModifierStack);
            self::$variantsModifierStack[] = $variantsModifier;
        } else {
            $variantsModifier = GeneralUtility::makeInstance(VariantsModifier::class);
        }
        return $variantsModifier;
    }

    public static function pushVariantsModifier(VariantsModifier $variantsModifier)
    {
        self::$variantsModifierStack[] = $variantsModifier;
    }

    public static function popVariantsModifier(): ?VariantsModifier
    {
        if (count(self::$variantsModifierStack)) {
            return array_pop(self::$variantsModifierStack);
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
     * (3) [Structure element 2 column]                   > variantsModifierStack
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
     * When processing the variantsModifierStack a variantsModifier might contain a variants. This variants serves
     * as a new base for the subsequent variantsModifiers. A use case might be a modal dialog element where the button
     * to activate the modal dialog is rendered in a column. For the images in the modal dialog to be rendered
     * correctly a new variants base has to be available. In the following illustration the stack element (3) would
     * contain a variants hence the variantsModifiers (1) and (2) haven't an effect any more.
     *
     * --------------------------------------------------+
     * (5) [Content element with images]                 |
     * (4) [Structure element column]                    |
     * (3) [Content element to render modal dialog]       > variantsModifierStack
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
    public static function getVariants($specifier): array
    {
        $initialVariants = [];
        if ($specifier && is_array($specifier)) {
            $initialVariants = $specifier;
        }
        if (!$initialVariants) {
            $initialVariants = self::getInitialVariants((string) $specifier);
        }
        $variants = ImageVariantsUtility::getImageVariants($initialVariants);
        foreach (self::$variantsModifierStack as $variantsModifier) {
            $variants = ImageVariantsUtility::getImageVariants(
                $variantsModifier->getVariants() ?? $variants,
                $variantsModifier->getMultiplier(),
                $variantsModifier->getGutter(),
                $variantsModifier->getCorrection()
            );
        }
        return $variants;
    }

    /**
     * @param string $specifier
     * @return array
     */
    private static function getInitialVariants(string $specifier = ''): array
    {
        // Get default variants
        if (!$specifier) {
            // Default variants defined by the current content element
            $result = self::getTypoScriptValue(self::$contentElementData['tx_pizpalue_image_variants']);
            if (!$result) {
                // Default variants defined in TS
                $result = self::getTypoScriptValue('variants');
            }
            return $result;
        }

        // Get specified variants
        $result = [];
        if (array_key_exists($specifier, self::$contentElementData)) {
            // Variants defined by the current content element
            $result = self::getTypoScriptValue(self::$contentElementData[$specifier]);
        }
        if (!$result) {
            // Variants from TS ($variants contains TS path or last TS path segment)
            $result = self::getTypoScriptValue($specifier);
        }
        if (!$result) {
            // Default variants defined in TS
            $result = self::getTypoScriptValue('variants');
        }
        return $result;
    }

    public static function getVariantsModifierStack(): array
    {
        return self::$variantsModifierStack;
    }

    public static function setContentElementData($data)
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
     * @param string|null $tsPath
     * @return array|mixed
     */
    public static function getTypoScriptValue($tsPath)
    {
        $result = [];
        /** @var TypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $setup = $typoScriptService->convertTypoScriptArrayToPlainArray($GLOBALS['TSFE']->tmpl->setup);
        $parts = GeneralUtility::trimExplode('.', $tsPath, true);
        if (count($parts) === 1 && is_array($setup['lib']['contentElement']['settings']['responsiveimages'][$parts[0]])) {
            $result = $setup['lib']['contentElement']['settings']['responsiveimages'][$parts[0]];
        } else {
            foreach ($parts as $part) {
                if (is_array($setup)) {
                    $setup = $setup[$part] ?? null;
                }
            }
            if (is_array($setup)) {
                $result = $setup;
            }
        }
        return $result;
    }
}
