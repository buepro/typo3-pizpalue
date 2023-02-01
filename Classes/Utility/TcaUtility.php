<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaUtility
{
    /** @var ?array */
    private static $breakpoints = null;

    /** @var ?array[] */
    private static $aspectRatios = null;

    /** @var ?array[] */
    private static $cropVariants = null;

    public static function getBreakpoints(): array
    {
        // Return cached breakpoints
        if (self::$breakpoints !== null) {
            return self::$breakpoints;
        }
        self::$breakpoints = ['default', 'large', 'medium', 'small', 'extrasmall'];
        $predefinedCropVariants = $GLOBALS['TCA']['tt_content']['columns']['background_image']['config']
            ['overrideChildTca']['columns']['crop']['config']['cropVariants'] ?? null;
        if (is_array($predefinedCropVariants)) {
            self::$breakpoints = array_keys($predefinedCropVariants);
        }
        return self::$breakpoints;
    }

    public static function getAspectRatios(): array
    {
        // Return the cached aspect ratios
        if (self::$aspectRatios !== null) {
            return self::$aspectRatios;
        }
        self::$aspectRatios = [
            '2:1' => [
                'title' => '2:1',
                'value' => 2
            ],
            '16:9' => [
                'title' => '16:9',
                'value' => 16 / 9
            ],
            '4:3' => [
                'title' => '4:3',
                'value' => 4 / 3
            ],
            '1:1' => [
                'title' => '1:1',
                'value' => 1.0
            ],
            '3:4' => [
                'title' => '3:4',
                'value' => 3 / 4
            ],
            '9:16' => [
                'title' => '9:16',
                'value' => 9 / 16
            ],
            '1:2' => [
                'title' => '1:2',
                'value' => 0.5
            ],
            'NaN' => [
                'title' => 'Free',
                'value' => 0.0
            ],
        ];
        $predefinedAspectRatios = $GLOBALS['TCA']['tt_content']['columns']['background_image']['config']['overrideChildTca']
            ['columns']['crop']['config']['cropVariants']['default']['allowedAspectRatios'] ?? null;
        if (is_array($predefinedAspectRatios)) {
            self::$aspectRatios = array_replace_recursive(self::$aspectRatios, $predefinedAspectRatios);
        }
        return self::$aspectRatios;
    }

    public static function getCropVariants(): array
    {
        // Return the cached crop variants
        if (self::$cropVariants !== null) {
            return self::$cropVariants;
        }
        $defaultCropVariants = [
            'title' => 'Default',
            'allowedAspectRatios' => self::getAspectRatios(),
            'selectedRatio' => 'NaN',
            'cropArea' => [
                'x' => 0.0,
                'y' => 0.0,
                'width' => 1.0,
                'height' => 1.0,
            ],
        ];
        self::$cropVariants = [];
        foreach (self::getBreakpoints() as $breakpoint) {
            self::$cropVariants[$breakpoint] = $defaultCropVariants;
            self::$cropVariants[$breakpoint]['title'] = $breakpoint;
        }
        $predefinedCropVariants = $GLOBALS['TCA']['tt_content']['columns']['background_image']['config']['overrideChildTca']
            ['columns']['crop']['config']['cropVariants'] ?? null;
        if (is_array($predefinedCropVariants)) {
            self::$cropVariants = array_replace_recursive(self::$cropVariants, $predefinedCropVariants);
        }
        return self::$cropVariants;
    }

    public static function assignAllowedAspectRatiosToCropVariants(array &$cropVariants): void
    {
        foreach (self::getBreakpoints() as $breakpoint) {
            $cropVariants[$breakpoint]['allowedAspectRatios'] = self::getAspectRatios();
        }
    }

    public static function setAllowedAspectRatiosForField(string $table, string $field): void
    {
        if (
            !isset($GLOBALS['TCA'][$table]['columns']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'])
        ) {
            $GLOBALS['TCA'][$table]['columns']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [];
        }
        self::assignAllowedAspectRatiosToCropVariants(
            $GLOBALS['TCA'][$table]['columns']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants']
        );
    }

    public static function setAllowedAspectRatiosForCType(string $table, string $cType, string $field): void
    {
        if (
            !isset($GLOBALS['TCA'][$table]['types'][$cType]['columnsOverrides']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'])
        ) {
            $GLOBALS['TCA'][$table]['types'][$cType]['columnsOverrides']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [];
        }
        self::assignAllowedAspectRatiosToCropVariants(
            $GLOBALS['TCA'][$table]['types'][$cType]['columnsOverrides']
                [$field]['config']['overrideChildTca']['columns']['crop']['config']['cropVariants']
        );
    }

    public static function removeFieldsFromPalette(string $table, string $palette, string $fields): void
    {
        if (($items = $GLOBALS['TCA'][$table]['palettes'][$palette]['showitem'] ?? null) === null) {
            return;
        }
        $removeFields = GeneralUtility::trimExplode(',', $fields, true);
        $paletteItems = GeneralUtility::trimExplode(',', $items, true);
        $newPaletteItems = [];
        $previousPaletteItem = '';
        foreach ($paletteItems as $paletteItem) {
            $paletteItemParts = GeneralUtility::trimExplode(';', $paletteItem);
            if (
                ($paletteField = array_shift($paletteItemParts)) !== null &&
                in_array($paletteField, $removeFields, true)
            ) {
                continue;
            }
            // Prevent add subsequent '--linebreak--'
            if ($previousPaletteItem !== $paletteItem) {
                $newPaletteItems[] = $paletteItem;
                $previousPaletteItem = $paletteItem;
            }
        }
        if (reset($newPaletteItems) === '--linebreak--') {
            array_shift($newPaletteItems);
        }
        if (end($newPaletteItems) === '--linebreak--') {
            array_pop($newPaletteItems);
        }
        $GLOBALS['TCA'][$table]['palettes'][$palette]['showitem'] = implode(',', $newPaletteItems);
    }
}
