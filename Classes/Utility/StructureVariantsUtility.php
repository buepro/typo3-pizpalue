<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

use BK2K\BootstrapPackage\Utility\ImageVariantsUtility;

/**
 * Calculate structure variants
 *
 * In addition to `BK2K\BootstrapPackage\Utility\ImageVariantsUtility` margins can be defined. The margins are added
 * to the widths prior passing processing to the `ImageVariantsUtility`. The resulting variants calculation is as
 * following: $variants = ($variants.widths - margins - gutters) * $multipliers - corrections.
 *
 * Note: Margins are needed to render bootstrap columns correctly.
 *
 * @see \Buepro\Pizpalue\ViewHelpers\Render\Bootstrap\ColumnViewHelper
 */
class StructureVariantsUtility
{
    public const BREAKPOINTS = ['default', 'xlarge', 'large', 'medium', 'small', 'extrasmall'];

    /**
     * @param array|null $variants
     * @param array|null $margins
     * @param array|null $gutters
     * @param array|null $multiplier
     * @param array|null $corrections
     * @param float|null $aspectRatio
     * @return array
     */
    public static function getStructureVariants(
        ?array $variants = null,
        ?array $margins = null,
        ?array $gutters = null,
        ?array $multiplier = null,
        ?array $corrections = null,
        ?float $aspectRatio = null
    ): array {
        $variants = $variants !== null ? $variants : [];
        if ($margins !== null) {
            $variants = self::removeMargin($variants, $margins);
        }
        return ImageVariantsUtility::getImageVariants($variants, $multiplier, $gutters, $corrections, $aspectRatio);
    }

    /**
     * @param array $variants
     * @param array $margins
     * @return array
     */
    protected static function removeMargin(array $variants, array $margins): array
    {
        foreach ($margins as $variant => $value) {
            if (is_numeric($value) && isset($variants[$variant]['width'])) {
                $variants[$variant]['width'] = (int) round($variants[$variant]['width'] - (float) $value);
            }
        }
        return $variants;
    }

    /**
     * @param float|array $property
     * @return array
     */
    public static function getVectorProperty($property): array
    {
        if (!is_array($property)) {
            $property = array_fill_keys(self::BREAKPOINTS, $property);
        }
        return $property;
    }
}
